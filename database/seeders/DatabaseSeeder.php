<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        User::factory()->create([
            'uuid' => uniqid(),
            'type' => User::TYPE_SUPER,
            'name' => 'Administrator',
            'email' => 'admin@fammalawi.org',
        ]);
    }
}
