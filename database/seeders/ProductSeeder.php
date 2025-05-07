<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $data = [
        //     'product' => [
        //         [
        //         'product_name' => 'Sample Product',
        //         'product_description' => 'This is a sample product description.',
        //         'product_price' => 19.99,
        //     ],
        //         [
        //         'product_name' => 'Sample Product 2',
        //         'product_description' => 'This is a sample product description 2.',
        //         'product_price' => 29.99,
        //         ],
        //     ],
        //     'supplier' => [
        //         [
        //         'supplier_name' => 'Supplier Name',
        //         'company_name' => 'Company Name',
        //         'address' => 'Address',
        //         'contact' => 1234567890,
        //         ],
        //         [
        //         'supplier_name' => 'Supplier Name 2',
        //         'company_name' => 'Company Name 2',
        //         'address' => 'Address 2',
        //         'contact' => 9876543210,
        //         ],
        //     ],
        //     'categories' => [
        //         [
        //             'type' => 'Type',
        //             'subtype' => 
        //             [
        //                 'Sub Type 1',
        //                 'Sub Type 2',
        //             ],
        //         ],
        //         [
        //             'type' => 'Type 2',
        //             'subtype' => 
        //             [
        //                 'Sub Type 3',
        //                 'Sub Type 4',
        //             ],
        //         ],
        //     ],
        // ];

        // $product = new \App\Models\Products\Product();
        // $supplier = new \App\Models\Products\Supplier();
        // $category = new \App\Models\Products\ProductCategories();

        // foreach ($data['product'] as $productData) {
        //     $product->create($productData);
        // }
        // foreach ($data['supplier'] as $supplierData) {
        //     $supplier->create($supplierData);
        // }
        // foreach ($data['categories'] as $categoryData) {
        //     $category->create([
        //         'type' => $categoryData['type'],
        //         'subtype' => json_encode($categoryData['subtype']),
        //     ]);
        // }
    }
}
