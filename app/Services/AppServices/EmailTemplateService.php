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
        try {
            $templateResponse = $this->_templateRepository->index($request);
            $templateObject = $templateResponse->pull("data");
            $templateCollection = $templateObject->getCollection();
            $resource = new Collection($templateCollection, new TemplateTransformer(), 'templates');
            $resource->setPaginator(new IlluminatePaginatorAdapter($templateObject));
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        try {
            $requestObject = $request->all();
            $isValidate = $this->_templateCreateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $templateResponse = $this->_templateRepository->store($request);
            $resource = new Item($templateResponse->pull("data"), new TemplateTransformer(), 'template');
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        try {
            $requestObject = $request->all();
            $isValidate = $this->_templateUpdateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }

            $templateResponse = $this->_templateRepository->update($request, $id);
            $resource = new Item($templateResponse->pull("data"), new TemplateTransformer(), 'template');
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
        try {
            $templateResponse = $this->_templateRepository->destroy($id);
            return $this->_response->withItem($templateResponse->pull("data"), new TemplateTransformer(), 'template');
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
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
            'template.key.required' => "Uh-oh! the { key } is required.",
            'template.key.unique' => "Uh-oh! the { key } has already been taken.",
            'template.subject.required' => "Uh-oh! the { subject } is required.",
            'template.subject.unique' => "Uh-oh! the { subject }  has already exist.",
            'template.from_email.required' => "Uh-oh! the { from email } is required.",
            'template.from_email.email' => "Uh-oh! the { from email } is wrong.",
            'template.from_name.required' => "Uh-oh! the { from name } is required.",
            'template.email_body.required' => "Uh-oh! the { email body } is required.",

        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
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
            'template.key.required' => "Uh-oh! the { key } is required.",
            'template.key.unique' => "Uh-oh! the { key } has already been taken.",
            'template.subject.required' => "Uh-oh! the { subject } is required.",
            'template.subject.unique' => "Uh-oh! the { subject }  has already exist.",
            'template.from_email.required' => "Uh-oh! the { from email } is required.",
            'template.from_email.email' => "Uh-oh! the { from email } is wrong.",
            'template.from_name.required' => "Uh-oh! the { from name } is required.",
            'template.email_body.required' => "Uh-oh! the { email body } is required.",

        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }
}