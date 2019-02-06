<?php
/**
 * Created by PhpStorm.
 * User: hammadtahir
 * Date: 2018-08-03
 * Time: 10:34 AM
 */

namespace App\Services\Transformers;

use App\Models\Eloquent\Permission;
use League\Fractal;

class PermissionTransformer extends Fractal\TransformerAbstract
{
    /*
    |--------------------------------------------------------------------------
    | Permission Transformer
    |--------------------------------------------------------------------------
    |
    | This transformer is responsible to Permission Transformation
    |
    */

    /**
     * Create a new transformer instance.
     *
     * @param Permission $permission
     * @return array
     */
    public function transform(Permission $permission)
    {
        return [
            'id' => $permission->id,
            'name' => $permission->name,
            'display_name' => $permission->display_name,
            'description' => $permission->description,
            'roles' => $permission->roles
        ];
    }
}