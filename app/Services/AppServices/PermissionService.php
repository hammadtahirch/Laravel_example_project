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

    /**
     * @var PermissionRepository
     */
    protected $_permissionRepository;

    /**
     * Create a new Service instance.
     *
     * @param $permissionRepository
     * @return void
     */
    public function __construct(PermissionRepository $permissionRepository)
    {
        parent::__construct();
        $this->_permissionRepository = $permissionRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return array []
     */
    public function index($request)
    {
        try {
            $collectionResponse = $this->_permissionRepository->index($request);
            $permissionObject = $collectionResponse->pull("data");
            $permissionCollection = $permissionObject->getCollection();
            $resource = new Collection($permissionCollection, new PermissionTransformer(), 'permissions');
            $resource->setPaginator(new IlluminatePaginatorAdapter($permissionObject));
            return $this->_fractal->createData($resource)->toArray();

        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $request
     * @return array|Collection
     */
    public function store($request)
    {
        try {
            $requestObject = $request->all();
            $isValidate = $this->_roleCreateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_permissionRepository->store($request);
            $resource = new Item($collectionResponse->pull("data"), new PermissionTransformer(), "permission");
            return $this->_fractal
                ->createData($resource)
                ->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }


    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return array|Collection
     */
    public function update($request, $id)
    {
        try {
            $requestObject = $request->all();
            $isValidate = $this->_roleUpdateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_permissionRepository->update($request, $id);
            $resource = new Item($collectionResponse->pull("data"), new PermissionTransformer(), "permission");
            return $this->_fractal
                ->createData($resource)
                ->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
            $collectionResponse = $this->_permissionRepository->destroy($id);
            $resource = new Item($collectionResponse->pull("data"), new PermissionTransformer(), 'permission');
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
            'permission.name' => 'required|unique:permissions,name' . $request["permission"]["id"],
            'permission.display_name' => 'required',
            'permission.description' => 'required',
        ];
        $messages = [
            'permission.name.required' => "Uh-oh! the { name } is required.",
            'permission.name.unique' => "Uh-oh! the { name } has already been taken.",
            'permission.display_name.required' => "Uh-oh! the { display name } is required.",
            'permission.description.required' => "Uh-oh! the { description } is required.",
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
            'permission.name' => 'required|unique:permissions,name',
            'permission.display_name' => 'required',
            'permission.description' => 'required',
        ];
        $messages = [
            'permission.name.required' => "Uh-oh! the { name } is required.",
            'permission.name.unique' => "Uh-oh! the { name } has already been taken.",
            'permission.display_name.required' => "Uh-oh! the { display name } is required.",
            'permission.description.required' => "Uh-oh! the { description } is required.",
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }
}