<?php

namespace App\Services\ControllerServices;

use App\Models\Shop;
use App\Models\ShopTimeSlot;
use App\Services\TransformerServices\CustomJsonSerializer;
use App\Services\TransformerServices\ShopTimeSlotTransformer;
use App\Services\TransformerServices\ShopTransformer;
use App\Services\TransformerServices\UserTransformer;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use Validator;
use App\Services\ConstantServices\StatusCodes;

class ShopTimeSlotService
{
    /*
    |--------------------------------------------------------------------------
    | ShopTime Slot Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling shop Activity
    |
    */

    protected $_roleService = null;
    protected $_response = null;
    protected $_fractal = null;

    /**
     * Create a new Service instance.
     *
     * @param Response $response
     * @return void
     */
    public function __construct($response)
    {
        $this->_response = $response;
        $this->_fractal = new Manager();
        $this->_fractal->setSerializer(new CustomJsonSerializer());

    }

    /**
     * Display a listing of the resource.
     *
     * @return @return array []
     */
    public function index($shop_id)
    {
        dd("Hammad");
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
    public function update($shop_id, $request, $id)
    {
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