<?php

namespace App\Services\AppServices;

use App\Services\Transformers\CustomJsonSerializer;
use EllipseSynergie\ApiResponse\Laravel\Response;
use Illuminate\Http\Request;
use League\Fractal\Manager;

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
     * @var Manager
     */
    protected $_fractal;

    /**
     * @var Response
     */
    protected $_response;

    /**
     * @var \Illuminate\Foundation\Application|mixed
     */
    protected $logService;

    /**
     * Create a new Service instance.
     *
     * @return void
     */
    public function __construct()
    {

        $request = app(Request::class);
        $this->_response = app(Response::class);
        $this->logService = app(LogService::class);
        $this->_fractal = new Manager();
        $this->_fractal->setSerializer(new CustomJsonSerializer());
        if (!empty($request->get("include")))
            $this->_fractal->parseIncludes($request->get("include"));
    }

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