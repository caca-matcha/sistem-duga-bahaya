<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_she_can_access_company_data_page()
    {
        $user = User::factory()->create(['role' => 'she']);

        $response = $this->actingAs($user)->get(route('she.company-data.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Perusahaan'); // Header
        $response->assertSee('PT Dharma Polimetal Tbk'); // Mock Data
    }

    public function test_karyawan_cannot_access_company_data_page()
    {
        $user = User::factory()->create(['role' => 'karyawan']);

        $response = $this->actingAs($user)->get(route('she.company-data.index'));

        $response->assertStatus(403); // Middleware role:she should block it
    }
}
