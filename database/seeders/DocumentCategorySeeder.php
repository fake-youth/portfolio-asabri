<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentCategory;
use App\Models\User;

class DocumentCategorySeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::first(); // Assuming superadmin exists or just pick first user
    }
}