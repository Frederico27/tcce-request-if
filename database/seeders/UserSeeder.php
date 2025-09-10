<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'nik' => '12345',
            'full_name' => 'Riko',
            'position_name' => 'Admin Manager',
            'phone_number' => '08123456789',
            'id_sub_unit' => '1',
        ]);

        User::create([
            'nik' => '12346',
            'full_name' => 'John Doe',
            'position_name' => 'Asistant Manager',
            'phone_number' => '08123456780',
            'id_sub_unit' => '2',
        ]);
    }
}
