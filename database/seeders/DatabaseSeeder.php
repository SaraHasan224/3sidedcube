<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
//        $this->call(RolesSeeder::class);
//        $this->call(AdminSeeder::class);

        $this->call(CountriesSeeder::class);
        $this->call(ProvinceSeeder::class);
        $this->call(CitiesSeeder::class);

        $this->call(CustomerSeeder::class);

        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
