<?php

namespace App\Services\AppServices;

use App\Models\Eloquent\User;
use App\Models\Repositories\AuthRepository;
use App\Services\Transformers\CustomJsonSerializer;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use Validator;

class AuthService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Auth Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Auth Operations
    |
    */

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @var Manager
     */
    protected $_fractal;

    /**
     * @var AuthRepository
     */
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
        return Auth::user()->can($permission_name);
    }

    /**
     * This function is responsible for return error message
     *
     * @return Collection $collection
     */
    public function authAbort()
    {
        return $this->_response->errorForbidden("Oops! You do not have permission");
    }
}