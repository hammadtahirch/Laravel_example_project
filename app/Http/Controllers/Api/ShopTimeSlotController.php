<?php

namespace App\Http\Controllers\Api;

use App\Models\ShopTimeSlot;
use App\Services\AppServices\ShopTimeSlotService;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopTimeSlotController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | ShopTimeSlot Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling ShopTimeSlot Activity
    |
    */

    /**
     * Display a listing of the resource.
     *
     * @param ShopTimeSlotService $timeSlotService
     * @param $shop_id
     * @return mixed
     */
    public function index(ShopTimeSlotService $timeSlotService, $shop_id)
    {
        return $timeSlotService->index($shop_id);
    }

    /**
     * Save the specified resource in storage.
     *
     * @param  ShopTimeSlotService $timeSlotService
     * @param  $shop_id
     * @param  Request $request
     * @return mixed
     */
    public function save(ShopTimeSlotService $timeSlotService, $shop_id, Request $request)
    {
        return $timeSlotService->save($shop_id, $request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ShopTimeSlotService $timeSlotService
     * @param  $shop_id
     * @param  Request $request
     * @param  $id
     * @return mixed
     */
    public function update(ShopTimeSlotService $timeSlotService, $shop_id, Request $request, $id)
    {
        return $timeSlotService->update($shop_id, $request, $id);
    }
}
