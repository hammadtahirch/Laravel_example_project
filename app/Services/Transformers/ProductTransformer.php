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
        'upload'
    ];

    /**
     * Create a new transformer instance.
     *
     * @param Product $product
     * @return array
     */
    public function transform(Product $product)
    {

        return [
            'id' => $product->id,
            'shop_id' => $product->shop_id,
            'title' => $product->title,
            'description' => $product->description,
            'price' => $product->price / 100,
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

    /**
     * Include Upload
     *
     * @param Product $product
     * @return \League\Fractal\Resource\Item
     */
    public function includeUpload(Product $product)
    {
        $uploadObject = $product->upload;
        if (!empty($uploadObject))
            return $this->item($uploadObject, new UploadTransformer(), false);
        else
            return null;
    }
}