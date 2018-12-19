<?php

namespace App\Http\Controllers\Api;

use App\Services\ControllerServices\AuthService;
use App\Services\ControllerServices\PermissionService;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{

    protected $_permissionService = null;
    protected $_authService = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Response $response, AuthService $authService)
    {
        $this->middleware(function ($request, $next) use ($authService) {
            if ($authService->AuthChecker('PERMISSION_construct')) {
                return $authService->AuthAbort();
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
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
     * @return array[]
     */
    public function destroy($id, PermissionService $permissionService)
    {
        return $permissionService->destroy($id);
    }
}
