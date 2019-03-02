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
        'upload',
        'role',

    ];

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
            "created_by" => $user->created_by,
            "updated_by" => $user->updated_by,
            "deleted_at" => $user->deleted_at,
            "created_at" => $user->created_at,
            "updated_at" => $user->updated_at
        ];
    }

    /**
     * Include Upload
     *
     * @param User $user
     * @return mixed
     */
    public function includeUpload(User $user)
    {
        $uploadObject = $user->upload;
        if (!empty($uploadObject))
            return $this->item($uploadObject, new UploadTransformer(), false);
        else
            return $this->null();
    }

    /**
     * Include roles
     *
     * @param User $user
     * @return mixed
     */
    public function includeRole(User $user)
    {
        $roleObject = $user->roles->first();
        if (!empty($roleObject))
            return $this->item($roleObject, new RoleTransformer(), false);
        else
            return $this->null();
    }
}