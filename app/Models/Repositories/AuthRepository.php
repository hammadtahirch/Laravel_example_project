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
     * @return Collection $collection
     */
    public function authChecker($permission_name)
    {
        $this->_collection->put("data",Auth::user()->can($permission_name));
        return $this->_collection;
    }

    /**
     * This function is responsible for return error message
     *
     * @return Collection $_collection
     */
    public function authAbort()
    {
        $this->_collection->put("forbidden","Oops! You do not have permission");
        return $this->_collection;
    }
}