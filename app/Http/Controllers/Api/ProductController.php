<?php

namespace App\Http\Controllers\Api;

use App\DTOs\ProductDTO;
use App\Enum\ConditionStatus;
use App\Enum\Measurement;
use App\Enum\Routes;
use App\Http\Controllers\api\ApiBaseController;
use App\Http\Resources\SubtypeResource;
use App\Http\Resources\TypeResource;
use App\Http\{Requests\ProductRequest, Resources\ProductResource};
use App\Models\Products\Product;
use App\Models\Products\Subtype;
use App\Models\Products\Type;
use App\Services\Products\ProductService;
use Illuminate\Http\JsonResponse;   

class ProductController extends ApiBaseController
{   
    private $productCollection;
    private $types, $subtypes;

    public function __construct(protected ProductService $productService) {
        $product = Product::with(['types', 'subtypes'])->get();

        $this->productCollection = ProductResource::collection($product);
        $this->types = TypeResource::collection(Type::all());
        $this->subtypes = SubtypeResource::collection(Subtype::all());
    }

    public function index()
    {    
        return view('src.admin.Products.ProductIndex', 
        ['products' => $this->productCollection]);
    }

    public function create()
    {   
        return view('src.admin.Products.ProductAdd', 
        ['types' => $this->types, 'subtypes' => $this->subtypes]);
    }

    public function store(ProductRequest $request)
    {   
        $createdProduct = $this->productService->requestCreateProduct($request);
        return Routes::ProductForm->toRoute()->with('success', $createdProduct['message']);
    }

    public function show(Product $product)
    {   
        $product = Product::with(['subtypes', 'types', 'supplier'])->findOrFail($product->id);
        return view('src.admin.Products.ProductDetails', 
        ['products' => $product, 'conditions' => ConditionStatus::cases(), 'measurements' => Measurement::cases()]);
    }

    public function edit(Product $product)
    {    
        $product = Product::with(['subtypes', 'types', 'supplier'])->findOrFail($product->id);
        return view('src.admin.Products.ProductEdit', 
        ['products' => $product, 'types' => $this->types, 'subtypes' => $this->subtypes]);
    }
}
