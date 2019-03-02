<?php

namespace App\Services\AppServices;

use App\Jobs\GenerateResizedImageJob;
use App\Models\Repositories\CollectionRepository;
use App\Models\Repositories\UploadRepository;
use App\Services\Transformers\CollectionTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use Validator;
use App\Services\Constants\StatusCodes;

class CollectionService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Collection Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Collection Activity
    |
    */

    /**
     * @var CollectionRepository
     */
    protected $_collectionRepository;


    /**
     * Create a new Service instance.
     *
     * @param CollectionRepository $collectionRepository
     * @param UploadService $uploadService
     * @param UploadRepository $uploadRepository
     * @return void
     */
    public function __construct(CollectionRepository $collectionRepository)
    {
        parent::__construct();
        $this->_collectionRepository = $collectionRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return array []
     */
    public function index($request)
    {
        $collectionResponse = $this->_collectionRepository->index($request);
        if ($collectionResponse->has("data")) {
            $collectionObject = $collectionResponse->pull("data");
            $collectionCollection = $collectionObject->getCollection();
            $resource = new Collection($collectionCollection, new CollectionTransformer(), 'collections');
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
        $requestObject = $request->all();
        $isValidate = $this->_collectionCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $collectionResponse = $this->_collectionRepository->store($request);
        if ($collectionResponse->has("data")) {
            $collectionResponse = $collectionResponse->pull("data");
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.collection_size")));
            }
            $resource = new Item($collectionResponse, new CollectionTransformer(), 'collection');
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
        $isValidate = $this->_collectionUpdateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }

        $collectionResponse = $this->_collectionRepository->update($request, $id);
        if ($collectionResponse->has("data")) {
            $collectionResponse = $collectionResponse->pull("data");
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.profile_sizes")));
            }
            $resource = new Item($collectionResponse->where(["id" => $id])->with("upload")->first(), new CollectionTransformer(), 'collection');
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
        $collectionResponse = $this->_collectionRepository->destroy($id);
        if ($collectionResponse->has("data")) {
            return $this->_response->withItem($collectionResponse->pull("data")->where(["id" => $id])->with("upload")->first(), new CollectionTransformer(), 'collection');
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
            'collection.title' => 'required|unique:collections,title,' . $request["collection"]["id"],
            'collection.description' => 'required',
        ];
        $messages = [
            'collection.title.required' => "Oops! the { title } is required.",
            'collection.title.unique' => "Oops! the { collection } has already been taken.",
            'collection.description.required' => "Oops! the { description } is required.",
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
            'collection.title' => 'required|unique:collections,title',
            'collection.description' => 'required',
        ];
        $messages = [
            'collection.title.required' => "Oops! the { title } is required.",
            'collection.title.unique' => "Oops! the { collection } has already been taken.",
            'collection.description.required' => "Oops! the { description } is required.",
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }
}