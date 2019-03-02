<?php

namespace App\Services\AppServices;

use App\Models\Repositories\CollectionRepository;
use App\Models\Repositories\ProductVarianceRepository;
use App\Services\Transformers\ProductVarianceTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use Validator;
use App\Services\Constants\StatusCodes;

class ProductVarianceService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Product Variance Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Product Variance Activity
    |
    */

    /**
     * @var CollectionRepository
     */
    protected $_productVarianceRepository;


    /**
     * Create a new Service instance.
     *
     * @param ProductVarianceRepository $productVarianceRepository
     * @return void
     */
    public function __construct(ProductVarianceRepository $productVarianceRepository)
    {
        parent::__construct();
        $this->_productVarianceRepository = $productVarianceRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return array []
     */
    public function index($request)
    {
        $collectionResponse = $this->_productVarianceRepository->index($request);
        if ($collectionResponse->has("data")) {
            $collectionObject = $collectionResponse->pull("data");
            $collectionCollection = $collectionObject->getCollection();
            $resource = new Collection($collectionCollection, new ProductVarianceTransformer(), 'variances');
            $resource->setPaginator(new IlluminatePaginatorAdapter($collectionObject));
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
//        $requestObject = $request->all();
//        $isValidate = $this->_collectionCreateValidator($requestObject);
//        if (!empty($isValidate)) {
//            return $isValidate;
//        }
        $collectionResponse = $this->_productVarianceRepository->store($request);
        if ($collectionResponse->has("data")) {
            $collectionResponse = $collectionResponse->pull("data");
            $resource = new Item($collectionResponse, new ProductVarianceTransformer(), 'variance');
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $product_id
     * @param  int $id
     * @return mixed
     */
    public function update($request, $product_id, $id)
    {
//        $requestObject = $request->all();
//        $isValidate = $this->_collectionUpdateValidator($requestObject);
//        if (!empty($isValidate)) {
//            return $isValidate;
//        }

        $collectionResponse = $this->_productVarianceRepository->update($request, $product_id, $id);
        if ($collectionResponse->has("data")) {
            $collectionResponse = $collectionResponse->pull("data");
            $resource = new Item($collectionResponse, new ProductVarianceTransformer(), 'variance');
            return $this->_fractal->createData($resource)->toArray();

        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param  int $product_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($product_id, $id)
    {
        $collectionResponse = $this->_productVarianceRepository->destroy($product_id, $id);
        if ($collectionResponse->has("data")) {
            return $this->_response->withItem($collectionResponse->pull("data"), new ProductVarianceTransformer(), 'variance');
        } elseif ($collectionResponse->has("not_found")) {
            return $this->_response->errorNotFound($collectionResponse->pull("not_found"));
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * This function responsible for validating collection on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _collectionUpdateValidator(array $request)
    {
        $rules = [
            'variance.title' => 'required' . $request["variance"]["id"],
            'variance.description' => 'required',
        ];
        $messages = [
            'variance.title.required' => "Oops! the { title } is required.",
            'variance.description.required' => "Oops! the { description } is required.",
        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }

    /**
     * This function responsible for validating collection on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _collectionCreateValidator(array $request)
    {
        $rules = [
            'variance.title' => 'required',
            'variance.description' => 'required',
        ];
        $messages = [
            'variance.title.required' => "Oops! the { title } is required.",
            'variance.description.required' => "Oops! the { description } is required.",
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }
}