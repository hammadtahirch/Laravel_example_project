<?php

namespace App\Http\Controllers\Api;

use App\Models\Eloquent\EmailTemplate;
use App\Services\AppServices\EmailTemplateService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class EmailTemplateController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Email template Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling Email templating Activity
    |
    */

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @param EmailTemplateService $emailTemplateService
     * @return array []
     */
    public function index(Request $request, EmailTemplateService $emailTemplateService)
    {
        return $emailTemplateService->index($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  EmailTemplateService $emailTemplateService
     * @return array []
     */
    public function store(Request $request, EmailTemplateService $emailTemplateService)
    {
        return $emailTemplateService->store($request);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  $id
     * @param  EmailTemplateService $emailTemplateService
     * @return array []
     */
    public function update(Request $request, $id, EmailTemplateService $emailTemplateService)
    {
        return $emailTemplateService->update($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  EmailTemplateService $emailTemplateService
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, EmailTemplateService $emailTemplateService)
    {
        return $emailTemplateService->destroy($id);
    }
}
