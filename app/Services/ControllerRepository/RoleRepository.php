<?php

namespace App\Services\ControllerRepository;

use App\Models\Eloquent\Role;
use App\Services\ConstantServices\StatusCodes;
use App\Services\TransformerServices\CustomJsonSerializer;
use App\Services\TransformerServices\RoleTransformer;
use Illuminate\Database\QueryException;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Validator;
use Zend\Diactoros\Response\ArraySerializer;

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
     * Display a listing of the resource.
     *
     * @return array []
     */
    public function index()
    {
        try {
            $roleObject = Role::query()
                ->where('id', '<>', 1)->get();
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        }
        $resource = new Collection($roleObject, new RoleTransformer(), "roles");
        return $this->_fractal
            ->createData($resource)
            ->toArray();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array[]
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $isValidate = $this->_roleCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        try {
            $roleObject = new Role();
            $roleObject->name = $requestObject['role']['name'];
            $roleObject->display_name = $requestObject['role']['display_name'];
            $roleObject->description = $requestObject['role']['description'];
            $roleObject->save($requestObject);
            if ($roleObject->id > 0) {
                dd($roleObject);
                $roleObject = $roleObject->where(["id" => $requestObject->id])->first();
            }
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        }

        $resource = new Item($roleObject, new RoleTransformer(), "roles");
        return $this->_fractal
            ->createData($resource)
            ->toArray();
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return array[]
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        $isValidate = $this->_roleUpdateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        try {
            $roleObject = "";
        } catch (QueryException $exception) {

        } catch (\Exception $exception) {

        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return array[]
     */
    public function destroy($id)
    {
        try {
            $resource = new Item('', new RoleTransformer(), 'role');
            return $this->_fractal->createData($resource)->toArray();
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! Query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
    }

    /**
     * This function responsible for validating role on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _roleUpdateValidator(array $request)
    {
        $rules = [
            'role.name' => 'required',
            'role.display_name' => 'required',
            'role.description' => 'required',
        ];
        $messages = [
            'shop.name.required' => "Oops! name is required.",
            'shop.display_name.required' => "Oops! display_name is required.",
            'shop.description.required' => "Oops! description is required.",
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }

    /**
     * This function responsible for validating role on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _roleCreateValidator(array $request)
    {
        $rules = [
            'role.name' => 'required',
            'role.display_name' => 'required',
            'role.description' => 'required',
        ];
        $messages = [
            'shop.name.required' => "Oops! name is required.",
            'shop.display_name.required' => "Oops! display_name is required.",
            'shop.description.required' => "Oops! description is required.",
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }
}