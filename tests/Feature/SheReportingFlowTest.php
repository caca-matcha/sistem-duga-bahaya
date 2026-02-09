<?php

namespace Tests\Feature;

use App\Models\Hazard;
use App\Models\Location;
use App\Models\Map;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SheReportingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_she_reporting_flow_lifecycle()
    {
        Storage::fake('public');

        // 1. SETUP USERS & ROLES
        $sheUser = User::factory()->create(['role' => 'she', 'name' => 'SHE Manager']);
        $picUser = User::factory()->create(['role' => 'karyawan', 'name' => 'PIC Staff']);
        $leaderUser = User::factory()->create(['role' => 'karyawan', 'name' => 'Leader Staff']);
        $reporterUser = User::factory()->create(['role' => 'karyawan', 'name' => 'Reporter Staff']);

        // 2. SETUP LOCATION w/ PIC & LEADER
        $map = Map::factory()->create(['created_by' => $sheUser->id]);
        $location = Location::create([
            'map_id' => $map->id,
            'name' => 'Test Area Hazard Point',
            'type' => 'area',
            'location_id_string' => 'TEST-LOC-01',
            'created_by' => $sheUser->id,
            'pic_id' => $picUser->id,
            'leader_id' => $leaderUser->id,
        ]);

        // 3. REPORTING (Karyawan)
        $this->actingAs($reporterUser);

        $response = $this->post(route('karyawan.hazards.store'), [
            'NPK' => '12345',
            'dept' => 'IT Dept',
            'tgl_observasi' => now()->format('Y-m-d'),
            'location_id' => $location->id,
            'map_id' => $map->id,
            'area_gedung' => $map->name,
            'area_name' => $location->name,
            'area_id' => $location->location_id_string,
            'area_type' => $location->type,
            'lokasi_detail_manual' => 'Near the server rack',
            'deskripsi_bahaya' => 'Loose cable causing trip hazard',
            'kategori_stop6' => 'Safety',
            'ide_penanggulangan' => 'Secure with zip ties',
            'foto_bukti' => UploadedFile::fake()->image('evidence.jpg'),
            'tingkat_keparahan' => 3,
            'kemungkinan_terjadi' => 3,
            'risk_score' => 9,
            'kategori_resiko' => 'Medium',
            // risk_score & kategori_resiko are usually calculated client-side but validated server-side or recalculated

        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect(route('karyawan.dashboard'));

        // 4. ASSERT AUTO-ASSIGNMENT & STATUS
        $hazard = Hazard::latest()->first();
        $this->assertEquals('menunggu validasi', $hazard->status);
        $this->assertEquals($picUser->id, $hazard->pic_id);
        $this->assertEquals($leaderUser->id, $hazard->leader_id);

        // 5. SHE VALIDATION & FORWARDING (SHE)
        $this->actingAs($sheUser);

        // Simulate SHE using 'forwardToPic'
        $response = $this->post(route('she.hazards.forwardToPic', $hazard), [
            'faktor_penyebab' => 'Unsafe Condition',
            'final_tingkat_keparahan' => 3,
            'final_kemungkinan_terjadi' => 3,
            'final_kategori_stop6' => 'Safety',
            'pic_id' => $picUser->id, // Ensuring PIC is still assigned or updated
            'leader_id' => $leaderUser->id,
        ]);

        $response->assertSessionHasNoErrors();
        $hazard->refresh();
        $this->assertEquals('diproses', $hazard->status); // Status should be 'diproses' now
        $this->assertEquals(9, $hazard->risk_score);

        // 6. PIC ACTION (PIC)
        $this->actingAs($picUser);

        // A. Set Deadline
        $response = $this->put(route('karyawan.hazards.update', $hazard), [
            'action' => 'set_deadline',
            'target_penyelesaian' => now()->addDays(3)->format('Y-m-d'),
        ]);
        $response->assertSessionHasNoErrors();
        $hazard->refresh();
        $this->assertNotNull($hazard->target_penyelesaian);

        // B. Complete Task
        $response = $this->put(route('karyawan.hazards.update', $hazard), [
            'action' => 'complete',
            'tindakan_perbaikan' => 'Secured cables with zip ties and tape.',
            'foto_bukti_penyelesaian' => UploadedFile::fake()->image('completion.jpg'),
        ]);

        $response->assertSessionHasNoErrors();
        $hazard->refresh();
        $this->assertEquals('menunggu verifikasi', $hazard->status);
        $this->assertNotNull($hazard->tindakan_perbaikan);
        $this->assertNotNull($hazard->report_selesai); // Completion time set by PIC
        $this->assertNotNull($hazard->foto_bukti_penyelesaian);

        // 7. SHE VERIFICATION (SHE)
        $this->actingAs($sheUser);

        // Verify Selesai
        $response = $this->put(route('she.hazards.updateStatus', $hazard), [
            'status' => 'selesai',
        ]);

        $response->assertSessionHasNoErrors();
        $hazard->refresh();
        $this->assertEquals('selesai', $hazard->status);
    }
}
