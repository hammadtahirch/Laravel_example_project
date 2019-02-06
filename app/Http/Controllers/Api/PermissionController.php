<?php

namespace App\Http\Controllers\Api;

use App\Services\AppServices\AuthService;
use App\Services\AppServices\PermissionService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{

    protected $_permissionService;
    protected $_authService;

    /**
     * Create a new controller instance.
     *
     * @param AuthService $authService
     * @return void
     */
    public function __construct(AuthService $authService)
    {
        $this->middleware(function ($request, $next) use ($authService) {
            if (!$authService->authChecker('PERMISSION_construct')) {
                return $authService->authAbort();
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request ,
     * @param PermissionService $permissionService
     * @return array[]
     */
    public function index(Request $request, PermissionService $permissionService)
    {
        return $permissionService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  PermissionService $permissionService
     * @return array[]
     */
    public function store(Request $request, PermissionService $permissionService)
    {
        return $permissionService->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @param  PermissionService $permissionService
     * @return array[]
     */
    public function update(Request $request, $id, PermissionService $permissionService)
    {
        return $permissionService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param  PermissionService $permissionService
     * @return array[]
     */
    public function destroy($id, PermissionService $permissionService)
    {
        return $permissionService->destroy($id);
    }
}
