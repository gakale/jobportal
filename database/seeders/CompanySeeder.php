<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('companies')->insert([
            'name' => 'Example Company',
            'description' => 'This is an example company.',
            'slug' => Str::slug('Example Company'),
            'logo' => null,
            'website' => 'https://www.example.com',
            'email' => 'info@example.com',
            'password' => bcrypt('password'),
            'address' => '123 Example Street',
            'city' => 'Example City',
            'state' => 'Example State',
            'zip' => '12345',
            'country' => 'Example Country',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
