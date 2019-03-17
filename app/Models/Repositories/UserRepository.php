<?php

namespace App\Models\Repositories;

use App\Models\Eloquent\User;
use App\Services\AppServices\UploadService;
use App\Services\Constants\GeneralConstants;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Str;
use Predis\Client;
use Validator;

class UserRepository
{
    /*
    |--------------------------------------------------------------------------
    | User Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling user Activity
    |
    */

    /**
     * @var Collection
     */
    protected $_collection;

    /**
     * @var UploadService
     */
    protected $_uploadService;

    /**
     * @var UploadRepository
     */
    protected $_uploadRepository;

    /**
     * Create a new Service instance.
     *
     * @param UploadService $uploadService
     * @param UploadRepository $uploadRepository
     * @return void
     */
    public function __construct(UploadService $uploadService, UploadRepository $uploadRepository)
    {
        $this->_collection = new Collection();
        $this->_uploadService = $uploadService;
        $this->_uploadRepository = $uploadRepository;
    }

    /**
     * Display a listing of the resource.
     * @param $request
     * @return Collection _collection
     */
    public function index($request)
    {
        try {
            $userObject = User::query();
            $userObject = $this->_userFilter($userObject, $request);
            $userObject = $userObject->whereHas('roles', function ($query) {
                $query->where("name", "<>", GeneralConstants::SUPPER_ADMIN);
            })
                ->with(["upload"])
                ->orderBy('created_at', 'DESC');

            if ($request->has("_render")) {
                $this->_collection->put("data", $userObject->get());
            } else {
                $this->_collection->put("data", $userObject->paginate(10));
            }

        } catch (QueryException $exception) {
            $this->_collection->put("exception", [
                "message" => "Oops! query exception contact to admin",
                "query_exception" => $exception
            ]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception", [
                "message" => "Oops! exception contact to admin",
                "query_exception" => $exception
            ]);
        }
        return $this->_collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Collection _collection
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['user'];
        try {

            $userObject = new User();
            $userObject->name = $requestObject['first_name'] . " " . $requestObject['last_name'];
            $userObject->email = $requestObject['email'];
            $userObject->password = Str::random(32);
            $userObject->phone_number = $requestObject['phone_number'];
            $userObject->role_id = $requestObject['role_id'];
            $userObject->status = (boolean)$requestObject['status'];

            $userObject->save();
            if (!empty($userObject->id)) {
                DB::table('role_user')->insert(["user_id" => $userObject->id, "role_id" => $requestObject['role_id']]);

                //upload image
                $request->request->add(["user_id" => $userObject->id, "dataUrl" => $request->get("user")["dataUrl"]]);
                $imagePayload = $this->_uploadService->storeImage($request);

                $request->request->add(["upload" => [
                    'name' => $imagePayload["name"],
                    'relative_path' => $imagePayload["relative_path"],
                    'storage_url' => $imagePayload["storage_url"],
                    'user_id' => $userObject->id,
                    'extension' => $imagePayload["extension"]
                ]]);
                $this->_uploadRepository->store($request);

                //upload image
            }


            $this->_collection->put("data", $userObject->with(["roles", "upload"])->where("id", "=", $userObject->id)->first());
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Collection
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['user'];
        try {
            $userObject = User::find($id);
            $userObject->name = $requestObject['first_name'] . " " . $requestObject['last_name'];
            $userObject->email = $requestObject['email'];
            $userObject->phone_number = $requestObject['phone_number'];
            $userObject->role_id = $requestObject['role_id'];
            $userObject->status = (boolean)$requestObject['status'];

            if ($userObject->update($userObject->toArray())) {
                DB::table('role_user')->where(["user_id" => $userObject->id])
                    ->update(["role_id" => $requestObject['role_id']]);

                if (!empty($request->get('user')["dataUrl"])) {

                    if (!empty($request->get("user")["upload"])) {
                        $this->_uploadRepository->destroy($request->get("user")["upload"]["id"]);
                    }
                    $request->request->add(["user_id" => $id, "dataUrl" => $request->get("user")["dataUrl"]]);
                    $imagePayload = $this->_uploadService->storeImage($request);

                    $request->request->add(["upload" => [
                        'name' => $imagePayload["name"],
                        'relative_path' => $imagePayload["relative_path"],
                        'storage_url' => $imagePayload["storage_url"],
                        'user_id' => $id,
                        'extension' => $imagePayload["extension"]
                    ]]);
                    $this->_uploadRepository->store($request);

                }

            }
            $this->_collection->put("data", $userObject->where(["id" => $id])->with(["roles", "upload"])->first());
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Collection
     */
    public function destroy($id)
    {
        try {
            $userObject = User::find($id);
            if (!$userObject) {
                $this->_collection->put("not_found", ['message' => 'User not found.']);
            }
            if ($userObject->delete()) {
                $this->_collection->put("data", $userObject->where(["id" => $id])->with(["roles", "upload"])->first());
            } else {
                $this->_collection->put("exception", ['message' => 'Internal server error user not deleted']);
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! Query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;
    }

    /**
     * Validate a new user instance.
     *
     * @param $request
     *
     * @return Collection _collection
     */
    public function login($request)
    {
        $request = $request->all();
        $request = $request['user'];
        if (Auth::attempt(['email' => $request['email'], 'password' => $request['password'] . "_" . strtolower($request['email'])])) {
            $user = Auth::user();
            $this->_collection->put("data",
                [
                    'success' => [
                        "token" => $user->createToken(env('ACCESS_TOKEN_APP_NAME'))->accessToken,
                        "user" => $user
                    ]
                ]);
        } else {
            $this->_collection->put("exception", "Whoop! the { email } or { password } you’ve entered doesn’t match any account");
        }
        return $this->_collection;
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param $request
     *
     * @return Collection _collection
     */
    public function register($request)
    {
        $request = $request->all();
        $request = $request['user'];
        $request['password'] = bcrypt($request['password'] . "_" . strtolower($request['email']));
        try {
            $user = User::create($request);
            $this->_collection->put("data",
                [
                    "token" => $user->createToken(env('ACCESS_TOKEN_APP_NAME'))->accessToken,
                    "user" => $user
                ]
            );
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Oops! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;
    }

    /**
     * this is responsible to sign out from application
     *
     * @param $request
     *
     * @return Collection
     */
    public function signOut($request)
    {
        try {
            $signOut = $request->user()->token()->revoke();
            if ($signOut) {
                $this->_collection->put("data",
                    [
                        "message" => 'Sign Out successfully.'
                    ]
                );
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception", ["query_exception" => $exception]);
        }
        return $this->_collection;
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