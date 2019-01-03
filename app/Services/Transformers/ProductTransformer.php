<?php

namespace App\Services\Transformers;

use App\Models\Eloquent\Product;
use League\Fractal\TransformerAbstract;

;

class ProductTransformer extends TransformerAbstract
{
    /*
    |--------------------------------------------------------------------------
    | Product Transformer
    |--------------------------------------------------------------------------
    |
    | This transformer is responsible to Product Transformation
    |
    */

    /**
     * Create a new transformer instance.
     *
     * @param Product $product
     *
     * @return array
     */
    public function transform(Product $product)
    {

        return [
            'id' => $product->id,
            'shop_id' => $product->shop_id,
            'title' => $product->title,
            'description' => $product->description,
            'image' => $product->image,
            'price' => $product->price,
            'is_published' => $product->is_published,
            'published_date' => $product->published_date,
            'status' => $product->status,
            "created_by" => $product->created_by,
            "updated_by" => $product->updated_by,
            "deleted_at" => $product->deleted_at,
            "created_at" => $product->created_at,
            "updated_at" => $product->updated_at
        ];
    }
}