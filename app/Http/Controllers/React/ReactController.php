<?php

namespace App\Http\Controllers\React;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ReactController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | React Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling views for react js app.
    |
    */

    /**
     * This method responsible to render the admin portal view
     * for React js.
     *
     * @return View
     */
    public function adminPortal()
    {
        return \view('app');
    }
}
