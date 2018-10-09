<?php
/**
 * Created by PhpStorm.
 * User: hammadtahir
 * Date: 2018-08-03
 * Time: 10:34 AM
 */

namespace App\Services\TransformerServices;

use App\Models\User;
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
     *
     * @return array
     */
    public function transform(User $user)
    {

        return [
            'id' => (int)$user->id,
            'name' => (string)$user->name,
            'email' => (string)$user->email,
            'phone_number' => (string)$user->phone_number,
            'role_id' => (int)$user->role_id,
            'status' => (bool)$user->status,
            'role' => $user->roles[0],
            "created_by" => $user->created_by,
            "updated_by" => $user->updated_by,
            "deleted_at" => $user->deleted_at,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at
        ];
    }
}