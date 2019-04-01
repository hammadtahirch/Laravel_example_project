<?php

namespace App\Models\Repositories;

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
    | ShopTime Slot Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling shop Activity
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
     * @param $shop_id
     * @return Collection $_collection
     * @throws \Exception
     */
    public function index($shop_id)
    {
        try {
            $timeSlotObject = ShopTimeSlot::query()
                ->where(["shop_id" => $shop_id])->orderBy("day", "asc")
                ->get();

            $this->_collection->put("data", $timeSlotObject);

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
     * @param  int $shop_id
     * @return Collection $_collection
     * @throws \Exception
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
     * @param  int $shop_id
     * @param  int $id
     * @return Collection $_collection
     * @throws \Exception
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
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
        return $this->_collection;
    }
}