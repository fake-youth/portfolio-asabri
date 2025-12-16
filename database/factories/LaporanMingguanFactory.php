<?php

namespace Database\Factories;

use App\Models\LaporanMingguan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LaporanMingguanFactory extends Factory
{
    protected $model = LaporanMingguan::class;

    public function definition(): array
    {
        $date = $this->faker->dateTimeBetween('-1 year', 'now');
        $minggu = ceil($date->format('j') / 7);

        return [
            'judul' => 'Laporan Mingguan ' . $date->format('F Y'),
            'file_path' => 'laporan_mingguan/dummy.pdf',
            'uploaded_by' => User::where('role', '!=', 'user')->inRandomOrder()->first()->id,
            'tanggal_laporan' => $date,
            'periode_minggu' => "Minggu ke-{$minggu} " . $date->format('F Y'),
        ];
    }
}
