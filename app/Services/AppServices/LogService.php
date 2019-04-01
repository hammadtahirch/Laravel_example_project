<?php

namespace App\Services\AppServices;

use App\Jobs\GenerateResizedImageJob;
use App\Models\Eloquent\Log;
use App\Models\Repositories\CollectionRepository;
use App\Models\Repositories\UploadRepository;
use App\Services\Transformers\CollectionTransformer;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use Validator;
use App\Services\Constants\StatusCodes;

class LogService
{
    /*
    |--------------------------------------------------------------------------
    | Log Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Log Activity
    |
    */


    /**
     * This function handle the informative Logs.
     *
     * @param $message
     * @param null $system_message
     * @return null
     */
    public function info($message, $system_message = null)
    {
        $loggingObject = new Log([
            "log_type" => "INFO",
            "message" => $message,
            "system_message" => $system_message
        ]);
        $loggingObject->save();

        if (!empty($loggingObject->id)) {
            return $loggingObject->where(["id" => $loggingObject->id])->first();
        }

        return null;

    }

    /**
     * This function handle the Error Logs like .
     *
     * @param $message
     * @param null $system_message
     * @return null
     */
    public function error($message, $system_message = null)
    {
        $loggingObject = new Log([
            "log_type" => "ERROR",
            "message" => $message,
            "system_message" => $system_message
        ]);
        $loggingObject->save();

        if (!empty($loggingObject->id)) {
            return $loggingObject->where(["id" => $loggingObject->id])->first();
        }

        return null;
    }

    /**
     * This function handle the Exceptions Logs like .
     *
     * @param $message
     * @param null $system_message
     * @return null
     */
    public function exception($message, $system_message = null)
    {
        $loggingObject = new Log([
            "log_type" => "EXCEPTION",
            "message" => $message,
            "system_message" => $system_message
        ]);
        $loggingObject->save();

        if (!empty($loggingObject->id)) {
            return $loggingObject->where(["id" => $loggingObject->id])->first();
        }

        return null;
    }

    /**
     * This function handle the success Logs like.
     * mostly it will be use in queued job and system related tasks
     *
     * @param $message
     * @param null $system_message
     * @return null
     */
    public function success($message, $system_message = null)
    {
        $loggingObject = new Log([
            "log_type" => "SUCCESS",
            "message" => $message,
            "system_message" => $system_message
        ]);
        $loggingObject->save();

        if (!empty($loggingObject->id)) {
            return $loggingObject->where(["id" => $loggingObject->id])->first();
        }

        return null;
    }

    /**
     * This function use to handle the warning.
     *
     * @param $message
     * @param null $system_message
     * @return null
     */
    public function warning($message, $system_message = null)
    {
        $loggingObject = new Log([
            "log_type" => "WARNING",
            "message" => $message,
            "system_message" => $system_message
        ]);
        $loggingObject->save();

        if (!empty($loggingObject->id)) {
            return $loggingObject->where(["id" => $loggingObject->id])->first();
        }

        return null;
    }

    /**
     * This function use to handle code debugging.
     *
     * @param $message
     * @param null $system_message
     * @return null
     */
    public function debugger($message, $system_message = null)
    {
        $loggingObject = new Log([
            "log_type" => "DEBUGGER",
            "message" => $message,
            "system_message" => $system_message
        ]);
        $loggingObject->save();

        if (!empty($loggingObject->id)) {
            return $loggingObject->where(["id" => $loggingObject->id])->first();
        }

        return null;
    }

    /**
     * This is general function it can be use for all purpose.
     *
     * @param $log_type
     * @param $message
     * @param null $system_message
     * @return null
     */
    public function logger($log_type, $message, $system_message = null)
    {
        $loggingObject = new Log([
            "log_type" => $log_type,
            "message" => $message,
            "system_message" => $system_message
        ]);
        $loggingObject->save();

        if (!empty($loggingObject->id)) {
            return $loggingObject->where(["id" => $loggingObject->id])->first();
        }

        return null;
    }
}