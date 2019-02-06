<?php

namespace App\Services\AppServices;

use App\Models\Repositories\AuthRepository;
use App\Services\Transformers\CustomJsonSerializer;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use Validator;

class BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Base Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Base Activity
    |
    */


    /**
     * This function responsible for check object contain paging object or simple collection object
     *
     * @param $object
     * @return boolean true / false
     */
    protected function hasPagingObject($object)
    {
        return ($object instanceof \Illuminate\Pagination\AbstractPaginator);
    }
}