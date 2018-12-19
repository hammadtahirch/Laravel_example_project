<?php

namespace App\Services\ControllerRepository;

use App\Models\Eloquent\Product;
use App\Models\Eloquent\Shop;
use App\Services\TransformerServices\CustomJsonSerializer;
use App\Services\TransformerServices\ProductTransformer;
use App\Services\TransformerServices\ShopTransformer;
use App\Services\TransformerServices\UserTransformer;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use League\Fractal\Resource\Item;
use League\Fractal\Serializer\JsonApiSerializer;
use Validator;
use App\Services\ConstantServices\StatusCodes;

class ShopProductsRepository
{
    /*
    |--------------------------------------------------------------------------
    | Shop Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling shop Products Activity
    |
    */

    protected $_roleService = null;
    protected $_response = null;
    protected $_fractal = null;

    /**
     * Create a new Service instance.
     *
     * @param Response $response
     * @return void
     */
    public function __construct(Response $response)
    {
        $this->_response = $response;
        $this->_fractal = new Manager();
        $this->_fractal->setSerializer(new CustomJsonSerializer());

    }

    /**
     * Display a listing of the resource.
     *
     * @return @return array []
     */
    public function index($shop_id,$request)
    {
        try {
            $productPagination = Product::query()
                ->paginate(10);
            $productObject = $productPagination->getCollection();

            $resource = new Collection($productObject, new ProductTransformer(), 'products');
            $resource->setPaginator(new IlluminatePaginatorAdapter($productPagination));
            return $this->_fractal->createData($resource)->toArray();

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
    public function store($shop_id,$request)
    {
        $requestObject = $request->all();
        $isValidate = $this->_shopCreateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $requestObject = $requestObject['shop'];
        try {

            $shopObject = new Shop($requestObject);
            $shopObject->save();
            if ($shopObject->id > 0) {
                $this->generateShopTimings($shopObject);
                $shopObject = $shopObject
                    ->with(
                        [
                            'user' => function ($query) {
                                $query->select("id", "name", "email");
                            },
                            'shop_time_slot' => function ($query) {
                                $query->select('id', 'shop_id', 'day', 'deliver_start_time', 'delivery_end_time', 'change_delivery_date', 'pickup_start_time', 'pickup_end_time', 'change_pickup_date');
                            }
                        ]
                    )
                    ->where(["id" => $shopObject->id])
                    ->first();
            }

            $resource = new Item($shopObject, new ShopTransformer(), 'shop');
            return $this->_fractal->createData($resource)->toArray();

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
            ->withItem('', new UserTransformer, 'user');

    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($shop_id,$id)
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
    public function update($shop_id,$request, $id)
    {
        $requestObject = $request->all();
        $isValidate = $this->_shopUpdateValidator($requestObject);
        if (!empty($isValidate)) {
            return $isValidate;
        }
        $requestObject = $requestObject['shop'];
        try {

            $resource = new Item('', new ShopTransformer(), 'shop');
            return $this->_fractal->createData($resource)->toArray();

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
            ->withItem('', new UserTransformer, 'shop');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($shop_id,$id)
    {
        try {

            $shopObject = Shop::find($id);
            if (!$shopObject) {
                return $this->_response->errorNotFound(['message' => 'User not found.']);
            }
            if ($shopObject->delete()) {
                $shopObject = $shopObject->with(
                    [
                        'user' => function ($query) {
                            $query->select("id", "name", "email");
                        },
                        'shop_time_slot' => function ($query) {
                            $query->select('id', 'shop_id', 'day', 'deliver_start_time', 'delivery_end_time', 'change_delivery_date', 'pickup_start_time', 'pickup_end_time', 'change_pickup_date');
                        }
                    ]
                )->first();
                return $this->_response->withItem($shopObject, new ShopTransformer(), 'user');
            } else {
                return $this->_response->errorInternalError(['message' => 'Internal server error user not deleted']);
            }
            $resource = new Item('', new ShopTransformer(), 'shop');
            return $this->_fractal->createData($resource)->toArray();
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
     * This function responsible for validating shop on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _shopUpdateValidator(array $request)
    {
        $rules = [
            'shop.title' => 'required',
            'shop.user_id' => 'required',
            'shop.address' => 'required',
            'shop.city' => 'required',
            'shop.province' => 'required',
            'shop.country' => 'required',
            'shop.portal_code' => 'required',
            'shop.latitude' => 'required',
            'shop.longitude' => 'required',


        ];
        $messages = [
            'shop.title.required' => "Oops! title is required.",
            'shop.user_id.required' => "Oops! user is required.",
            'shop.address.required' => "Oops! address is required.",
            'shop.city.required' => "Oops! city is required.",
            'shop.province.required' => "Oops! province is required.",
            'shop.country.required' => "Oops! country is required.",
            'shop.portal_code.required' => "Oops! portal_code is required.",
            'shop.latitude.required' => "Oops! latitude is required.",
            'shop.longitude.required' => "Oops! longitude is required.",

        ];
        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }

    /**
     * This function responsible for validating shop on update.
     *
     * @param  array $request
     * @return \League\Fractal\Resource\Collection
     */
    private function _shopCreateValidator(array $request)
    {
        $rules = [
            'shop.title' => 'required',
            'shop.user_id' => 'required',
            'shop.address' => 'required',
            'shop.city' => 'required',
            'shop.province' => 'required',
            'shop.country' => 'required',
            'shop.portal_code' => 'required',
            'shop.latitude' => 'required',
            'shop.longitude' => 'required',


        ];
        $messages = [
            'shop.title.required' => "Oops! title is required.",
            'shop.user_id.required' => "Oops! user is required.",
            'shop.address.required' => "Oops! address is required.",
            'shop.city.required' => "Oops! city is required.",
            'shop.province.required' => "Oops! province is required.",
            'shop.country.required' => "Oops! country is required.",
            'shop.portal_code.required' => "Oops! portal_code is required.",
            'shop.latitude.required' => "Oops! latitude is required.",
            'shop.longitude.required' => "Oops! longitude is required.",

        ];

        $validator = \Illuminate\Support\Facades\Validator::make($request, $rules, $messages);
        if ($validator->fails()) {
            return response()->json(collect(["errors" => $validator->errors()]), StatusCodes::UNCROSSABLE);
        }
    }

    /**
     * This function responsible for filter records from Query.
     *
     * @param  array $request
     * @return Collection
     */
    private function _shopFilter($query, $request)
    {

        if ($request->has("name")) {
            return $query->where('name', 'like', '%' . $request->name . '%');
        } else if ($request->has("email")) {
            return $query->where('email', '=', $request->email);
        } else if ($request->has("phone_number")) {
            return $query->where('phone_number', 'like', '%' . $request->phone_number . '%');
        } else {
            return $query;
        }
    }

    /**
     * This function responsible for filter records from Query.
     *
     * @param  array $request
     * @return Collection
     */
    private function generateShopTimings($query)
    {
        $timeSlotStack = [];
        for ($i = 1; $i <= 7; $i++) {
            $tempArray = [
                'shop_id' => $query->id,
                'day' => $i,
                'deliver_start_time' => "09:00:00",
                'delivery_end_time' => "18:00:00",
                'change_delivery_date' => null,
                'pickup_start_time' => "09:00:00",
                'pickup_end_time' => "18:00:00",
                'change_pickup_date' => null,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
            array_push($timeSlotStack, $tempArray);

        }
        return $query->shop_time_slot()->insert($timeSlotStack);

    }
}