<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Patient::factory()->count(30)->create();
        Vehicle::factory()->count(30)->create();
    }
}
