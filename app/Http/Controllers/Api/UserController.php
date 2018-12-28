<?php

namespace App\Http\Controllers\API;

use EllipseSynergie\ApiResponse\Contracts\Response;
use App\Services\AppServices\UserService;
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

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index(UserService $userService, Request $request)
    {
        return $userService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserService $userService, Request $request)
    {
        return $userService->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserService $userService, Request $request, $id)
    {
        return $userService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserService $userService, $id)
    {
        return $userService->destroy($id);
    }

    /**
     * Validate a new user instance.
     *
     * @param Request $request
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function login(UserService $userService, Request $request)
    {
        return $userService->login($request);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param Request $request
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function register(UserService $userService, Request $request)
    {
        return $userService->register($request);
    }

    /**
     * this is responsible to sign out from application
     *
     * @param Request $request
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function signOut(UserService $userService, Request $request)
    {
        return $userService->signOut($request);
    }
}
