<?php

namespace App\Models\Repositories;

use App\Services\AppServices\UploadService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use App\Models\Eloquent\Collection as MarketCollection;
use Validator;

class CollectionRepository
{
    /*
    |--------------------------------------------------------------------------
    | Collection Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling Collection Activity
    |
    */

    /**
     * @var Collection
     */
    protected $_collection;

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
     * @param UploadService $uploadService
     * @param UploadRepository $uploadRepository
     * @return void
     */
    public function __construct(UploadService $uploadService, UploadRepository $uploadRepository)
    {
        $this->_collection = new Collection();
        $this->_uploadService = $uploadService;
        $this->_uploadRepository = $uploadRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return Collection $_collection
     */
    public function index($request)
    {
        try {
            $collectionPagination = MarketCollection::query()
                ->with(["upload"])
                ->orderBy("created_at", "desc");
            $this->_CollectionFilter($collectionPagination, $request);
            if ($request->has("_render")) {
                $this->_collection->put("data", $collectionPagination->get());
            } else {
                $this->_collection->put("data", $collectionPagination->paginate(10));
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Collection $_collection
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['collection'];
        try {
            $collectionObject = new MarketCollection($requestObject);
            $collectionObject->save();
            if (!empty($collectionObject->id)) {
                $collectionObject = $collectionObject
                    ->with(["upload"])
                    ->where(["id" => $collectionObject->id])
                    ->first();
            }
            //upload image
            $request->request->add(["collection_id" => $collectionObject->id, "dataUrl" => $request->get("collection")["dataUrl"]]);
            $imagePayload = $this->_uploadService->storeImage($request);

            $request->request->add(["upload" => [
                'name' => $imagePayload["name"],
                'relative_path' => $imagePayload["relative_path"],
                'storage_url' => $imagePayload["storage_url"],
                'extension' => $imagePayload["extension"],
                'collection_id' => $collectionObject->id]
            ]);
            $this->_uploadRepository->store($request);
            //upload image

            $this->_collection->put("data", $collectionObject->where(["id" => $collectionObject->id])->with("upload")->first());
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Collection $_collection
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['collection'];
        try {
            $collectionObject = MarketCollection::find($id);
            if ($collectionObject->update($requestObject)) {
                if (!empty($request->get('collection')["dataUrl"])) {

                    if (!empty($request->get("collection")["upload"])) {
                        $this->_uploadRepository->destroy($request->get("collection")["upload"]["id"]);
                    }
                    $request->request->add(["collection_id" => $id, "dataUrl" => $request->get("collection")["dataUrl"]]);
                    $imagePayload = $this->_uploadService->storeImage($request);

                    $request->request->add(["upload" => [
                        'name' => $imagePayload["name"],
                        'relative_path' => $imagePayload["relative_path"],
                        'storage_url' => $imagePayload["storage_url"],
                        'extension' => $imagePayload["extension"],
                        'collection_id' => $id]
                    ]);
                    $this->_uploadRepository->store($request);

                }
                $this->_collection->put("data",
                    $collectionObject
                        ->where(["id" => $id])
                        ->with(["upload"])
                        ->first()
                );
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Collection $_collection
     */
    public function destroy($id)
    {
        try {

            $collectionObject = MarketCollection::find($id);
            if (!$collectionObject) {
                $this->_collection->put("not_found", ['message' => 'Collection not found']);
            }
            if ($collectionObject->delete()) {
                $this->_collection->put("data", $collectionObject->with(["upload"])->first());
            } else {
                $this->_collection->put("exception", ['message' => 'Internal server error collection not deleted']);
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! Query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;
    }

    /**
     * This function responsible for filter records from Query.
     *
     * @param  array $request
     * @return Collection
     */
    private function _CollectionFilter($query, $request)
    {
        if ($request->has("title")) {
            return $query->where('title', 'like', '%' . $request->title . '%');
        } else {
            return $query;
        }
    }
}