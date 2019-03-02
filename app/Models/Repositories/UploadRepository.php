<?php

namespace App\Models\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use App\Models\Eloquent\Upload as FileUpload;

class UploadRepository
{
    /*
    |--------------------------------------------------------------------------
    | Upload Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling Upload Activity
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Collection $_collection
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['upload'];
        try {

            $uploadObject = new FileUpload($requestObject);
            $uploadObject->save();
            if (!empty($uploadObject->id)) {
                $uploadObject = $uploadObject
                    ->where(["id" => $uploadObject->id])
                    ->first();
            }

            $this->_collection->put("data", $uploadObject);

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
        $requestObject = $requestObject['upload'];
        try {
            $uploadObject = FileUpload::find($id);
            if ($uploadObject->update($requestObject)) {
                $this->_collection->put("data", $uploadObject);
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

            $uploadObject = FileUpload::find($id);
            if (!$uploadObject) {
                $this->_collection->put("not_found", ['message' => 'Collection not found']);
            }
            if ($uploadObject->delete()) {
                $this->_collection->put("data", $uploadObject);
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
}