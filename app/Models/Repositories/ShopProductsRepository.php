<?php

namespace App\Models\Repositories;

use App\Models\Eloquent\Product;

use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Validator;

class ShopProductsRepository
{
    /*
    |--------------------------------------------------------------------------
    | Shop Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling shop Products Activity
    |
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
     * @param $shop_id
     * @param $request
     * @return Collection $_collection
     */
    public function index($shop_id, $request)
    {
        try {
            $productPagination = Product::query()
                ->paginate(10);
            $this->_collection->put("data", $productPagination);
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
     * @param  $shop_id
     * @param  \Illuminate\Http\Request $request
     * @return Collection $_collection
     */
    public function store($shop_id, $request)
    {
        try {
            $requestObject = $request->all();
            $requestObject["product"]["shop_id"] = $shop_id;
            $productObject = new Product($requestObject["product"]);
            $productObject->save();
            if ($productObject->id) {
                $productObject = $productObject->where(["id" => $productObject->id])->first();
                $this->_collection->put("data", $productObject);
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
     * Display the specified resource.
     *
     * @param  $shop_id
     * @param  $id
     * @return Collection $_collection
     */
    public function show($shop_id, $id)
    {
        try {
            $productPagination = Product::query()
                ->where(["id" => $id])
                ->first();
            $this->_collection->put("data", $productPagination);
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
     * Update the specified resource in storage.
     *
     * @param  $shop_id
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Collection $_collection
     */
    public function update($shop_id, $request, $id)
    {
        try {
            $requestObject = $request->all();
            $requestObject = $requestObject["product"];
            $productObject = Product::find($id);
            if ($productObject->update($requestObject)) {
                $this->_collection->put("data", $productObject);
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
     * Remove the specified resource from storage.
     *
     * @param  $shop_id
     * @param  $id
     * @return Collection $_collection
     */
    public function destroy($shop_id, $id)
    {
        $userObject = Product::find($id);

        if (!$userObject) {
            $this->_collection->put("not_found", ['message' => 'Product not found.']);
        } else if ($userObject->delete()) {
            $this->_collection->put("data", $userObject);
        } else {
            $this->_collection->put("exception", ['message' => 'Internal server error user not deleted']);
        }
        return $this->_collection;
    }
}