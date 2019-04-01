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
     * @var AuthRepository
     */
    protected $_authRepository;

    /**
     * Create a new Service instance.
     *
     * @param AuthRepository $authRepository
     * @return void
     */
    public function __construct(AuthRepository $authRepository)
    {
        parent::__construct();
        $this->_authRepository = $authRepository;
    }

    /**
     * This function is responsible for current user permission
     *
     * @param $permission_name
     * @return Collection $collection
     */
    public function authChecker($permission_name)
    {
        try {
            return Auth::user()->can($permission_name);
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }

    /**
     * This function is responsible for return error message
     *
     * @return Collection $collection
     */
    public function authAbort()
    {
        try {
            return $this->_response->errorForbidden("Uh-oh! You do not have permission");
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }
}