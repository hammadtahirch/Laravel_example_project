<?php

namespace App\Services\AppServices;

use App\Models\Repositories\ShopRepository;
use App\Services\Transformers\CustomJsonSerializer;
use App\Services\Transformers\ShopTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use Validator;
use App\Services\Constants\StatusCodes;

class ShopService
{
    /*
    |--------------------------------------------------------------------------
    | Shop Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling shop Activity
    |
    */

    protected $_response;
    protected $_fractal;
    protected $_shopRepository;

    /**
     * Create a new Service instance.
     *
     * @param Response $response
     * @param ShopRepository $shopRepository
     * @return void
     */
    public function __construct(Response $response, ShopRepository $shopRepository)
    {
        $this->_response = $response;
        $this->_shopRepository = $shopRepository;
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

        $collectionResponse = $this->_shopRepository->index($request);
        if ($collectionResponse->has("data")) {
            $shopObject = $collectionResponse->pull("data");
            $shopCollection = $shopObject->getCollection();
            $resource = new Collection($shopCollection, new ShopTransformer(), 'shops');
            $resource->setPaginator(new IlluminatePaginatorAdapter($shopObject));
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  array []
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $isValidate = $this->_shopCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }

        $collectionResponse = $this->_shopRepository->store($request);
        if ($collectionResponse->hsa("data")) {
            $resource = new Item($collectionResponse->pull("data"), new ShopTransformer(), 'shop');
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return array []
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        $isValidate = $this->_shopUpdateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }

        $collectionResponse = $this->_shopRepository->update($request, $id);
        if ($collectionResponse->has("data")) {
            $resource = new Item($collectionResponse->pull("data"), new ShopTransformer(), 'shop');
            return $this->_fractal->createData($resource)->toArray();

        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $collectionResponse = $this->_shopRepository->destroy($id);
        if ($collectionResponse->has("data")) {
            return $this->_response->withItem($collectionResponse->pull("data"), new ShopTransformer(), 'user');
        } elseif ($collectionResponse->has("not_found")) {
            return $this->_response->errorNotFound($collectionResponse->pull("not_found"));
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * This function responsible for validating shop on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _shopUpdateValidator(array $request)
    {
        $rules = [
            'shop.title' => 'required',
            'shop.user_id' => 'required',
            'shop.address' => 'required',
            'shop.city' => 'required',
            'shop.province' => 'required',
            'shop.country' => 'required',
            'shop.portal_code' => 'required',
            'shop.latitude' => 'required',
            'shop.longitude' => 'required',


        ];
        $messages = [
            'shop.title.required' => "Oops! title is required.",
            'shop.user_id.required' => "Oops! user is required.",
            'shop.address.required' => "Oops! address is required.",
            'shop.city.required' => "Oops! city is required.",
            'shop.province.required' => "Oops! province is required.",
            'shop.country.required' => "Oops! country is required.",
            'shop.portal_code.required' => "Oops! portal_code is required.",
            'shop.latitude.required' => "Oops! latitude is required.",
            'shop.longitude.required' => "Oops! longitude is required.",

        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }

    /**
     * This function responsible for validating shop on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _shopCreateValidator(array $request)
    {
        $rules = [
            'shop.title' => 'required',
            'shop.user_id' => 'required',
            'shop.address' => 'required',
            'shop.city' => 'required',
            'shop.province' => 'required',
            'shop.country' => 'required',
            'shop.portal_code' => 'required',
            'shop.latitude' => 'required',
            'shop.longitude' => 'required',


        ];
        $messages = [
            'shop.title.required' => "Oops! title is required.",
            'shop.user_id.required' => "Oops! user is required.",
            'shop.address.required' => "Oops! address is required.",
            'shop.city.required' => "Oops! city is required.",
            'shop.province.required' => "Oops! province is required.",
            'shop.country.required' => "Oops! country is required.",
            'shop.portal_code.required' => "Oops! portal_code is required.",
            'shop.latitude.required' => "Oops! latitude is required.",
            'shop.longitude.required' => "Oops! longitude is required.",

        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }
}