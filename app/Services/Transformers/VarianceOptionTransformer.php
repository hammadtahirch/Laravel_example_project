<?php

namespace App\Services\Transformers;

use App\Models\Eloquent\Collection;
use App\Models\Eloquent\ProductVariance;
use App\Models\Eloquent\Shop;
use App\Models\Eloquent\User;
use League\Fractal\TransformerAbstract;

class VarianceOptionTransformer extends TransformerAbstract
{
    /*
    |--------------------------------------------------------------------------
    | Variance Option Transformer
    |--------------------------------------------------------------------------
    |
    | This transformer is responsible to Variance Option Transformation
    |
    */

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $availableIncludes = [
    ];

    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
    ];

    /**
     * Create a new transformer instance.
     *
     * @param ProductVariance $variance
     * @return array
     */
    public function transform(ProductVariance $variance)
    {
        return [
            'id' => $variance->id,
            'shop_id' => $variance->shop_id,
            'variance_id' => $variance->variance_id,
            'title' => $variance->title,
            'price' => $variance->price,

            "created_by" => $variance->created_by,
            "updated_by" => $variance->updated_by,
            "deleted_at" => $variance->deleted_at,
            "created_at" => $variance->created_at,
            "updated_at" => $variance->updated_at
        ];
    }
}