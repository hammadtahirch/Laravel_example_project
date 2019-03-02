<?php

namespace App\Http\Controllers\Api;

use App\Models\Eloquent\Collection;
use App\Models\Repositories\ProductVarianceRepository;
use App\Services\AppServices\CollectionService;
use App\Services\AppServices\ProductVarianceService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductVarianceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ProductVariance Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling ProductVariance Activity
    |
    */

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param ProductVarianceService $productVarianceService
     * @return array []
     */
    public function index(Request $request, ProductVarianceService $productVarianceService)
    {
        return $productVarianceService->index($request);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param ProductVarianceService $productVarianceService
     * @return array []
     */
    public function store(Request $request, ProductVarianceService $productVarianceService)
    {
        return $productVarianceService->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $product_id
     * @param  integer $id
     * @param ProductVarianceService $productVarianceService
     * @return  array []
     */
    public function update(Request $request, $product_id, $id, ProductVarianceService $productVarianceService)
    {
        return $productVarianceService->update($request, $product_id, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @param  integer $product_id
     * @param ProductVarianceService $productVarianceService
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id, $id, ProductVarianceService $productVarianceService)
    {
        return $productVarianceService->destroy($product_id, $id);
    }
}
