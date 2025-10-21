<?php

namespace App\Http\Controllers;

use App\Http\{Requests\ProductRequest};
use App\Models\Products\Product;
use App\Services\Caching\CachedProductServices;
use App\Services\ProductService;
use App\Enum\Measurement;
use App\Enum\ConditionStatus;

class ProductController
{
    public function __construct(
        // product service
        protected ProductService $productService,
        // cached product service
        protected CachedProductServices $cachedProductServices
    ) {}

    public function index()
    {
        return view('src.admin.adproduct',
        [
            'products' => $this->cachedProductServices->getProductCollection()
        ]);
    }

    public function create()
    {
        return view('src.admin.adproducts.productadd',
        [
            'types' => $this->cachedProductServices->getType(),
            'subtypes' => $this->cachedProductServices->getSubtype()
        ]);
    }

    public function store(ProductRequest $request)
    {
        $createdProduct = $this->productService->requestCreateProduct($request);
        return redirect()->route($createdProduct['route'])->with('success', $createdProduct['message']);
    }

    public function show(Product $product)
    {
        $product = Product::with(['subtypes', 'types', 'supplier'])->findOrFail($product->id);
        return view(
            'src.dashboard.products.details',
            ['products' => $product, 'conditions' => ConditionStatus::cases(), 'measurements' => Measurement::cases()]
        );
    }

    public function edit(Product $product)
    {
        $product = Product::with(['subtypes', 'types', 'supplier'])->findOrFail($product->id);
        return view('src.admin.adproducts.editprod',
        [
            'products' => $product,
            'types' => $this->cachedProductServices->getType(),
            'subtypes' => $this->cachedProductServices->getSubtype()
        ]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $updatedProduct = $this->productService->requestUpdateProduct($request, $product);
        return redirect()->back()->with('success', $updatedProduct['message']);
    }

    public function destroy(Product $product)
    {
        $deletedProduct = $this->productService->requestDeleteProduct($product);
        return redirect()->route($deletedProduct['route'])->with('deleted', $deletedProduct['message']);
    }
}
