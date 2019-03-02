<?php

namespace App\Services\Transformers;

use App\Models\Eloquent\Collection;
use App\Models\Eloquent\Shop;
use App\Models\Eloquent\User;
use League\Fractal\TransformerAbstract;

class CollectionTransformer extends TransformerAbstract
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
        'upload'
    ];

    /**
     * Create a new transformer instance.
     *
     * @param Collection $collection
     * @return array
     */
    public function transform(Collection $collection)
    {
        return [
            'id' => $collection->id,
            'title' => $collection->title,
            'description' => $collection->description,
            'image' => $collection->description,

            "created_by" => $collection->created_by,
            "updated_by" => $collection->updated_by,
            "deleted_at" => $collection->deleted_at,
            "created_at" => $collection->created_at,
            "updated_at" => $collection->updated_at
        ];
    }

    /**
     * Include Upload
     *
     * @param Collection $collection
     * @return \League\Fractal\Resource\Item
     */
    public function includeUpload(Collection $collection)
    {
        $uploadObject = $collection->upload;
        if (!empty($uploadObject))
            return $this->item($uploadObject, new UploadTransformer(), false);
        else
            return null;
    }
}