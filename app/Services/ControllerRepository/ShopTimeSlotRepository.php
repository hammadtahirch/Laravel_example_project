<?php

namespace App\Services\ControllerRepository;

use App\Models\Eloquent\Shop;
use App\Models\Eloquent\ShopTimeSlot;
use App\Services\TransformerServices\CustomJsonSerializer;
use App\Services\TransformerServices\ShopTimeSlotTransformer;
use App\Services\TransformerServices\UserTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Database\QueryException;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
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

    /**
     * Create a new Service instance.
     *
     * @param Response $response
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->_response = $response;
        $this->_fractal = new Manager();
        $this->_fractal->setSerializer(new CustomJsonSerializer());

    }

    /**
     * Display a listing of the resource.
     *
     * @return array []
     */
    public function index($shop_id)
    {
        try {
            $timeSlotObject = ShopTimeSlot::query()
                ->where(["shop_id" => $shop_id])
                ->get();

            $resource = new Collection($timeSlotObject, new ShopTimeSlotTransformer(), 'shop_time_slots');
            return $this->_fractal->createData($resource)->toArray();

        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function save($shop_id, $request)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['shop_time_slot'];
        try {
            $timeSlotObject = '';
            if ($timeSlotObject->update($requestObject)) {
                $resource = new Item($timeSlotObject, new ShopTimeSlotTransformer(), 'shop_time_slot');
                return $this->_fractal->createData($resource)->toArray();
            }
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_response
            ->withItem('', new UserTransformer, 'shop');
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($shop_id, $request, $id)
    {
        dd($shop_id, $request, $id);
        $requestObject = $request->all();
        $requestObject = $requestObject['shop_time_slot'];
        try {
            $timeSlotObject = Shop::find($id);
            if ($timeSlotObject->update($requestObject)) {
                $resource = new Item($timeSlotObject, new ShopTimeSlotTransformer(), 'shop_time_slot');
                return $this->_fractal->createData($resource)->toArray();
            }
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_response
            ->withItem('', new UserTransformer, 'shop');
    }
}