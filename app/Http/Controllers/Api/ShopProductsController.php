<?php

namespace App\Http\Controllers\Api;

use App\Services\AppServices\ShopProductsService;
use App\Services\AppServices\ShopService;
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
     * @param $shop_id
     * @param Request $request
     * @param ShopProductsService $shopProductsService
     * @return array []
     */
    public function index($shop_id, Request $request, ShopProductsService $shopProductsService)
    {
        return $shopProductsService->index($shop_id, $request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $shop_id
     * @param  Request $request
     * @param  ShopProductsService $shopProductsService
     * @return array []
     */
    public function store($shop_id, Request $request, ShopProductsService $shopProductsService)
    {
        return $shopProductsService->store($shop_id, $request);
    }

    /**
     * Display the specified resource.
     *
     * @param  $shop_id
     * @param  $id
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
     * @param  $shop_id
     * @param  Request $request
     * @param  $id
     * @param  ShopProductsService $shopProductsService
     * @return array []
     */
    public function update($shop_id, Request $request, $id, ShopProductsService $shopProductsService)
    {
        return $shopProductsService->update($shop_id, $request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $shop_id
     * @param  int $id
     * @param  ShopProductsService $shopProductsService
     * @return array []
     */
    public function destroy($shop_id, $id, ShopProductsService $shopProductsService)
    {
        return $shopProductsService->destroy($shop_id, $id);
    }
}
