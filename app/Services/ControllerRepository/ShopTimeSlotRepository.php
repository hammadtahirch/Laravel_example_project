<?php

namespace App\Services\ControllerRepository;

use App\Models\Eloquent\Shop;
use App\Models\Eloquent\ShopTimeSlot;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Validator;

class ShopTimeSlotRepository
{
    /*
    |--------------------------------------------------------------------------
    | ShopTime Slot Service
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
     * @param $shop_id
     * @return Collection $_collection
     */
    public function index($shop_id)
    {
        try {
            $timeSlotObject = ShopTimeSlot::query()
                ->where(["shop_id" => $shop_id])
                ->get();

            $this->_collection->put("data", $timeSlotObject);

        } catch (QueryException $exception) {
            $this->_collection->put("data",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            $this->_collection->put("data",
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
     * @param  int $shop_id
     * @return Collection $_collection
     */
    public function save($shop_id, $request)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['shop_time_slot'];
        try {
            $timeSlotObject = '';
            if ($timeSlotObject->update($requestObject)) {
                $this->_collection->pull("data", $timeSlotObject);
            }
        } catch (QueryException $exception) {
            $this->_collection->pull("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->pull("exception",
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
     * @param  int $shop_id
     * @param  int $id
     * @return Collection $_collection
     */
    public function update($shop_id, $request, $id)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['shop_time_slot'];
        try {
            $timeSlotObject = Shop::find($id);
            if ($timeSlotObject->update($requestObject)) {
                $this->_collection->pull("data", $timeSlotObject);
            }
        } catch (QueryException $exception) {
            $this->_collection->pull("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->pull("exception",
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;
    }
}