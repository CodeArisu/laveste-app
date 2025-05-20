<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\api\ApiBaseController;
use App\Models\Catalog;
use App\Services\CatalogService;

class CatalogController extends ApiBaseController
{
    public function __construct(protected CatalogService $displayService){}

    public function index()
    {
        $catalog = Catalog::all();
        return view('src.cashier.product', ['catalogs' => $catalog]);
    }
}
