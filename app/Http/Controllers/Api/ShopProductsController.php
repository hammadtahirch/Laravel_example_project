<?php

namespace App\Http\Controllers\Api;

use App\Services\ControllerServices\ShopProductsService;
use App\Services\ControllerServices\ShopService;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopProductsController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Shop Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling Shop  Products Activity
    |
    */

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ShopProductsService $shopProductsService
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($shop_id, Request $request, ShopProductsService $shopProductsService)
    {
        return $shopProductsService->index($shop_id, $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  ShopProductsService $shopProductsService
     * @return \Illuminate\Http\Response
     */
    public function store($shop_id, Request $request, ShopProductsService $shopProductsService)
    {
        return $shopProductsService->store($shop_id, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @param  ShopProductsService $shopProductsService
     * @return \Illuminate\Http\Response
     */
    public function show($shop_id, $id, ShopProductsService $shopProductsService)
    {
        return new \Illuminate\Http\Response;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param  ShopProductsService $shopProductsService
     * @return \Illuminate\Http\Response
     */
    public function update($shop_id, Request $request, $id, ShopProductsService $shopProductsService)
    {
        return $shopProductsService->update($shop_id, $request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param  ShopProductsService $shopProductsService
     * @return \Illuminate\Http\Response
     */
    public function destroy($shop_id, $id, ShopProductsService $shopProductsService)
    {
        return $shopProductsService->destroy($shop_id, $id);
    }
}
