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
            'area_gedung' => $this->faker->randomElement(['Gedung A', 'Gedung B', 'Gedung C', 'Gedung D']),
            
            'deskripsi_bahaya' => $this->faker->sentence(),
            'foto_bukti' => null, // Placeholder for actual photo
            'kategori_stop6' => $this->faker->randomElement(['A', 'B', 'C', 'D', 'E', 'F', 'O']),
            'faktor_penyebab' => $this->faker->randomElement(['Unsafe Action', 'Unsafe Condition']),
            'tingkat_keparahan' => $tingkatKeparahan,
            'kemungkinan_terjadi' => $kemungkinanTerjadi,
            'risk_score' => $skorResiko,
            'kategori_resiko' => $this->getKategoriResiko($skorResiko),
            'ide_penanggulangan' => $this->faker->paragraph(),
            'status' => $this->faker->randomElement(['menunggu validasi', 'ditolak', 'diproses', 'selesai']),
            'alasan_penolakan' => $this->faker->boolean(20) ? $this->faker->sentence() : null, // 20% chance of rejection reason
            'report_selesai' => $this->faker->boolean(70) ? $this->faker->dateTimeBetween('-6 months', 'now') : null, // 70% chance of being resolved
            'ditangani_oleh' => $this->faker->boolean(50) ? User::factory() : null, // 50% chance of being handled by another user
            'ditangani_pada' => $this->faker->boolean(50) ? $this->faker->dateTimeBetween('-6 months', 'now') : null,
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
