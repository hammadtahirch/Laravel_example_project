<?php

namespace App\Models\Repositories;

use App\Models\Eloquent\Role;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Validator;

class RoleRepository
{
    /*
    |--------------------------------------------------------------------------
    | Role Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Roles
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
                ->where('id', '<>', 1)->get();
            $this->_collection->put("data", $roleObject);
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
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
            if ($roleObject->id > 0) {
                $roleObject = $roleObject->where(["id" => $requestObject->id])->first();
                $this->_collection->put("data", $roleObject);
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        }

        return $this->_collection;
    }
}