<?php
/**
 * Created by PhpStorm.
 * User: hammadtahir
 * Date: 2018-08-03
 * Time: 10:34 AM
 */

namespace App\Services\TransformerServices;

use App\Models\Role;
use League\Fractal;

class RoleTransformer extends Fractal\TransformerAbstract
{
    /*
    |--------------------------------------------------------------------------
    | Role Transformer
    |--------------------------------------------------------------------------
    |
    | This transformer is responsible to User Transformation
    |
    */

    /**
     * Create a new transformer instance.
     *
     * @param Role $role
     *
     * @return array
     */
    public function transform(Role $role)
    {
        return [
            'id' => (int)$role->id,
            'name' => (string)$role->name,
            'display_name' => (string)$role->display_name,
            'description' => (string)$role->description,
        ];
    }
}