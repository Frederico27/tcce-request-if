<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('sub_categories')->insert([
            [
                'id_sub_category' => '1',
                'id_category' => '1',
                'sub_category_name' => 'Cable',
            ],
            [
                'id_sub_category' => '2',
                'id_category' => '1',
                'sub_category_name' => 'Router',
            ],
            [
                'id_sub_category' => '3',
                'id_category' => '2',
                'sub_category_name' => 'Lunch',
            ],
            [
                'id_sub_category' => '4',
                'id_category' => '2',
                'sub_category_name' => 'Dinner',
            ],
            [
                'id_sub_category' => '5',
                'id_category' => '3',
                'sub_category_name' => 'Conference',
            ],
            [
                'id_sub_category' => '6',
                'id_category' => '3',
                'sub_category_name' => 'Meetup',
            ],
        ]);
    }
}
