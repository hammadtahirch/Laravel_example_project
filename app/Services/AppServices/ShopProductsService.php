<?php

namespace App\Services\AppServices;

use App\Jobs\GenerateResizedImageJob;
use App\Models\Eloquent\Product;
use App\Models\Eloquent\ProductVariance;
use App\Models\Repositories\ProductVarianceRepository;
use App\Models\Repositories\ShopProductsRepository;
use App\Models\Repositories\UploadRepository;
use App\Models\Repositories\VarianceOptionRepository;
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
     * @var ShopProductsRepository
     */
    protected $_shopProductRepository;

    /**
     * @var ProductVarianceRepository
     */
    protected $_productVariance;

    /**
     * @var VarianceOptionRepository
     */
    protected $_varianceOption;


    /**
     * Create a new Service instance.
     *
     * @param ShopProductsRepository $shopProductsRepository
     * @return void
     */
    public function __construct(ShopProductsRepository $shopProductsRepository, ProductVarianceRepository $productVarianceRepository, VarianceOptionRepository $varianceOptionRepository)
    {
        parent::__construct();
        $this->_shopProductRepository = $shopProductsRepository;
        $this->_productVariance = $productVarianceRepository;
        $this->_varianceOption = $varianceOptionRepository;

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
        try {
            $collectionResponse = $this->_shopProductRepository->index($shop_id, $request);
            $productObject = $collectionResponse->pull("data");
            if ($this->hasPagingObject($productObject)) {
                $productCollection = $productObject->getCollection();
                $resource = new Collection($productCollection, new ProductTransformer(), 'products');
                $resource->setPaginator(new IlluminatePaginatorAdapter($productObject));
            }
            return $this->_fractal->createData($resource)->toArray();

        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }

    /**
     * Display a listing of the resource.
     *
     * @param $shop_id
     * @param $id
     * @return array []
     */
    public function show($shop_id, $id)
    {
        try {
            $collectionResponse = $this->_shopProductRepository->show($shop_id, $id);
            $productObject = $collectionResponse->pull("data");
            $resource = new Item($productObject, new ProductTransformer(), 'product');
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        try {
            $collectionObject = $this->_shopProductRepository->store($shop_id, $request);
            $productObject = $collectionObject->pull("data");
            if (!empty($productObject->upload)) {
                dispatch(new GenerateResizedImageJob($productObject->upload->toArray(), config("custom_config.product_size")));
            }
            $resource = new Item($productObject, new ProductTransformer(), "product");
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        try {
            $collectionObject = $this->_shopProductRepository->update($shop_id, $request, $id);
            $productObject = $collectionObject->pull("data");
            if (!empty($productObject->upload)) {
                dispatch(new GenerateResizedImageJob($productObject->upload->toArray(), config("custom_config.product_size")));
            }
            $resource = new Item($productObject, new ProductTransformer(), "product");
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        try {
            $collectionObject = $this->_shopProductRepository->destroy($shop_id, $id);
            $productObject = $collectionObject->pull("data");
            $resource = new Item($productObject, new ProductTransformer(), "product");
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }

    /**
     * Duplicate Current Product
     *
     * @param $shop_id
     * @param $product_id
     * @param $request
     * @return array
     * @throws \Exception
     */
    public function duplicateProduct($shop_id, $product_id, $request)
    {
        try {


            $productObject = $this->_shopProductRepository->show($shop_id, $product_id);
            $productDetail = $productObject->get("data");
            $productVariances = $productDetail->product_variance;
            $request->request->add(
                ["product" => [
                    'shop_id' => $productDetail->shop_id,
                    'title' => "Copy " . $productDetail->title,
                    'description' => $productDetail->description,
                    'price' => $productDetail->price,
                    'is_published' => $productDetail->is_published,
                    'published_date' => $productDetail->published_date,
                    'status' => $productDetail->status,
                ]]);
            $newProduct = $this->_shopProductRepository->store($shop_id, $request)->get("data");
            foreach ($productVariances as $index => $variance) {
                $request->request->add(["variance" => [
                    'title' => $variance->title,
                    'product_id' => $newProduct->id,
                    'description' => $variance->description,
                    'max_permitted' => $variance->max_permitted,
                    'min_permitted' => $variance->min_permitted,
                ]]);
                $newVariance = $this->_productVariance->store($request)->get("data");
                foreach ($variance->product_variance_option as $index => $option) {
                    $request->request->add(["option" => [
                        'variance_id' => $newVariance->id,
                        'title' => $option->title,
                        'price' => $option->price
                    ]]);
                    $this->_varianceOption->store($request);
                }
            }

            $newProduct = $this->_shopProductRepository->show($shop_id, $newProduct->id);
            $resource = new Item($newProduct->get("data"), new ProductTransformer(), "product");
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }
    }
}