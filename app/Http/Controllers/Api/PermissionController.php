<?php

namespace App\Http\Controllers\Api;

use App\Services\ControllerServices\AuthService;
use App\Services\ControllerServices\PermissionService;
use App\Services\ControllerServices\RoleService;
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
    public function __construct(Response $response)
    {
        $this->_permissionService = new PermissionService($response);
        $this->_authService = new AuthService($response);

        $this->middleware(function ($request, $next) {
            if (!$this->_authService->AuthChecker('PERMISSION_construct')) {
                return $this->_authService->AuthAbort();
            }
            return $next($request);
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return array[]
     */
    public function index(Request $request)
    {
        return $this->_permissionService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array[]
     */
    public function store(Request $request)
    {
        return $this->_permissionService->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return array[]
     */
    public function update(Request $request, $id)
    {
        return $this->_permissionService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return array[]
     */
    public function destroy($id)
    {
        return $this->_permissionService->destroy($id);
    }
}
