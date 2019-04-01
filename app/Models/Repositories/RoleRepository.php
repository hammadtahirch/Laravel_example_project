<?php

namespace App\Models\Repositories;

use App\Models\Eloquent\Role;
use App\Services\Constants\GeneralConstants;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Validator;

class RoleRepository
{
    /*
    |--------------------------------------------------------------------------
    | Role Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling Roles
    |
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
     * Display a listing of the resource.
     *
     * @return Collection $collection
     */
    public function index()
    {
        try {
            $roleObject = Role::query()
                ->where('id', '<>', GeneralConstants::SUPPER_ADMIN_ID)->get();
            $this->_collection->put("data", $roleObject);
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        }
        return $this->_collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Collection $collection
     */
    public function store($request)
    {
        $requestObject = $request->all();

        try {
            $roleObject = new Role();
            $roleObject->name = $requestObject['role']['name'];
            $roleObject->display_name = $requestObject['role']['display_name'];
            $roleObject->description = $requestObject['role']['description'];
            $roleObject->save($requestObject);
            if (!empty($roleObject->id)) {
                $roleObject = $roleObject->where(["id" => $requestObject->id])->first();
                $this->_collection->put("data", $roleObject);
            }
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }

        return $this->_collection;
    }
}