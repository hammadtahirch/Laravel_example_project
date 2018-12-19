<?php

namespace App\Services\ControllerRepository;

use App\Services\TransformerServices\CustomJsonSerializer;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use Validator;

class AuthRepository
{
    /*
    |--------------------------------------------------------------------------
    | Auth Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Auth Operations
    |
    */

    protected $_response = null;
    protected $_fractal = null;

    /**
     * Create a new Service instance.
     *
     * @return void
     */
    public function __construct($response)
    {
        $this->_response = $response;
        $this->_fractal = new Manager();
        $this->_fractal->setSerializer(new CustomJsonSerializer());

    }

    /**
     * This function is responsible for current user permission
     *
     * @return array []
     */
    public function AuthChecker($permission_name)
    {
        return Auth::user()->can($permission_name);
    }

    /**
     * This function is responsible for return error message
     *
     * @return Response $response
     */
    public function AuthAbort()
    {
        return $this->_response->errorForbidden("Oops! You do not have permission");
    }
}