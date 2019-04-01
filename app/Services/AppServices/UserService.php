<?php

namespace App\Services\AppServices;

use App\Jobs\GenerateResizedImageJob;
use App\Models\Repositories\UploadRepository;
use App\Services\Constants\GeneralConstants;
use App\Models\Repositories\UserRepository;
use App\Services\Transformers\UserTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Validator;
use App\Services\Constants\StatusCodes;

class UserService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | User Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling user Activity
    |
    */

    /**
     * @var UserRepository
     */
    protected $_userRepository;


    /**
     * Create a new Service instance.
     *
     * @param UserRepository $userRepository
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();
        $this->_userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return mixed
     */
    public function index($request)
    {
        try {
            $collectionResponse = $this->_userRepository->index($request);

            $collectionObject = $collectionResponse->pull("data");
            if ($request->has("_render")) {
                $resource = new Collection($collectionObject, new UserTransformer(), 'users');
                return $this->_fractal->createData($resource)->toArray();
            }
            $collectionCollection = $collectionObject->getCollection();
            $resource = new Collection($collectionCollection, new UserTransformer(), 'users');
            $resource->setPaginator(new IlluminatePaginatorAdapter($collectionObject));
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return mixed
     */
    public function store($request)
    {

        try {
            $requestObject = $request->all();
            $isValidate = $this->_userCreateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_userRepository->store($request);

            $collectionResponse = $collectionResponse->pull("data");
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.profile_sizes")));
            }
            $resource = new Item($collectionResponse, new UserTransformer(), 'user');
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
            $isValidate = $this->_userUpdateValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_userRepository->update($request, $id);
            $collectionResponse = $collectionResponse->pull("data");
            if (!empty($collectionResponse->upload)) {
                dispatch(new GenerateResizedImageJob($collectionResponse->upload->toArray(), config("custom_config.profile_sizes")));
            }
            $resource = new Item($collectionResponse, new UserTransformer(), 'users');
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return mixed
     */
    public function destroy($id)
    {
        try {
            $collectionResponse = $this->_userRepository->destroy($id);
            $resource = new Item($collectionResponse->pull("data"), new UserTransformer(), 'user');
            return $this->_fractal->createData($resource)->toArray();
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }

    /**
     * Validate a new user instance.
     *
     * @param $request
     * @return \League\Fractal\Resource\Collection
     */
    public function login($request)
    {
        try {
            $requestObject = $request->all();
            $isValidate = $this->_loginValidator($requestObject);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_userRepository->login($request);
            if ($collectionResponse->has("data")) {
                return response()->json(
                    $collectionResponse->pull("data"), StatusCodes::SUCCESS);
            } else {
                return $this->_response->errorUnauthorized($collectionResponse->pull("exception"));
            }
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $request
     * @return \League\Fractal\Resource\Collection
     */
    public function register($request)
    {
        try {
            $request = $request->all();
            $isValidate = $this->_registrationValidator($request);
            if (!empty($isValidate)) {
                return $isValidate;
            }
            $collectionResponse = $this->_userRepository->register($request);
            return response()->json($collectionResponse->pull("data"), StatusCodes::SUCCESS);
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }


    }

    /**
     * this is responsible to sign out from application
     *
     * @param Request $request
     * @return \League\Fractal\Resource\Collection
     */
    public function signOut($request)
    {
        try {
            $collectionResponse = $this->_userRepository->signOut($request);
            return response()->json($collectionResponse->pull("data"), StatusCodes::SUCCESS);
        } catch (\Exception $exception) {
            return $this->logService->exception('Uh-oh! Due Exception code is breaking.', $exception->getMessage());
        }


    }

    /**
     * This function responsible for validating user on registration page.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _registrationValidator(array $request)
    {
        $rules = [
            'user.name' => 'required',
            'user.email' => 'required|email',
            'user.password' => 'required',
            'user.c_password' => 'required|same:user.password',
        ];
        $messages = [
            'user.name.required' => "Uh-oh! name is required.",
            'user.email.required' => "Uh-oh! email is required",
            'user.email.email' => "Uh-oh! email is not correct format.",
            'user.password.required' => "Uh-oh! password is required.",
            'user.c_password.required' => "Uh-oh! retype is required.",
            'user.c_password.same' => "Uh-oh! Password does not match.",
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }

    /**
     * This function responsible for validating user on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _userUpdateValidator(array $request)
    {
        $rules = [
            'user.first_name' => 'required',
            'user.last_name' => 'required',
            'user.email' => 'required|email|unique:users,email,' . $request["user"]["id"],
            'user.phone_number' => 'required',
            'user.role_id' => 'required',
            'user.status' => 'required',

        ];
        $messages = [
            'user.first_name.required' => "Uh-oh! the { first name } is required.",
            'user.last_name.required' => "Uh-oh! the { last name } is required.",
            'user.email.required' => "Uh-oh! the { email } is required",
            'user.email.email' => "Uh-oh! the { email } is not correct format.",
            'user.email.unique' => "Uh-oh! the { email } has already been taken.",
            'user.phone_number.required' => "Uh-oh! the { phone number } required.",
            'user.role_id.required' => "Uh-oh! the { role } is required.",
            'user.status.required' => "Uh-oh! the { status } is required.",

        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }

    /**
     * This function responsible for validating user on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _userCreateValidator(array $request)
    {
        $rules = [
            'user.first_name' => 'required',
            'user.last_name' => 'required',
            'user.email' => 'required|email|unique:users,email',
            'user.phone_number' => 'required',
            'user.role_id' => 'required',
            'user.status' => 'required',

        ];
        $messages = [
            'user.first_name.required' => "Uh-oh! the { first name } is required.",
            'user.last_name.required' => "Uh-oh! the { last name } is required.",
            'user.email.required' => "Uh-oh! the { email } is required",
            'user.email.email' => "Uh-oh! the { email } is not correct format.",
            'user.email.unique' => "Uh-oh! the { email } has already been taken.",
            'user.phone_number.required' => "Uh-oh! the { phone number } required.",
            'user.role_id.required' => "Uh-oh! the { role } is required.",
            'user.status.required' => "Uh-oh! the { status } is required.",

        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
        return null;
    }

    /**
     * This function responsible for validating user on login page.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _loginValidator(array $request)
    {
        $rules = [
            'user.email' => 'required|email',
            'user.password' => 'required',
        ];
        $messages = [
            'user.email.required' => "Uh-oh! the { email } is required",
            'user.email.email' => "Uh-oh! the { email } is not correct format.",
            'user.password.required' => "Uh-oh! the { password } is required."
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            $collection = collect(["errors" => $validator->errors()]);
            return response()->json($collection, StatusCodes::UNCROSSABLE);
        }
        return null;
    }

    /**
     * This function responsible for filter records from Query.
     *
     * @param  $query
     * @param  $request
     * @return Collection
     */
    private function _userFilter($query, $request)
    {

        if ($request->has("name")) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        } else if ($request->has("email")) {
            if ($request->has("role_id")) {
                return $query->where('email', 'like', '%' . $request->email . '%')
                    ->whereHas('roles', function ($query) {
                        $query->where("name", "=", GeneralConstants::SHOP_KEEPER);
                    });
            } else {
                return $query->where('email', '=', $request->email);
            }

        } else if ($request->has("phone_number")) {
            return $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
        } else {
            return $query;
        }
    }
}