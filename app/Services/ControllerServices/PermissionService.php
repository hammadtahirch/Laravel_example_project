<?php

namespace App\Services\ControllerServices;

use App\Models\Permission;
use App\Models\Role;
use App\Services\ConstantServices\GeneralConstants;
use App\Services\ConstantServices\StatusCodes;
use App\Services\TransformerServices\CustomJsonSerializer;
use App\Services\TransformerServices\PermissionTransformer;
use App\Services\TransformerServices\RoleTransformer;
use Illuminate\Database\QueryException;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Validator;
use Zend\Diactoros\Response\ArraySerializer;

class PermissionService
{
    /*
    |--------------------------------------------------------------------------
    | Permission Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Permission
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
    public function index($request)
    {
        try {
            $permissionPagination = Permission::query()
                ->with(
                    [
                        'roles' => function ($query) {
                            $query->where('name', '<>', GeneralConstants::SUPPER_ADMIN);
                        }
                    ]
                )->paginate(10);
            $permissionObject = $permissionPagination->getCollection();

            $resource = new Collection($permissionObject, new PermissionTransformer(), 'permissions');
            $resource->setPaginator(new IlluminatePaginatorAdapter($permissionPagination));
            return $this->_fractal->createData($resource)->toArray();
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        }

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

            $permissionObject = new Permission();
            $permissionObject->name = $requestObject['permission']['name'];
            $permissionObject->display_name = $requestObject['permission']['display_name'];
            $permissionObject->description = $requestObject['permission']['description'];

            $permissionObject->save();
            if ($permissionObject->id > 0) {
                $supper_admin = Role::where(["id" => 1])->first();
                $supper_admin->attachPermission($permissionObject);
                dd($permissionObject);
                $permissionObject = $permissionObject->where(["id" => $requestObject->id])->first();
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

        $resource = new Item($permissionObject, new PermissionTransformer(), "permission");
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
            $permissionObject = Permission::find($id);
            $permissionObject->name = $requestObject['permission']['name'];
            $permissionObject->display_name = $requestObject['permission']['display_name'];
            $permissionObject->description = $requestObject['permission']['description'];

            if ($permissionObject->update($permissionObject->toArray())) {
                $permissionObject->detachRoles($id);
                foreach ($requestObject['permission']['roles'] as $index => $role) {
                    $roleObject = Role::where(["id" => $role])->first();
                    $roleObject->perms()->sync(array($id));
                }
                $permissionObject = $permissionObject->where(["id" => $id])->with(
                    [
                        'roles' => function ($query) {
                            $query->where('name', '<>', GeneralConstants::SUPPER_ADMIN);
                        }
                    ]
                )->first();
                $resource = new Item($permissionObject, new PermissionTransformer(), "permission");
                return $this->_fractal
                    ->createData($resource)
                    ->toArray();
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

            $permissionObject = Permission::find($id);
            if (!$permissionObject) {
                return $this->_response->errorNotFound(['message' => 'Permission not found.']);
            }
            if ($permissionObject->delete()) {

                $resource = new Item($permissionObject->first(), new PermissionTransformer(), 'permission');
                return $this->_fractal->createData($resource)->toArray();
            } else {
                return $this->_response->errorInternalError(['message' => 'Internal server error user not deleted']);
            }

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
            'permission.name' => 'required',
            'permission.display_name' => 'required',
            'permission.description' => 'required',
        ];
        $messages = [
            'permission.name.required' => "Oops! name is required.",
            'permission.display_name.required' => "Oops! display_name is required.",
            'permission.description.required' => "Oops! description is required.",
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
            'permission.name' => 'required',
            'permission.display_name' => 'required',
            'permission.description' => 'required',
        ];
        $messages = [
            'permission.name.required' => "Oops! name is required.",
            'permission.display_name.required' => "Oops! display_name is required.",
            'permission.description.required' => "Oops! description is required.",
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }
}