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
        try {
            $collectionResponse = $this->_collectionRepository->index($request);
            $collectionObject = $collectionResponse->pull("data");
            if ($request->has("_render")) {
                $resource = new Collection($collectionObject, new CollectionTransformer(), 'collections');
                return $this->_fractal->createData($resource)->toArray();
            }
            $collectionCollection = $collectionObject->getCollection();
            $resource = new Collection($collectionCollection, new CollectionTransformer(), 'collections');
            $resource->setPaginator(new IlluminatePaginatorAdapter($collectionObject));
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
            $isValidate = $this->_collectionCreateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_collectionRepository->store($request);
            $collectionResponse = $collectionResponse->pull("data");
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.collection_size")));
            }
            $resource = new Item($collectionResponse, new CollectionTransformer(), 'collection');
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
            $isValidate = $this->_collectionUpdateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_collectionRepository->update($request, $id);
            $collectionResponse = $collectionResponse->pull("data");
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.profile_sizes")));
            }
            $resource = new Item($collectionResponse->where(["id" => $id])->with("upload")->first(), new CollectionTransformer(), 'collection');
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
            $collectionResponse = $this->_collectionRepository->destroy($id);
            return $this->_response->withItem($collectionResponse->pull("data")->where(["id" => $id])->with("upload")->first(), new CollectionTransformer(), 'collection');
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
            'collection.title.required' => "Uh-oh! the { title } is required.",
            'collection.title.unique' => "Uh-oh! the { collection } has already been taken.",
            'collection.description.required' => "Uh-oh! the { description } is required.",
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
            'collection.title.required' => "Uh-oh! the { title } is required.",
            'collection.title.unique' => "Uh-oh! the { collection } has already been taken.",
            'collection.description.required' => "Uh-oh! the { description } is required.",
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }
}