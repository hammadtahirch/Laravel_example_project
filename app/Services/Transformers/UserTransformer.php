<?php
/**
 * Created by PhpStorm.
 * User: hammadtahir
 * Date: 2018-08-03
 * Time: 10:34 AM
 */

namespace App\Services\Transformers;

use App\Models\Eloquent\User;
use League\Fractal;

class UserTransformer extends Fractal\TransformerAbstract
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
     * @param User $user
     * @return array
     */
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'role_id' => $user->role_id,
            'status' => $user->status,
            'role' => $user->roles[0],
            "created_by" => $user->created_by,
            "updated_by" => $user->updated_by,
            "deleted_at" => $user->deleted_at,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at
        ];
    }
}