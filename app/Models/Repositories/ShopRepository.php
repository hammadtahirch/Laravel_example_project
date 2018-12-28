<?php

namespace App\Models\Repositories;

use App\Models\Eloquent\Shop;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Validator;

class ShopRepository
{
    /*
    |--------------------------------------------------------------------------
    | Shop Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling shop Activity
    |
    */

    protected $_collection;

    /**
     * Create a new Service instance.
     *
     * @param Response $response
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
            $shopPagination = Shop::query()
                ->with(
                    [
                        'user' => function ($query) {
                            $query->select("id", "name", "email");
                        },
                        'shop_time_slot' => function ($query) {
                            $query->select('id', 'shop_id', 'day', 'deliver_start_time', 'delivery_end_time', 'change_delivery_date', 'pickup_start_time', 'pickup_end_time', 'change_pickup_date');
                        }
                    ]
                )
                ->paginate(10);

            $this->_collection->put("data", $shopPagination);
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
        $requestObject = $requestObject['shop'];
        try {

            $shopObject = new Shop($requestObject);
            $shopObject->save();
            if ($shopObject->id > 0) {
                $this->generateShopTimings($shopObject);
                $shopObject = $shopObject
                    ->with(
                        [
                            'user' => function ($query) {
                                $query->select("id", "name", "email");
                            },
                            'shop_time_slot' => function ($query) {
                                $query->select('id', 'shop_id', 'day', 'deliver_start_time', 'delivery_end_time', 'change_delivery_date', 'pickup_start_time', 'pickup_end_time', 'change_pickup_date');
                            }
                        ]
                    )
                    ->where(["id" => $shopObject->id])
                    ->first();
            }
            $this->_collection->put("data", $shopObject);

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
        $requestObject = $requestObject['shop'];
        try {
            $this->_collection->put("data", []);
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

            $shopObject = Shop::find($id);
            if (!$shopObject) {
                $this->_collection->put("not_found", ['message' => 'User not found.']);
            }
            if ($shopObject->delete()) {
                $shopObject = $shopObject->with(
                    [
                        'user' => function ($query) {
                            $query->select("id", "name", "email");
                        },
                        'shop_time_slot' => function ($query) {
                            $query->select('id', 'shop_id', 'day', 'deliver_start_time', 'delivery_end_time', 'change_delivery_date', 'pickup_start_time', 'pickup_end_time', 'change_pickup_date');
                        }
                    ]
                )->first();
                $this->_collection->put("data", $shopObject);
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
    private function _shopFilter($query, $request)
    {

        if ($request->has("name")) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        } else if ($request->has("email")) {
            return $query->where('email', '=', $request->email);
        } else if ($request->has("phone_number")) {
            return $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
        } else {
            return $query;
        }
    }

    /**
     * This function responsible for filter records from Query.
     *
     * @param  Model $query
     * @return Collection
     */
    private function generateShopTimings($query)
    {
        $timeSlotStack = [];
        for ($i = 1; $i <= 7; $i++) {
            $tempArray = [
                'shop_id' => $query->id,
                'day' => $i,
                'deliver_start_time' => "09:00:00",
                'delivery_end_time' => "18:00:00",
                'change_delivery_date' => null,
                'pickup_start_time' => "09:00:00",
                'pickup_end_time' => "18:00:00",
                'change_pickup_date' => null,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            array_push($timeSlotStack, $tempArray);

        }
        return $query->shop_time_slot()->insert($timeSlotStack);

    }
}