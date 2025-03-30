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
            "contact" => "9234567892",

            "type" => "testtype",
            "subtype" => ['type1', 'type2'],
        ];

        $response = $this->postJson(route('product.store'), $productData);

        $response->assertStatus(202)->assertJson([
            'message' => 'Product created successfully',
        ]);
    }
}
