<?php

namespace App\Services\AppServices;

use App\Models\Repositories\ShopTimeSlotRepository;
use App\Services\Transformers\CustomJsonSerializer;
use App\Services\Transformers\ShopTimeSlotTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Validator;

class ShopTimeSlotService extends BaseService
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
     * @var ShopTimeSlotRepository
     */
    protected $_shopTimeSlotRepository;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @var Manager
     */
    protected $_fractal;

    /**
     * Create a new Service instance.
     *
     * @param ShopTimeSlotRepository $shopTimeSlotRepository
     * @return void
     */
    public function __construct(ShopTimeSlotRepository $shopTimeSlotRepository)
    {
        parent::__construct();
        $this->_shopTimeSlotRepository = $shopTimeSlotRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $shop_id
     * @return array []
     */
    public function index($shop_id)
    {
        $collectionResponse = $this->_shopTimeSlotRepository->index($shop_id);
        if ($collectionResponse->has("data")) {
            $resource = new Collection($collectionResponse->pull("data"), new ShopTimeSlotTransformer(), 'shop_time_slots');
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError(
                $collectionResponse->pull("exception")
            );
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $shop_id
     * @return array []
     */
    public function save($shop_id, $request)
    {
        $collectionResponse = $this->_shopTimeSlotRepository->save($shop_id, $request);
        if ($collectionResponse->has("data")) {
            $resource = new Item($collectionResponse->pull("data"), new ShopTimeSlotTransformer(), 'shop_time_slot');
            return $this->_fractal->createData($resource)->toArray();

        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $shop_id
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return array []
     */
    public function update($shop_id, $request, $id)
    {
        $collectionResponse = $this->_shopTimeSlotRepository->update($shop_id, $request, $id);
        if ($collectionResponse->has("data")) {
            $resource = new Item($collectionResponse->pull("data"), new ShopTimeSlotTransformer(), 'shop_time_slot');
            return $this->_fractal->createData($resource)->toArray();

        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }
}