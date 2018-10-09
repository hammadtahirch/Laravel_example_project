<?php

namespace App\Http\Controllers\API;

use App\Services\ConstantServices\GeneralConstants;
use App\Services\ConstantServices\StatusCodes;
use App\Services\ControllerServices\AuthService;
use EllipseSynergie\ApiResponse\Contracts\Response;
use App\Services\ControllerServices\RoleService;
use App\Services\ControllerServices\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Validator;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | User Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling user Activity
    |
    */

    protected $_userService = null;
    protected $_roleService = null;
    protected $_authService = null;


    /**
     * Create a new controller instance.
     *
     * @param Response $response
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->_userService = new UserService($response);
        $this->_roleService = new RoleService($response);
        $this->_authService = new AuthService($response);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(Request $request)
    {
        return $this->_userService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->_userService->store($request);
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->_userService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->_userService->destroy($id);
    }

    /**
     * Validate a new user instance.
     *
     * @param Request $request
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function login(Request $request)
    {
        return $this->_userService->login($request);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function register(Request $request)
    {
        return $this->_userService->register($request);
    }

    /**
     * this is responsible to sign out from application
     *
     * @param Request $request
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function signOut(Request $request)
    {
        return $this->_userService->signOut($request);
    }
}
