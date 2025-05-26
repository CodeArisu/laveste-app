<?php

namespace App\Services\Products;

use App\Models\Products\ProductCategories;
use App\Repositories\BaseRepository;

class CategoryServices extends BaseRepository
{
    public function __construct()
    {
        $this->model = new ProductCategories();
    }
}
