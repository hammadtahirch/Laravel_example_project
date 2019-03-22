<?php

namespace App\Http\Controllers\Api;

use App\Models\Eloquent\Collection;
use App\Models\Repositories\ProductVarianceRepository;
use App\Services\AppServices\CollectionService;
use App\Services\AppServices\ProductVarianceService;
use App\Services\AppServices\VarianceOptionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VarianceOptionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | VarianceOption Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling VarianceOption Activity
    |
    */

    /**
     * Display a listing of the resource.
     *
     * @param $variance_id
     * @param Request $request
     * @param VarianceOptionService $varianceOptionService
     * @return array[]
     */
    public function index($variance_id, Request $request, VarianceOptionService $varianceOptionService)
    {
        return $varianceOptionService->index($variance_id, $request);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param $variance_id
     * @param Request $request
     * @param VarianceOptionService $varianceOptionService
     * @return mixed
     */
    public function store($variance_id, Request $request, VarianceOptionService $varianceOptionService)
    {
        return $varianceOptionService->store($variance_id, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $variance_id
     * @param $id
     * @param Request $request
     * @param VarianceOptionService $varianceOptionService
     * @return mixed
     */
    public function update($variance_id, $id, Request $request, VarianceOptionService $varianceOptionService)
    {
        return $varianceOptionService->update($variance_id, $id, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param $variance_id
     * @param $id
     * @param VarianceOptionService $varianceOptionService
     * @return \Illuminate\Http\Response
     */
    public function destroy( $variance_id, $id, VarianceOptionService $varianceOptionService)
    {
        return $varianceOptionService->destroy( $variance_id, $id);
    }
}
