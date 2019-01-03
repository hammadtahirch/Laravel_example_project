<?php

namespace App\Services\AppServices;

use App\Services\Constants\GeneralConstants;
use App\Models\Repositories\UserRepository;
use App\Services\Transformers\UserTransformer;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Support\Collection;
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

    protected $_response;
    protected $_userRepository;

    /**
     * Create a new Service instance.
     *
     * @param Response $response
     * @param UserRepository $userRepository
     *
     * @return void
     */
    public function __construct(Response $response, UserRepository $userRepository)
    {
        $this->_response = $response;
        $this->_userRepository = $userRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return \League\Fractal\Resource\Collection
     */
    public function index($request)
    {
        $collectionResponse = $this->_userRepository->index($request);
        if ($collectionResponse->has("data")) {
            if ($request->has("_render")) {
                return $this->_response
                    ->withCollection($collectionResponse->pull("data"), new UserTransformer, 'users');
            }
            return $this->_response
                ->withPaginator($collectionResponse->pull("data"), new UserTransformer, 'users');
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \League\Fractal\Resource\Collection
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $isValidate = $this->_userCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $collectionResponse = $this->_userRepository->store($request);
        if ($collectionResponse->has("data")) {
            return $this->_response
                ->withItem($collectionResponse->pull("data"), new UserTransformer, 'user');
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \League\Fractal\Resource\Collection
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        $isValidate = $this->_userUpdateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $collectionResponse = $this->_userRepository->update($request, $id);
        if ($collectionResponse->has("data")) {
            return $this->_response
                ->withItem($collectionResponse->pull("data"), new UserTransformer, 'user');
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
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
        $collectionResponse = $this->_userRepository->destroy($id);
        if ($collectionResponse->has("data")) {
            return $this->_response->withItem($collectionResponse->pull("data"), new UserTransformer, 'user');
        } else if ($collectionResponse->has("not_found")) {
            return $this->_response->withItem($collectionResponse->pull("not_found"), new UserTransformer, 'user');
        } else {
            return $this->_response->withItem($collectionResponse->pull("exception"), new UserTransformer, 'user');
        }
    }

    /**
     * Validate a new user instance.
     *
     * @param $request
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function login($request)
    {
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
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $request
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function register($request)
    {
        $request = $request->all();
        $isValidate = $this->_registrationValidator($request);
        if (!empty($isValidate)) {
            return $isValidate;
        }

        $collectionResponse = $this->_userRepository->register($request);
        if ($collectionResponse->has("data")) {
            return response()->json($collectionResponse->pull("data"), StatusCodes::SUCCESS);
        } else {
            return $this->_response->errorInternalError($collectionResponse->pull("exception"));
        }

    }

    /**
     * this is responsible to sign out from application
     *
     * @param Request $request
     *
     * @return \League\Fractal\Resource\Collection
     */
    public function signOut($request)
    {

        $collectionResponse = $this->_userRepository->signOut($request);
        if ($collectionResponse->has("data")) {
            return response()->json($collectionResponse->pull("data"), StatusCodes::SUCCESS);
        } else {
            return response()->json($collectionResponse->pull("exception"), StatusCodes::BAD_REQUEST);
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
            'user.name.required' => "Oops! name is required.",
            'user.email.required' => "Oops! email is required",
            'user.email.email' => "Oops! email is not correct format.",
            'user.password.required' => "Oops! password is required.",
            'user.c_password.required' => "Oops! retype is required.",
            'user.c_password.same' => "Oops! Password does not match.",
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
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
            'user.email' => 'required|email',
            'user.phone_number' => 'required',
            'user.role_id' => 'required',
            'user.status' => 'required',

        ];
        $messages = [
            'user.first_name.required' => "Oops! first name is required.",
            'user.last_name.required' => "Oops! last name is required.",
            'user.email.required' => "Oops! email is required",
            'user.email.email' => "Oops! email is not correct format.",
            'user.phone_number.required' => "Oops! phone number is not correct format.",
            'user.role_id.required' => "Oops! role is not correct format.",
            'user.status.required' => "Oops! status is not correct format.",
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
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
            'user.email' => 'required|email',
            'user.phone_number' => 'required',
            'user.role_id' => 'required',
            'user.status' => 'required',

        ];
        $messages = [
            'user.first_name.required' => "Oops! first name is required.",
            'user.last_name.required' => "Oops! last name is required.",
            'user.email.required' => "Oops! email is required",
            'user.email.email' => "Oops! email is not correct format.",
            'user.phone_number.required' => "Oops! phone number is not correct format.",
            'user.role_id.required' => "Oops! role is not correct format.",
            'user.status.required' => "Oops! status is not correct format.",

        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
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
            'user.email.required' => "Oops! email is required",
            'user.email.email' => "Oops! email is not correct format.",
            'user.password.required' => "Oops! password is required."
        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            $collection = collect(["errors" => $validator->errors()]);
            return response()->json($collection, StatusCodes::UNCROSSABLE);
        }
    }

    /**
     * This function responsible for filter records from Query.
     *
     * @param  array $request
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