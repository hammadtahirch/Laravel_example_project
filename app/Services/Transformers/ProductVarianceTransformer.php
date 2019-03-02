<?php

namespace App\Services\Transformers;

use App\Models\Eloquent\Collection;
use App\Models\Eloquent\ProductVariance;
use App\Models\Eloquent\Shop;
use App\Models\Eloquent\User;
use League\Fractal\TransformerAbstract;

class ProductVarianceTransformer extends TransformerAbstract
{
    /*
    |--------------------------------------------------------------------------
    | Collection Transformer
    |--------------------------------------------------------------------------
    |
    | This transformer is responsible to Collection Transformation
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
            'title' => $variance->title,
            'description' => $variance->description,
            'product_id' => $variance->product_id,
            'max_permitted' => $variance->max_permitted,
            'min_permitted' => $variance->min_permitted,

            "created_by" => $variance->created_by,
            "updated_by" => $variance->updated_by,
            "deleted_at" => $variance->deleted_at,
            "created_at" => $variance->created_at,
            "updated_at" => $variance->updated_at
        ];
    }
}