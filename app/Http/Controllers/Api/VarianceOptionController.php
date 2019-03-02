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
     * @param Request $request
     * @param VarianceOptionService $varianceOptionService
     * @return array []
     */
    public function index(Request $request, VarianceOptionService $varianceOptionService)
    {
        return $varianceOptionService->index($request);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param VarianceOptionService $varianceOptionService
     * @return array []
     */
    public function store(Request $request, VarianceOptionService $varianceOptionService)
    {
        return $varianceOptionService->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $variance_id
     * @param  integer $id
     * @param VarianceOptionService $varianceOptionService
     * @return  array []
     */
    public function update(Request $request, $variance_id, $id, VarianceOptionService $varianceOptionService)
    {
        return $varianceOptionService->update($request, $variance_id, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @param  integer $variance_id
     * @param VarianceOptionService $varianceOptionService
     * @return \Illuminate\Http\Response
     */
    public function destroy($variance_id, $id, VarianceOptionService $varianceOptionService)
    {
        return $varianceOptionService->destroy($variance_id, $id);
    }
}
