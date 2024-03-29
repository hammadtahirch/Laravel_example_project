<?php

namespace App\Services\AppServices;

use App\Services\Constants\StatusCodes;
use App\Models\Repositories\RoleRepository;
use App\Services\Transformers\CustomJsonSerializer;
use App\Services\Transformers\RoleTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Validator;

class RoleService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Role Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Roles
    |
    */

    /**
     * @var RoleRepository
     */
    protected $_roleRepository;

    /**
     * Create a new Service instance.
     *
     * @param RoleRepository $roleRepository
     * @return void
     */
    public function __construct(RoleRepository $roleRepository)
    {
        parent::__construct();
        $this->_roleRepository = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return array/mixed
     */
    public function index()
    {
        try {
            $collectionResponse = $this->_roleRepository->index();
            $resource = new Collection($collectionResponse->pull("data"), new RoleTransformer(), "roles");
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $request
     * @return array|Collection|mixed
     */
    public function store($request)
    {
        try {
            $requestObject = $request->all();
            $isValidate = $this->_roleCreateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_roleRepository->store($request);
            $resource = new Item($collectionResponse->pull("data"), new RoleTransformer(), "roles");
            return $this->_fractal
                ->createData($resource)
                ->toArray();
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
    private function _roleCreateValidator(array $request)
    {
        $rules = [
            'role.name' => 'required',
            'role.display_name' => 'required',
            'role.description' => 'required',
        ];
        $messages = [
            'shop.name.required' => "Uh-oh! name is required.",
            'shop.display_name.required' => "Uh-oh! display_name is required.",
            'shop.description.required' => "Uh-oh! description is required.",
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }
}