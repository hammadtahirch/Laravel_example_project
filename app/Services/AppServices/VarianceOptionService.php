<?php

namespace App\Services\AppServices;

use App\Models\Repositories\CollectionRepository;
use App\Models\Repositories\VarianceOptionRepository;
use App\Services\Transformers\VarianceOptionTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use Validator;
use App\Services\Constants\StatusCodes;

class VarianceOptionService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | VarianceOption Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Product Variance Option Activity
    |
    */

    /**
     * @var CollectionRepository
     */
    protected $_varianceOptionRepository;


    /**
     * Create a new Service instance.
     *
     * @param VarianceOptionRepository $varianceOptionRepository
     * @return void
     */
    public function __construct(VarianceOptionRepository $varianceOptionRepository)
    {
        parent::__construct();
        $this->_varianceOptionRepository = $varianceOptionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $variance_id
     * @param $request
     * @return array []
     */
    public function index($variance_id, $request)
    {
        $collectionResponse = $this->_varianceOptionRepository->index($request);
        if ($collectionResponse->has("data")) {
            $collectionObject = $collectionResponse->pull("data");
            $collectionCollection = $collectionObject->getCollection();
            $resource = new Collection($collectionCollection, new VarianceOptionTransformer(), 'options');
            $resource->setPaginator(new IlluminatePaginatorAdapter($collectionObject));
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $variance_id
     * @param  $request
     * @return  mixed
     */
    public function store($variance_id, $request)
    {
        $requestObject = $request->all();
        $isValidate = $this->_collectionCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $collectionResponse = $this->_varianceOptionRepository->store($request);
        if ($collectionResponse->has("data")) {
            $collectionResponse = $collectionResponse->pull("data");
            $resource = new Item($collectionResponse, new VarianceOptionTransformer(), 'option');
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $request
     * @param  $variance_id
     * @param  $id
     * @return mixed
     */
    public function update($variance_id, $id, $request)
    {
        $requestObject = $request->all();
        $isValidate = $this->_collectionUpdateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }

        $collectionResponse = $this->_varianceOptionRepository->update($request, $variance_id, $id);
        if ($collectionResponse->has("data")) {
            $collectionResponse = $collectionResponse->pull("data");
            $resource = new Item($collectionResponse, new VarianceOptionTransformer(), 'option');
            return $this->_fractal->createData($resource)->toArray();

        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @param  $variance_id
     * @return mixed
     */
    public function destroy($variance_id, $id)
    {
        $collectionResponse = $this->_varianceOptionRepository->destroy($variance_id, $id);
        if ($collectionResponse->has("data")) {
            $resource = new Item($collectionResponse->pull("data"), new VarianceOptionTransformer(), 'option');
            return $this->_fractal->createData($resource)->toArray();
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
            'option.title' => 'required',
            'option.price' => 'required',
        ];
        $messages = [
            'option.title.required' => "Uh-oh! the { title } is required.",
            'option.price.required' => "Uh-oh! the { price } is required.",
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
            'option.title' => 'required',
            'option.price' => 'required',
        ];
        $messages = [
            'option.title.required' => "Uh-oh! the { title } is required.",
            'option.price.required' => "Uh-oh! the { price } is required.",
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }
}