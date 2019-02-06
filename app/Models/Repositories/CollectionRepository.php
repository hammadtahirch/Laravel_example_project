<?php

namespace App\Models\Repositories;

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
     * Create a new Service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_collection = new Collection();

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
            $collectionPagination = MarketCollection::query();

            $this->_CollectionFilter($collectionPagination, $request);
            if ($request->has("_render")) {
                $this->_collection->put("data", $collectionPagination->get());
            } else {
                $this->_collection->put("data", $collectionPagination->paginate(10));
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! exception contact to admin",
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
                    ->where(["id" => $collectionObject->id])
                    ->first();
            }

            $this->_collection->put("data", $collectionObject);

        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! exception contact to admin",
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
                $this->_collection->put("data", $collectionObject);
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! exception contact to admin",
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
                $this->_collection->put("data", $collectionObject);
            } else {
                $this->_collection->put("exception", ['message' => 'Internal server error user not deleted']);
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! Query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! exception contact to admin",
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