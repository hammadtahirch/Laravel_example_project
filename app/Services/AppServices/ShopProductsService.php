<?php

namespace App\Services\AppServices;

use App\Models\Repositories\ShopProductsRepository;
use App\Services\Transformers\CustomJsonSerializer;
use App\Services\Transformers\ProductTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Support\Facades\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use Validator;

class ShopProductsService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Shop Products Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling shop Products Activity
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
     * @var ShopProductsRepository
     */
    protected $_shopProductRepository;

    /**
     * Create a new Service instance.
     *
     * @param Response $response
     * @param ShopProductsRepository $shopProductsRepository
     * @return void
     */
    public function __construct(Response $response, ShopProductsRepository $shopProductsRepository)
    {
        $this->_response = $response;
        $this->_shopProductRepository = $shopProductsRepository;
        $this->_fractal = new Manager();
        $this->_fractal->setSerializer(new CustomJsonSerializer());

    }

    /**
     * Display a listing of the resource.
     *
     * @param $shop_id
     * @param Request $request
     * @return array []
     */
    public function index($shop_id, $request)
    {
        $collectionResponse = $this->_shopProductRepository->index($shop_id, $request);
        if ($collectionResponse->has("data")) {
            $productObject = $collectionResponse->pull("data");
            if ($this->hasPagingObject($productObject)) {
                $productCollection = $productObject->getCollection();
                $resource = new Collection($productCollection, new ProductTransformer(), 'product');
                $resource->setPaginator(new IlluminatePaginatorAdapter($productObject));
            }
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $shop_id
     * @return array []
     */
    public function store($shop_id, $request)
    {
        $collectionObject = $this->_shopProductRepository->store($shop_id, $request);
        if ($collectionObject->has("data")) {
            $productObject = $collectionObject->pull("data");
            $resource = new Item($productObject, new ProductTransformer(), "product");
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($collectionObject->pull("exception"));
        }

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $shop_id
     * @param  $id
     * @return array []
     */
    public function update($shop_id, $request, $id)
    {
        $collectionObject = $this->_shopProductRepository->update($shop_id, $request, $id);
        if ($collectionObject->has("data")) {
            $productObject = $collectionObject->pull("data");
            $resource = new Item($productObject, new ProductTransformer(), "product");
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($collectionObject->pull("exception"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param  $shop_id
     * @return array []
     */
    public function destroy($shop_id, $id)
    {
        $collectionObject = $this->_shopProductRepository->destroy($shop_id, $id);
        if ($collectionObject->has("data")) {
            $productObject = $collectionObject->pull("data");
            $resource = new Item($productObject, new ProductTransformer(), "product");
            return $this->_fractal->createData($resource)->toArray();
        } else if ($collectionObject->has("not_found")) {
            return $this->_response->errorNotFound($collectionObject->pull("not_found"));
        } else {

            return $this->_response->errorInternalError($collectionObject->pull("exception"));
        }
    }
}