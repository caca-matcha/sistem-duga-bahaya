<?php

namespace Database\Factories;

use App\Models\Cell;
use App\Models\Hazard;
use App\Models\Map;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Hazard>
 */
class HazardFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Hazard::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tingkatKeparahan = $this->faker->numberBetween(1, 5); // 1-5
        $kemungkinanTerjadi = $this->faker->numberBetween(1, 5); // 1-5
        $skorResiko = $tingkatKeparahan * $kemungkinanTerjadi;

        return [
            'user_id' => User::factory(), // Creates a new user if not specified
            'map_id' => Map::factory(),
            'cell_id' => Cell::factory(),
            'nama' => $this->faker->name(),
            'NPK' => $this->faker->unique()->randomNumber(5),
            'dept' => $this->faker->randomElement(['Produksi', 'Gudang', 'SHE', 'HRD', 'Maintenance']),
            'tgl_observasi' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'area_gedung' => $this->faker->randomElement(['Gedung A', 'Gedung B', 'Gedung C', 'Gedung D', 'Gedung E', 'Gedung F', 'Gedung G', 'Gedung I']),
            'aktivitas_kerja' => $this->faker->randomElement([// Gedung A
            'Packing', 
            'Produksi', 
            'Sorting',
            // Gedung B
            'Maintenance Mesin', 
            'Inspeksi Kualitas',
            // Gedung C
            'Administrasi', 
            'Meeting', 
            'Training',
            // Gedung D
            'Warehouse', 
            'Forklift', 
            'Loading / Unloading',
            // Gedung E
            'Laboratorium', 
            'Pengujian Sampel',
            // Gedung F
            'Welding', 
            'Assembly', 
            'Finishing',
            // Gedung G
            'Area Umum', 
            'Kantin', 
            'Mushola'
        ]),
            'deskripsi_bahaya' => $this->faker->sentence(),
            'foto_bukti' => null, // Placeholder for actual photo
            'kategori_stop6' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F', 'O']),
            'faktor_penyebab' => $this->faker->randomElement(['Unsafe Action', 'Unsafe Condition']),
            'tingkat_keparahan' => $tingkatKeparahan,
            'kemungkinan_terjadi' => $kemungkinanTerjadi,
            'risk_score' => $skorResiko,
            'kategori_resiko' => $this->getKategoriResiko($skorResiko),
            'ide_penanggulangan' => $this->faker->paragraph(),
            'status' => 'menunggu validasi',
            'alasan_penolakan' => null,
            'report_selesai' => null, 
            'ditangani_oleh' => null, 
            'ditangani_pada' => null,
        ];
    }

    protected function getKategoriResiko(int $skorResiko): string
    {
    if ($skorResiko <= 5) {
            return 'Low';
        } elseif ($skorResiko <= 12) {
            return 'Medium';
        } else {
            return 'High';
        }
    }


    public function forUser(User $user)
    {
        return $this->state(fn (array $attributes) => [
            'user_id' => $user->id,
        ]);
    }

    public function forMapAndCell(Map $map, Cell $cell)
    {
        return $this->state(fn (array $attributes) => [
            'map_id' => $map->id,
            'cell_id' => $cell->id,
        ]);
    }
}
