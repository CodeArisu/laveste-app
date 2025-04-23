<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductCategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {   
        $categories = [
            'types' => [
                ['type_name' => 'gown'],
                ['type_name' => 'tuxedo'],
            ],
            'subtypes' => [
                ['subtype_name' => 'ball gown'],
                ['subtype_name' => 'evening gown'],
                ['subtype_name' => 'wedding gown'],
            ]
        ];

        foreach($categories['types'] as $types)
        {
            DB::table('types')->insert([
                $types,
            ]);
        }

        foreach($categories['subtypes'] as $subtypes) 
        {
            DB::table('subtypes')->insert([
                $subtypes
            ]);
        }
    }
}
