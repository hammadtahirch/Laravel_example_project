<?php

namespace App\Services\Transformers;

use App\Models\Eloquent\Collection;
use App\Models\Eloquent\ProductVariance;
use App\Models\Eloquent\ProductVarianceOption;
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
     * @param ProductVariance $option
     * @return array
     */
    public function transform(ProductVarianceOption $option)
    {
        return [
            'id' => $option->id,
            'variance_id' => $option->variance_id,
            'title' => $option->title,
            'price' => $option->price / 100,

            "created_by" => $option->created_by,
            "updated_by" => $option->updated_by,
            "deleted_at" => $option->deleted_at,
            "created_at" => $option->created_at,
            "updated_at" => $option->updated_at
        ];
    }
}