<?php

namespace App\Services\ControllerRepository;

use App\Models\Eloquent\User;
use App\Services\ConstantServices\GeneralConstants;
use App\Services\TransformerServices\UserTransformer;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;
use App\Services\ConstantServices\StatusCodes;

class UserRepository
{
    /*
    |--------------------------------------------------------------------------
    | User Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling user Activity
    |
    */

    protected $_roleService = null;
    protected $_response = null;

    /**
     * Create a new Service instance.
     *
     * @return void
     */
    public function __construct($response)
    {
        $this->_roleService = new RoleRepository($response);
        $this->_response = $response;

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function index($request)
    {
        try {
            $userObject = User::query();
            $userObject = $this->_userFilter($userObject, $request);
            $userObject = $userObject->whereHas('roles', function ($query) {
                $query->where("name", "<>", GeneralConstants::SUPPER_ADMIN);
            })
                ->orderBy('id', 'DESC');

            if ($request->has("_render")) {
                $userObject = $userObject->get();
                return $this->_response
                    ->withCollection($userObject, new UserTransformer, 'users');
            } else {
                $userObject = $userObject->paginate(10);
                return $this->_response
                    ->withPaginator($userObject, new UserTransformer, 'users');
            }

        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $isValidate = $this->_userCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $requestObject = $requestObject['user'];
        try {
            $userObject = new User();

            $userObject->name = $requestObject['first_name'] . " " . $requestObject['last_name'];
            $userObject->email = $requestObject['email'];
            $userObject->password = Str::random(32);
            $userObject->phone_number = $requestObject['phone_number'];
            $userObject->role_id = (integer)$requestObject['role_id'];
            $userObject->status = (boolean)$requestObject['status'];

            $userObject->save();
            if ($userObject->id > 0) {
                $userObject
                    ->find($userObject->id)
                    ->roles()
                    ->attach($requestObject['role_id']);
            }
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_response
            ->withItem($userObject, new UserTransformer, 'user');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        $isValidate = $this->_userUpdateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $requestObject = $requestObject['user'];
        try {
            $userObject = User::find($id);
            $userObject->name = $requestObject['first_name'] . " " . $requestObject['last_name'];
            $userObject->email = $requestObject['email'];
            $userObject->phone_number = $requestObject['phone_number'];
            $userObject->role_id = (integer)$requestObject['role_id'];
            $userObject->status = (boolean)$requestObject['status'];

            if ($userObject->update($userObject->toArray())) {
                $userObject->roles()->sync([]);
                $userObject->roles()->attach($requestObject['role_id']);
            }
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_response
            ->withItem($userObject, new UserTransformer, 'user');
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
            $userObject = User::find($id);
            if (!$userObject) {
                return $this->_response->errorNotFound(['message' => 'User not found.']);
            }
            if ($userObject->delete()) {
                return $this->_response->withItem($userObject, new UserTransformer, 'user');
            } else {
                return $this->_response->errorInternalError(['message' => 'Internal server error user not deleted']);
            }
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! Query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
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
        $request = $request->all();
        $isValidate = $this->_loginValidator($request);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $request = $request['user'];
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password'] . "_" . strtolower($request['email'])])) {
            $user = Auth::user();
            return response()->json(
                [
                    'success' => collect(
                        [
                            "token" => $user->createToken(env('ACCESS_TOKEN_APP_NAME'))->accessToken,
                            "user" => $user
                        ]
                    )
                ], StatusCodes::SUCCESS);
        } else {
            return $this->_response->errorUnauthorized("username or password is wrong.");
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
        $request = $request['user'];
        $request['password'] = bcrypt($request['password'] . "_" . strtolower($request['email']));
        try {
            $user = User::create($request);
        } catch (QueryException $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            return $this->_response->errorInternalError(
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }

        return response()->json(collect(
            [
                "token" => $user->createToken(env('ACCESS_TOKEN_APP_NAME'))->accessToken,
                "user" => $user
            ]
        ), StatusCodes::SUCCESS);
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
        try {
            $signOut = $request->user()->token()->revoke();
            if ($signOut) {
                return response()->json(collect(
                    [
                        "message" => 'Sign Out successfully.'
                    ]
                ), StatusCodes::SUCCESS);
            }
        } catch (QueryException $exception) {
            return response()->json(collect(["query_exception" => $exception]), StatusCodes::BAD_REQUEST);
        }

    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @return Json Response
     */
    public function details()
    {
        return '';
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