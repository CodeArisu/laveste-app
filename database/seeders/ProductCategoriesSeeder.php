<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

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
                ['subtype_name' => 'gown 1'],
                ['subtype_name' => 'gown 2'],
                ['subtype_name' => 'gown 3'],
            ], [
                ['subtype_name' => 'tuxedo 1'],
                ['subtype_name' => 'tuxedo 2'],
                ['subtype_name' => 'tuxedo 3'],
            ]
        ];

        foreach ($categories['types'] as $types) {
            DB::table('types')->insert([
                $types,
            ]);
        }

        foreach ($categories['subtypes'] as $subtypes) {
            DB::table('subtypes')->insert([
                $subtypes
            ]);
        }
    }
}
