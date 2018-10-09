<?php

namespace App\Http\Controllers\Api;

use App\Services\ControllerServices\RoleService;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
{

    protected $_roleService = null;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->_roleService = new RoleService($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @return array[]
     */
    public function index()
    {
        return $this->_roleService->index();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array[]
     */
    public function store(Request $request)
    {
        return $this->_roleService->store($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
    public function update(Request $request, $id)
    {
        return $this->_roleService->update($request,$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
