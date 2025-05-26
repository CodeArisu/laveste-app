<?php


namespace App\Services\Products;

use App\Models\Products\Supplier;
use App\Repositories\BaseRepository;

class SupplierServices extends BaseRepository
{
    public function __construct()
    {
        $this->model = new Supplier();
    }
}
