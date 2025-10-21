<?php

namespace App\Services\Caching;

use App\Http\Resources\ProductResource;
use App\Http\Resources\TypeResource;
use App\Repositories\ProductRepository;
use Illuminate\Support\Facades\Cache;

class CachedProductServices extends ProductRepository
{
    protected $cacheDuration = 60;
    protected $cacheKeyPrefix = 'product_';

    protected function getCacheKey($identifier)
    {
        return $this->cacheKeyPrefix . $identifier;
    }

    public function getCachedProduct($identifier, $callback)
    {
        $cacheKey = $this->getCacheKey($identifier);
        return cache()->remember($cacheKey, $this->cacheDuration * 60, $callback);
    }

    public function clearCachedProduct($identifier)
    {
        $cacheKey = $this->getCacheKey($identifier);
        cache()->forget($cacheKey);
    }

    public function getProductCollection()
    {
        return Cache::remember('product_collection', $this->cacheDuration * 60, function () {
            $products = $this->model->with(['types', 'subtypes'])->get();
            return ProductResource::collection($products);
        });
    }

    public function getType()
    {
        return Cache::remember('type_collection', $this->cacheDuration * 60, function () {
            return TypeResource::collection($this->type->all());
        });
    }

    public function getSubtype()
    {
        return Cache::remember('subtype_collection', $this->cacheDuration * 60, function () {
            return TypeResource::collection($this->subtype->all());
        });
    }

    public function clearAllCaches()
    {
        Cache::forget('product_collection');
        Cache::forget('type_collection');
        Cache::forget('subtype_collection');
    }
}
