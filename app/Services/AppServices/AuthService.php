<?php

namespace App\Services\AppServices;

use App\Models\Repositories\AuthRepository;
use App\Services\Transformers\CustomJsonSerializer;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use Validator;

class AuthService
{
    /*
    |--------------------------------------------------------------------------
    | Auth Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Auth Operations
    |
    */

    protected $_response;
    protected $_fractal;
    protected $_authRepository;

    /**
     * Create a new Service instance.
     *
     * @param Response $response
     * @param AuthRepository $authRepository
     * @return void
     */
    public function __construct(Response $response, AuthRepository $authRepository)
    {
        $this->_response = $response;
        $this->_authRepository = $authRepository;
        $this->_fractal = new Manager();
        $this->_fractal->setSerializer(new CustomJsonSerializer());

    }

    /**
     * This function is responsible for current user permission
     *
     * @return Collection $collection
     */
    public function authChecker($permission_name)
    {

//        $collectionResponse = $this->_authRepository->authChecker($permission_name);
//        if ($collectionResponse->has("data")) {
//            return $collectionResponse->pull("data");
//        }
//        dd(Auth::user()->can($permission_name));
        return Auth::user()->can($permission_name);
    }

    /**
     * This function is responsible for return error message
     *
     * @return Collection $collection
     */
    public function authAbort()
    {
//        $collectionResponse = $this->_authRepository->authAbort();
//        if ($collectionResponse->has("forbidden")) {
//            return $collectionResponse->pull("forbidden");
//        }
        return $this->_response->errorForbidden("Oops! You do not have permission");
    }
}