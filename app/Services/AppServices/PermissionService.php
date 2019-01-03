<?php

namespace App\Services\AppServices;

use App\Services\Constants\StatusCodes;
use App\Models\Repositories\PermissionRepository;
use App\Services\Transformers\CustomJsonSerializer;
use App\Services\Transformers\PermissionTransformer;
use EllipseSynergie\ApiResponse\Laravel\Response;
use League\Fractal\Manager;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Validator;

class PermissionService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Permission Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Permission
    |
    */

    protected $_response;
    protected $_fractal;
    protected $_permissionRepository;

    /**
     * Create a new Service instance.
     *
     * @param Response $response
     * @param $permissionRepository
     * @return void
     */
    public function __construct(Response $response, PermissionRepository $permissionRepository)
    {
        $this->_response = $response;
        $this->_permissionRepository = $permissionRepository;
        $this->_fractal = new Manager();
        $this->_fractal->setSerializer(new CustomJsonSerializer());

    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return array []
     */
    public function index($request)
    {
        $collectionResponse = $this->_permissionRepository->index($request);
        if ($collectionResponse->has("data")) {
            $permissionObject = $collectionResponse->pull("data");
            $permissionCollection = $permissionObject->getCollection();
            $resource = new Collection($permissionCollection, new PermissionTransformer(), 'permissions');
            $resource->setPaginator(new IlluminatePaginatorAdapter($permissionObject));
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array []
     */
    public function store($request)
    {

        $requestObject = $request->all();
        $isValidate = $this->_roleCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }

        $collectionResponse = $this->_permissionRepository->store($request);
        if ($collectionResponse->has("data")) {
            $resource = new Item($collectionResponse->pull("data"), new PermissionTransformer(), "permission");
            return $this->_fractal
                ->createData($resource)
                ->toArray();
        } else {
            $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
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

        $collectionResponse = $this->_permissionRepository->update($request, $id);
        if ($collectionResponse->has("data")) {
            $resource = new Item($collectionResponse->pull("data"), new PermissionTransformer(), "permission");
            return $this->_fractal
                ->createData($resource)
                ->toArray();
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
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

        $collectionResponse = $this->_permissionRepository->destroy($id);
        if ($collectionResponse->has("data")) {
            $resource = new Item($collectionResponse->pull("data"), new PermissionTransformer(), 'permission');
            return $this->_fractal->createData($resource)->toArray();
        } elseif ($collectionResponse->pull("not_found")) {
            return $this->_response->errorNotFound($collectionResponse->pull("not_found"));
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
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