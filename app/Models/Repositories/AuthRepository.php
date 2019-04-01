<?php

namespace App\Models\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Validator;

class AuthRepository
{
    /*
    |--------------------------------------------------------------------------
    | Auth Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling Auth Operations
    |
    */

    /**
     * @var Collection
     */
    protected $_collection;

    /**
     * Create a new Service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_collection = new Collection();
    }

    /**
     * This function is responsible for current user permission
     *
     * @param $permission_name
     * @return Collection
     * @throws \Exception
     */
    public function authChecker($permission_name)
    {
        try {
            $this->_collection->put("data", Auth::user()->can($permission_name));
            return $this->_collection;
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }

    }

    /**
     * This function is responsible for return error message
     *
     * @return Collection $_collection
     * @throws \Exception
     */
    public function authAbort()
    {
        try {
            $this->_collection->put("forbidden", "Uh-oh! You do not have permission");
            return $this->_collection;
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }

    }
}