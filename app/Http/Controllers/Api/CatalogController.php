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
        $items = Catalog::all();
        return view('src.cashier.product', ['items' => $items]);
    }

    // public function store(CatalogRequest $request, Garment $garment) 
    // {
    //     $display = $this->displayService->requestDisplayGarment($request, $garment);
    //     return $this->sendResponse($display['message'], $display['display']);
    // }

    public function update()
    {
        // should only be the status
    }
}
