<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function stores_products_successfully()
    {
        $productData = [
            "product_name" => "test product",
            "original_price" => 999,
            "description" => "lorem ipsum",

            "supplier_name" => "test supplier",
            "company_name" => "test company",
            "address" => "lorem ipsum",
            "contact" => "92345678921",

            "type" => "test type",
            "subtype" => "test subtype",
        ];

        $response = $this->postJson(route('store.product'), $productData);

        $response->assertStatus(202)->assertJson([
            'message' => 'Product created successfully',
        ]);
    }
}
