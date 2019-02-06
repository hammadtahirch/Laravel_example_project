<?php

namespace App\Services\AppServices;

use App\Jobs\GenerateResizedImageJob;
use App\Models\Repositories\ShopRepository;
use App\Models\Repositories\UploadRepository;
use App\Services\Transformers\CustomJsonSerializer;
use App\Services\Transformers\ShopTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use Validator;
use App\Services\Constants\StatusCodes;

class ShopService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Shop Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling shop Activity
    |
    */

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @var Manager
     */
    protected $_fractal;

    /**
     * @var ShopRepository
     */
    protected $_shopRepository;

    /**
     * @var UploadService
     */
    protected $_uploadService;

    /**
     * @var UploadRepository
     */
    protected $_uploadRepository;

    /**
     * Create a new Service instance.
     *
     * @param Response $response
     * @param ShopRepository $shopRepository
     * @param UploadService $uploadService
     * @param UploadRepository $uploadRepository
     * @return void
     */
    public function __construct(Response $response, ShopRepository $shopRepository, UploadService $uploadService, UploadRepository $uploadRepository)
    {
        $this->_response = $response;
        $this->_shopRepository = $shopRepository;
        $this->_uploadService = $uploadService;
        $this->_uploadRepository = $uploadRepository;
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
     * @return  mixed
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $isValidate = $this->_shopCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $collectionResponse = $this->_shopRepository->store($request);
        if ($collectionResponse->has("data")) {
            $collectionResponse = $collectionResponse->pull("data");

            //upload image
            $request->request->add(["shop_id" => $collectionResponse->id, "dataUrl" => $request->get("shop")["dataUrl"]]);
            $imagePayload = $this->_uploadService->storeImage($request);

            $request->request->add(["upload" => [
                'name' => $imagePayload["name"],
                'relative_path' => $imagePayload["relative_path"],
                'absolute_path' => $imagePayload["relative_path"],
                'collection_id' => $collectionResponse->id]
            ]);
            $this->_uploadRepository->store($request);
            dispatch(new GenerateResizedImageJob($imagePayload, config("custom_config.shop_sizes")));
            //upload image

            $resource = new Item($collectionResponse, new ShopTransformer(), 'shop');
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
     * @return mixed
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
            'shop.title' => 'required |unique:shops,title,' . $request["shop"]["id"],
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
            'shop.title.required' => "Whoops! the { title } is required.",
            'shop.user_id.required' => "Whoops! the { user } is required.",
            'shop.address.required' => "Whoops! the { address } is required.",
            'shop.city.required' => "Whoops! the { city } is required.",
            'shop.province.required' => "Whoops! the { province } is required.",
            'shop.country.required' => "Whoops! the { country } is required.",
            'shop.portal_code.required' => "Whoops! the { portal code } is required.",
            'shop.latitude.required' => "Whoops! the { latitude } is required.",
            'shop.longitude.required' => "Whoops! the {longitude } is required.",

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
            'shop.title' => 'required |unique:shops,title',
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
            'shop.title.required' => "Whoops! the { title } is required.",
            'shop.user_id.required' => "Whoops! the { user } is required.",
            'shop.address.required' => "Whoops! the { address } is required.",
            'shop.city.required' => "Whoops! the { city } is required.",
            'shop.province.required' => "Whoops! the { province } is required.",
            'shop.country.required' => "Whoops! the { country } is required.",
            'shop.portal_code.required' => "Whoops! the { portal code } is required.",
            'shop.latitude.required' => "Whoops! the { latitude } is required.",
            'shop.longitude.required' => "Whoops! the {longitude } is required.",

        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }
}