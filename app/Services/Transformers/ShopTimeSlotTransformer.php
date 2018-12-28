<?php

namespace App\Services\Transformers;

use App\Models\Eloquent\Shop;
use App\Models\Eloquent\ShopTimeSlot;
use App\Models\Eloquent\User;
use League\Fractal\TransformerAbstract;

;

class ShopTimeSlotTransformer extends TransformerAbstract
{
    /*
    |--------------------------------------------------------------------------
    | User Transformer
    |--------------------------------------------------------------------------
    |
    | This transformer is responsible to User Transformation
    |
    */

    /**
     * Create a new transformer instance.
     *
     * @param Shop $shop
     *
     * @return array
     */
    public function transform(ShopTimeSlot $timeSlot)
    {

        return [
            'id' => $timeSlot->id,
            'shop_id' => $timeSlot->shop_id,
            'day' => $timeSlot->day,
            'deliver_start_time' => $timeSlot->deliver_start_time,
            'delivery_end_time' => $timeSlot->delivery_end_time,
            'change_delivery_date' => $timeSlot->change_delivery_date,
            'pickup_start_time' => $timeSlot->pickup_start_time,
            'pickup_end_time' => $timeSlot->pickup_end_time,
            'change_pickup_date' => $timeSlot->change_pickup_date,


            "created_by" => $timeSlot->created_by,
            "updated_by" => $timeSlot->updated_by,
            "deleted_at" => $timeSlot->deleted_at,
            "created_at" => $timeSlot->created_at,
            "updated_at" => $timeSlot->updated_at
        ];
    }
}