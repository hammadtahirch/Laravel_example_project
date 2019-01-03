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

    protected $_response ;
    protected $_fractal;
    protected $_roleRepository;

    /**
     * Create a new Service instance.
     *
     * @param RoleRepository $roleRepository
     * @return void
     */
    public function __construct(Response $response, RoleRepository $roleRepository)
    {
        $this->_response = $response;
        $this->_roleRepository = $roleRepository;
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
        $collectionResponse = $this->_roleRepository->index();
        if ($collectionResponse->has("data")) {
            $resource = new Collection($collectionResponse->pull("data"), new RoleTransformer(), "roles");
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

        $collectionResponse = $this->_roleRepository->store($request);
        if ($collectionResponse->has("data")) {
            $resource = new Item($collectionResponse->pull("data"), new RoleTransformer(), "roles");
            return $this->_fractal
                ->createData($resource)
                ->toArray();
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