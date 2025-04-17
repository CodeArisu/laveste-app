<?php

namespace Tests\Feature;

use App\Http\Controllers\Api\ProductController;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function stores_products_successfully()
    {   
        $productServiceMock = Mockery::mock(ProductService::class);

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

        $fakeResponse = [
            'message' => 'Product created successfully',
        ];

        $requestMock = Mockery::mock(ProductRequest::class);
        $requestMock->shouldReceive('all')->andReturn($productData);

        $productServiceMock->shouldReceive('requestCreateProduct')
        ->once()
        ->with($requestMock)
        ->andReturn($fakeResponse);

        // Create an instance of ProductController with mock service
        $controller = new ProductController($productServiceMock);

        // Call store method
        $response = $controller->store($requestMock);

        // Assert response type
        $this->assertInstanceOf(JsonResponse::class, $response);

        // Assert response structure
        $responseData = $response->getData(true);
        $this->assertEquals($fakeResponse['message'], $responseData['message']);
        $this->assertEquals($fakeResponse['product'], $responseData['data']);
    }
    
}