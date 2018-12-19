<?php

namespace App\Http\Controllers\Api;

use App\Services\ControllerServices\RoleService;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return array[]
     */
    public function index(RoleService $roleService)
    {
        return $roleService->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array[]
     */
    public function store(RoleService $roleService, Request $request)
    {
        return $roleService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show(RoleService $roleService, $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return array[]
     */
    public function update(RoleService $roleService, Request $request, $id)
    {
        return $roleService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(RoleService $roleService, $id)
    {
        //
    }
}
