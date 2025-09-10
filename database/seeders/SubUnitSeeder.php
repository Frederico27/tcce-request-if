<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubUnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_unit')->insert([
            [
                'id_sub_unit' => '1',
                'nama_sub_unit' => 'MNO',
            ],
            [
                'id_sub_unit' => '2',
                'nama_sub_unit' => 'Sub Unit 2',
            ],
            [
                'id_sub_unit' => '3',
                'nama_sub_unit' => 'Sub Unit 3',
            ],
        ]);
    }
}
