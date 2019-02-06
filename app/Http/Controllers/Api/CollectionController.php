<?php

namespace App\Http\Controllers\Api;

use App\Models\Eloquent\Collection;
use App\Services\AppServices\CollectionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CollectionController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Collection Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling Collection Activity
    |
    */

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param CollectionService $collectionService
     * @return array []
     */
    public function index(Request $request, CollectionService $collectionService)
    {
        return $collectionService->index($request);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param CollectionService $collectionService
     * @return array []
     */
    public function store(Request $request, CollectionService $collectionService)
    {
        return $collectionService->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  integer $id
     * @param CollectionService $collectionService
     * @return  array []
     */
    public function update(Request $request, $id, CollectionService $collectionService)
    {
        return $collectionService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  integer $id
     * @param CollectionService $collectionService
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, CollectionService $collectionService)
    {
        return $collectionService->destroy($id);
    }
}
