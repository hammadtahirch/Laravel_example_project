<?php

namespace App\Services\TransformerServices;

use App\Models\User;
use League\Fractal;

class ValidationTransformer extends Fractal\TransformerAbstract
{
    /*
    |--------------------------------------------------------------------------
    | Validation Transformer
    |--------------------------------------------------------------------------
    |
    | This transformer is responsible to  transform validation errors
    |
    */

    /**
     * Create a new transformer instance.
     *
     * @param array $validation
     *
     * @return array
     */
    public function transform($validation)
    {
//        $hammad = $validation->merge(["status" => 401]);
        return [
            "staus"=> "hammad"
        ];
    }
}