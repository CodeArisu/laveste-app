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
    ) {}

    public function index()
    {
        $products = $this->productService->getProductCollection();

        return view('src.dashboard.pages.products', [
            'products' => $products,
        ]);
    }

    public function create()
    {
        $types = $this->productService->getType();
        $subtypes = $this->productService->getSubtype();

        return view('src.dashboard.products.add', [
            'types' => $types,
            'subtypes' => $subtypes
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
        return view('src.dashboard.products.edit', [
            'products' => $product,
            'types' => $this->productService->getType(),
            'subtypes' => $this->productService->getSubtype()
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
