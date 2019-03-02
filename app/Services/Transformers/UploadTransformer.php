<?php

namespace App\Services\Transformers;

use App\Models\Eloquent\Upload;
use League\Fractal\TransformerAbstract;

class UploadTransformer extends TransformerAbstract
{
    /*
    |--------------------------------------------------------------------------
    | Upload Transformer
    |--------------------------------------------------------------------------
    |
    | This transformer is responsible to upload Transformation
    |
    */

    /**
     * Create a new transformer instance.
     *
     * @param Upload $upload
     * @return array
     */
    public function transform(Upload $upload)
    {
        return [
            'id' => $upload->id,
            'name' => $upload->name,
            'relative_path' => $upload->relative_path,
            'storage_url' => $upload->storage_url,
            'extension' => $upload->extension,

            "created_by" => $upload->created_by,
            "updated_by" => $upload->updated_by,
            "deleted_at" => $upload->deleted_at,
            "created_at" => $upload->created_at,
            "updated_at" => $upload->updated_at
        ];
    }


}