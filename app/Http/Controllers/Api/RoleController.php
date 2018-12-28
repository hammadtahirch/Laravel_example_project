<?php

namespace App\Http\Controllers\Api;

use App\Services\AppServices\RoleService;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param RoleService $roleService
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
     * @param  RoleService $roleService
     * @return array[]
     */
    public function store(RoleService $roleService, Request $request)
    {
        return $roleService->store($request);
    }
    
}
