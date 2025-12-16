<?php

namespace Database\Factories;

use App\Models\FundFactSheet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class FundFactSheetFactory extends Factory
{
    protected $model = FundFactSheet::class;

    public function definition(): array
    {
        return [
            'judul' => 'Fund Fact Sheet ' . $this->faker->monthName . ' ' . $this->faker->year,
            'file_path' => 'laporan_fundfactsheet/dummy.pdf',
            'uploaded_by' => User::where('role', '!=', 'user')->inRandomOrder()->first()->id,
            'tanggal_laporan' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }
}

