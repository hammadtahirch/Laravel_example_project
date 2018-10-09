<?php

namespace App\Http\Controllers\Api;

use App\Models\ShopTimeSlot;
use App\Services\ControllerServices\ShopTimeSlotService;
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
     * @return \Illuminate\Http\Response
     */
    public function index(ShopTimeSlotService $timeSlot, $shop_id)
    {
        return $timeSlot->index($shop_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(ShopTimeSlotService $timeSlot, $shop_id, Request $request, $id)
    {
        $timeSlot->update($shop_id,$request,$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
