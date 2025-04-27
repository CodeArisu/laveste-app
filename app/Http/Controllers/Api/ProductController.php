<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Http\Resources\SubtypeResource;
use App\Http\Resources\TypeResource;
use App\Http\{Requests\ProductRequest, Resources\ProductResource};
use App\Models\Products\Product;
use App\Models\Products\Subtype;
use App\Models\Products\Type;
use App\Services\ProductService;
use Illuminate\Http\JsonResponse;   

class ProductController extends ApiBaseController
{   
    private $productCollection;
    private $types;
    private $subtypes;

    public function __construct(protected ProductService $productService) {
        $product = Product::with(['types', 'subtypes'])->get();

        $this->productCollection = ProductResource::collection($product);
        $this->types = TypeResource::collection(Type::all());
        $this->subtypes = SubtypeResource::collection(Subtype::all());
    }

    public function index()
    {    
        return view('src.admin.adproduct', ['products' => $this->productCollection]);
    }

    public function create()
    {   
        return view('src.admin.adproducts.productadd', ['types' => $this->types, 'subtypes' => $this->subtypes]);
    }

    public function store(ProductRequest $request)
    {   
        $createdProduct = $this->productService->requestCreateProduct($request);
        return redirect()->route('dashboard.product.form')->with('message', $createdProduct['message']);
    }

    public function show(Product $product)
    {   
        $product = Product::with(['subtypes', 'types', 'supplier'])->findOrFail($product->id);
        return view('src.admin.adproducts.infoprod', ['products' => $product]);
    }

    public function edit()
    {   
        return view('src.admin.adproducts.editprod', ['products' => $this->productCollection]);
    }

    public function update(ProductRequest $request, Product $product) : JsonResponse
    {   
        $updatedProduct = $this->productService->requestUpdateProduct($request, $product);
        return $this->sendResponse($updatedProduct['data'], $updatedProduct['message']);
    }

    public function destroy(Product $product)
    {
        $deletedProduct = $this->productService->requestDeleteProduct($product);
        // return $this->sendResponse($deletedProduct['data'], $deletedProduct['message']);
        return redirect()->route('dashboard.product.index')->with(['success', $deletedProduct['message']]);
    }
}
