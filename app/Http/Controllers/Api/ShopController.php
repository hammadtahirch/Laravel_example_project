<?php

namespace App\Http\Controllers\Api;

use App\Services\AppServices\ShopService;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;

class ShopController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Shop Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling Shop Activity
    |
    */

    /**
     * Display a listing of the resource.
     *
     * @return Collection $collection
     */
    public function index(ShopService $shopService, Request $request)
    {
        return $shopService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(ShopService $shopService, Request $request)
    {
        dd($shopService->store($request));
        return $shopService->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShopService $shopService, Request $request, $id)
    {
        return $shopService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShopService $shopService, $id)
    {
        return $shopService->destroy($id);
    }
}
