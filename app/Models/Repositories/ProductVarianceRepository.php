<?php

namespace App\Models\Repositories;

use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use App\Models\Eloquent\ProductVariance as Variance;
use Validator;

class ProductVarianceRepository
{
    /*
    |--------------------------------------------------------------------------
    | Product Variance Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling Product Variance Activity
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
            $variancePagination = Variance::query()
                ->orderBy("created_at", "desc");
            $this->_varianceFilter($variancePagination, $request);
            $this->_collection->put("data", $variancePagination->paginate(10));
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
        $requestObject = $requestObject['variance'];
        try {
            $varianceObject = new Variance($requestObject);
            $varianceObject->save();
            if (!empty($varianceObject->id)) {
                $varianceObject = $varianceObject
                    ->where(["id" => $varianceObject->id])
                    ->first();
            }
            $this->_collection->put("data", $varianceObject->where(["id" => $varianceObject->id])->first());
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
        $requestObject = $requestObject['variance'];
        try {
            $varianceObject = Variance::find($id);
            if ($varianceObject->update($requestObject)) {
                $this->_collection->put("data",
                    $varianceObject
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

            $varianceObject = Variance::find($id);
            if (!$varianceObject) {
                throw new \Exception('Variance not found');
            }
            if ($varianceObject->delete()) {
                $this->_collection->put("data", $varianceObject);
            } else {
                throw new \Exception('Internal server error Variance not deleted');
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
    private function _varianceFilter($query, $request)
    {
        if ($request->has("title")) {
            return $query->where('title', 'like', '%' . $request->title . '%');
        } else {
            return $query;
        }
    }
}