<?php

namespace App\Services\AppServices;

use App\Models\Repositories\CollectionRepository;
use App\Models\Repositories\EmailTemplateRepository;
use App\Services\Transformers\CollectionTransformer;
use App\Services\Transformers\CustomJsonSerializer;
use App\Services\Transformers\TemplateTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use Validator;
use App\Services\Constants\StatusCodes;

class EmailTemplateService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Email template Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling email template Activity
    |
    */

    /**
     * @var EmailTemplateRepository
     */
    protected $_templateRepository;

    /**
     * Create a new Service instance.
     *
     * @param EmailTemplateRepository $emailTemplateRepository
     * @return void
     */
    public function __construct(EmailTemplateRepository $emailTemplateRepository)
    {
        parent::__construct();
        $this->_templateRepository = $emailTemplateRepository;

    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return array []
     */
    public function index($request)
    {
        $templateResponse = $this->_templateRepository->index($request);
        if ($templateResponse->has("data")) {
            $templateObject = $templateResponse->pull("data");
            $templateCollection = $templateObject->getCollection();
            $resource = new Collection($templateCollection, new TemplateTransformer(), 'templates');
            $resource->setPaginator(new IlluminatePaginatorAdapter($templateObject));
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($templateResponse->pull("exception"));
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return  mixed
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $isValidate = $this->_templateCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $templateResponse = $this->_templateRepository->store($request);
        if ($templateResponse->has("data")) {

            $resource = new Item($templateResponse->pull("data"), new TemplateTransformer(), 'template');
            return $this->_fractal->createData($resource)->toArray();
        } else {
            return $this->_response->errorInternalError($templateResponse->pull("exception"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return mixed
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        $isValidate = $this->_templateUpdateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }

        $templateResponse = $this->_templateRepository->update($request, $id);
        if ($templateResponse->has("data")) {
            $resource = new Item($templateResponse->pull("data"), new TemplateTransformer(), 'template');
            return $this->_fractal->createData($resource)->toArray();

        } else {
            return $this->_response->errorInternalError($templateResponse->pull("exception"));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $templateResponse = $this->_templateRepository->destroy($id);
        if ($templateResponse->has("data")) {
            return $this->_response->withItem($templateResponse->pull("data"), new TemplateTransformer(), 'template');
        } elseif ($templateResponse->has("not_found")) {
            return $this->_response->errorNotFound($templateResponse->pull("not_found"));
        } else {
            return $this->_response->errorInternalError($templateResponse->pull("exception"));
        }
    }

    /**
     * This function responsible for validating template on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _templateUpdateValidator(array $request)
    {
        $rules = [
            'template.key' => 'required|unique:email_templates,key,' . $request["template"]["id"],
            'template.subject' => 'required|unique:email_templates,subject,' . $request["template"]["id"],
            'template.from_email' => 'required|email',
            'template.from_name' => 'required',
            'template.email_body' => 'required',
        ];
        $messages = [
            'template.key.required' => "Whoops! the { key } is required.",
            'template.key.unique' => "Whoops! the { key } has already been taken.",
            'template.subject.required' => "Whoops! the { subject } is required.",
            'template.subject.unique' => "Whoops! the { subject }  has already exist.",
            'template.from_email.required' => "Whoops! the { from email } is required.",
            'template.from_email.email' => "Whoops! the { from email } is wrong.",
            'template.from_name.required' => "Whoops! the { from name } is required.",
            'template.email_body.required' => "Whoops! the { email body } is required.",

        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }

    /**
     * This function responsible for validating template on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _templateCreateValidator(array $request)
    {
        $rules = [
            'template.key' => 'required|unique:email_templates,key',
            'template.subject' => 'required|unique:email_templates,subject',
            'template.from_email' => 'required|email',
            'template.from_name' => 'required',
            'template.email_body' => 'required',
        ];
        $messages = [
            'template.key.required' => "Whoops! the { key } is required.",
            'template.key.unique' => "Whoops! the { key } has already been taken.",
            'template.subject.required' => "Whoops! the { subject } is required.",
            'template.subject.unique' => "Whoops! the { subject }  has already exist.",
            'template.from_email.required' => "Whoops! the { from email } is required.",
            'template.from_email.email' => "Whoops! the { from email } is wrong.",
            'template.from_name.required' => "Whoops! the { from name } is required.",
            'template.email_body.required' => "Whoops! the { email body } is required.",

        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }
}