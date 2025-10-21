<?php

namespace App\Http\Controllers;

use App\Models\Catalog;
use App\Services\CatalogService;

class CatalogController extends BaseController
{
    public function __construct(protected CatalogService $displayService){}

    public function index()
    {
        $catalog = Catalog::all();
        return view('src.cashier.product', ['catalogs' => $catalog]);
    }
}
