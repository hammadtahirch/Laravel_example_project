<?php

namespace App\Models\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use App\Models\Eloquent\ProductVarianceOption as Option;
use Validator;

class VarianceOptionRepository
{
    /*
    |--------------------------------------------------------------------------
    | Variance Option Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling Variance Option Activity
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
     * @throws \Exception
     */
    public function index($request)
    {
        try {
            $optionPagination = Option::query()
                ->orderBy("created_at", "desc");
            $this->_optionFilter($optionPagination, $request);
            $this->_collection->put("data", $optionPagination->paginate(10));
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
        return $this->_collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Collection $_collection
     * @throws \Exception
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['option'];
        try {
            $optionObject = new Option($requestObject);
            $optionObject->save();
            if (!empty($optionObject->id)) {
                $optionObject = $optionObject
                    ->where(["id" => $optionObject->id])
                    ->first();
            }
            $this->_collection->put("data", $optionObject->where(["id" => $optionObject->id])->first());
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
        return $this->_collection;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $product_id
     * @param  int $id
     * @return Collection $_collection
     * @throws \Exception
     */
    public function update($request, $product_id, $id)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['option'];
        try {
            $optionObject = Option::find($id);
            if ($optionObject->update($requestObject)) {
                $this->_collection->put("data",
                    $optionObject
                        ->where(["id" => $id])
                        ->first()
                );
            }
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
        return $this->_collection;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @param  int $product_id
     * @return Collection $_collection
     * @throws \Exception
     */
    public function destroy($product_id, $id)
    {
        try {

            $optionObject = Option::find($id);
            if (!$optionObject) {
                throw new \Exception('Option not found');
            }
            if ($optionObject->delete()) {
                $this->_collection->put("data", $optionObject);
            } else {
                throw new \Exception('Internal server error Option not deleted');
            }
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
        return $this->_collection;
    }

    /**
     * This function responsible for filter records from Query.
     *
     * @param  array $request
     * @return Collection
     */
    private function _optionFilter($query, $request)
    {
        if ($request->has("title")) {
            return $query->where('title', 'like', '%' . $request->title . '%');
        } else {
            return $query;
        }
    }
}