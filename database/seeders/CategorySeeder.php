<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            [
                'id_category' => '1',
                'category_name' => 'Network',
            ],
            [
                'id_category' => '2',
                'category_name' => 'Food',
            ],
            [
                'id_category' => '3',
                'category_name' => 'Event',
            ],
        ]);
    }
}
