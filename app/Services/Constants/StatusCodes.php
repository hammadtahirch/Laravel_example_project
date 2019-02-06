<?php

namespace App\Services\Constants;

/**
 * Class StatusCodes
 * @package App\Services\Constants
 */
class StatusCodes
{

    /**
     * SUCCESS
     *
     * @var integer
     */
    const SUCCESS = 200;

    /**
     * NOT_FOUND
     *
     * @var integer
     */
    const NOT_FOUND = 404;

    /**
     * UNAUTHORIZED
     *
     * @var integer
     */
    const UNAUTHORIZED = 401;

    /**
     * NOT_FOUND
     *
     * @var integer
     */
    const BAD_REQUEST = 400;

    /**
     * Method_Not_Allowed
     *
     * @var integer
     */
    const METHOD_NOT_ALLOWED = 405;

    /**
     * GONE
     *
     * @var integer
     */
    const GONE = 410;

    /**
     * UNCROSSABLE
     *
     * @var integer
     */
    const UNCROSSABLE = 422;

    /**
     * Internal description
     *
     * @var [type]
     */
    const INTERNAL_SERVER_ERROR = 500;

    /**
     * Internal description
     *
     * @var [type]
     */
    const SERVICES_UNAVAILABLE = 503;


}
