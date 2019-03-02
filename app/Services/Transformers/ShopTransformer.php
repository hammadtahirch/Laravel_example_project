<?php

namespace App\Services\Transformers;

use App\Models\Eloquent\Shop;
use App\Models\Eloquent\ShopTimeSlot;
use App\Models\Eloquent\User;
use League\Fractal\TransformerAbstract;

;

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
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        'upload',
        'user',
        'shop_time_slot'
    ];

    /**
     * Create a new transformer instance.
     *
     * @param Shop $shop
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

    /**
     * Include Upload
     *
     * @param Shop $shop
     * @return mixed
     */
    public function includeUpload(Shop $shop)
    {
        $uploadObject = $shop->upload;
        if (!empty($uploadObject))
            return $this->item($uploadObject, new UploadTransformer(), false);
        else
            return $this->null();
    }

    /**
     * Include Upload
     *
     * @param Shop $shop
     * @return mixed
     */
    public function includeUser(Shop $shop)
    {
        $userObject = $shop->user;
        if (!empty($userObject))
            return $this->item($userObject, new UserTransformer(), false);
        else
            return $this->null();
    }

    /**
     * Include Upload
     *
     * @param Shop $shop
     * @return mixed
     */
    public function includeShopTimeSlot(Shop $shop)
    {
        $shopTimeSlotObject = $shop->shop_time_slot;
        if (!empty($shopTimeSlotObject))
            return $this->collection($shopTimeSlotObject, new ShopTimeSlotTransformer(), false);
        else
            return $this->null();
    }
}