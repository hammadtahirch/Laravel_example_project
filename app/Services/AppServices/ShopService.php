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
        try {
            $collectionResponse = $this->_shopRepository->index($request);
            $shopObject = $collectionResponse->pull("data");
            $shopCollection = $shopObject->getCollection();
            $resource = new Collection($shopCollection, new ShopTransformer(), 'shops');
            $resource->setPaginator(new IlluminatePaginatorAdapter($shopObject));
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        try {
            $requestObject = $request->all();
            $isValidate = $this->_shopCreateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_shopRepository->store($request);
            $collectionResponse = $collectionResponse->pull("data");
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.shop_sizes")));
            }
            $resource = new Item($collectionResponse, new ShopTransformer(), 'shop');
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        try {
            $requestObject = $request->all();
            $isValidate = $this->_shopUpdateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }

            $collectionResponse = $this->_shopRepository->update($request, $id);
            $collectionResponse = $collectionResponse->pull("data");
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.profile_sizes")));
            }
            $resource = new Item($collectionResponse, new ShopTransformer(), 'shop');
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        try {
            $collectionResponse = $this->_shopRepository->destroy($id);
            return $this->_response->withItem($collectionResponse->pull("data")->with("upload")->where(["id" => $id])->first(), new ShopTransformer(), 'user');
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        return null;
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
        return null;
    }
}