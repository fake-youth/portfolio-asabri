<?php

namespace Database\Factories;

use App\Models\LaporanBulanan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanBulananFactory extends Factory
{
    protected $model = LaporanBulanan::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 year', 'now');

        return [
            'judul' => 'Laporan Bulanan ' . $date->format('F Y'),
            'file_path' => 'laporan_bulanan/dummy.pdf',
            'uploaded_by' => User::where('role', '!=', 'user')->inRandomOrder()->first()->id,
            'tanggal_laporan' => $date,
            'bulan' => $date->format('F'),
            'tahun' => $date->format('Y'),
        ];
    }
}
