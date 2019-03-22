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
     * @var ShopRepository
     */
    protected $_shopRepository;


    /**
     * Create a new Service instance.
     *
     * @param ShopRepository $shopRepository
     * @param UploadService $uploadService
     * @param UploadRepository $uploadRepository
     * @return void
     */
    public function __construct(ShopRepository $shopRepository)
    {
        parent::__construct();
        $this->_shopRepository = $shopRepository;
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
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.shop_sizes")));
            }
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
            $collectionResponse = $collectionResponse->pull("data");
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.profile_sizes")));
            }
            $resource = new Item($collectionResponse, new ShopTransformer(), 'shop');
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
            return $this->_response->withItem($collectionResponse->pull("data")->with("upload")->where(["id" => $id])->first(), new ShopTransformer(), 'user');
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
            'shop.title.required' => "Uh-oh! the { title } is required.",
            'shop.user_id.required' => "Uh-oh! the { user } is required.",
            'shop.address.required' => "Uh-oh! the { address } is required.",
            'shop.city.required' => "Uh-oh! the { city } is required.",
            'shop.province.required' => "Uh-oh! the { province } is required.",
            'shop.country.required' => "Uh-oh! the { country } is required.",
            'shop.portal_code.required' => "Uh-oh! the { portal code } is required.",
            'shop.latitude.required' => "Uh-oh! the { latitude } is required.",
            'shop.longitude.required' => "Uh-oh! the {longitude } is required.",

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
            'shop.title.required' => "Uh-oh! the { title } is required.",
            'shop.user_id.required' => "Uh-oh! the { user } is required.",
            'shop.address.required' => "Uh-oh! the { address } is required.",
            'shop.city.required' => "Uh-oh! the { city } is required.",
            'shop.province.required' => "Uh-oh! the { province } is required.",
            'shop.country.required' => "Uh-oh! the { country } is required.",
            'shop.portal_code.required' => "Uh-oh! the { portal code } is required.",
            'shop.latitude.required' => "Uh-oh! the { latitude } is required.",
            'shop.longitude.required' => "Uh-oh! the {longitude } is required.",

        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }
}