<?php
namespace App\Services\TransformerServices;

use App\Models\Eloquent\Shop;
use App\Models\Eloquent\User;
use League\Fractal\TransformerAbstract;;

class ShopTransformer extends TransformerAbstract
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
    public function transform(Shop $shop)
    {

        return [
            'id' => $shop->id,
            'user_id' => $shop->user_id,
            'title' => $shop->title,
            'description' => $shop->description,
            'address' => $shop->address,
            'city' => $shop->city,
            'province' => $shop->province,
            'country' => $shop->country,
            'portal_code' => $shop->portal_code,
            'latitude' => $shop->latitude,
            'longitude' => $shop->longitude,
            'user' => $shop->user,
            'shop_time_slot' => $shop->shop_time_slot,


            "created_by" => $shop->created_by,
            "updated_by" => $shop->updated_by,
            "deleted_at" => $shop->deleted_at,
            "created_at" => $shop->created_at,
            "updated_at" => $shop->updated_at
        ];
    }
}