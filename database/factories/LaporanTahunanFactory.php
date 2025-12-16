<?php

namespace Database\Factories;

use App\Models\LaporanTahunan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanTahunanFactory extends Factory
{
    protected $model = LaporanTahunan::class;

    public function definition(): array
    {
        $year = $this->faker->year;

        return [
            'judul' => 'Laporan Tahunan ' . $year,
            'file_path' => 'laporan_tahunan/dummy.pdf',
            'uploaded_by' => User::where('role', '!=', 'user')->inRandomOrder()->first()->id,
            'tanggal_laporan' => $this->faker->dateTimeBetween($year . '-01-01', $year . '-12-31'),
            'tahun' => $year,
        ];
    }
}
