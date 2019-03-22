<?php

namespace App\Models\Repositories;

use App\Models\Eloquent\Shop;
use App\Services\AppServices\UploadService;
use Carbon\Carbon;
use EllipseSynergie\ApiResponse\Contracts\Response;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Validator;

class ShopRepository
{
    /*
    |--------------------------------------------------------------------------
    | Shop Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling shop Activity
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
     *
     * @param $request
     * @return Collection $_collection
     */
    public function index($request)
    {
        try {
            $shopPagination = Shop::query()
                ->with(['user', 'shop_time_slot', 'upload'])
                ->paginate(10);

            $this->_collection->put("data", $shopPagination);
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Collection $_collection
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['shop'];
        try {
            $shopObject = new Shop($requestObject);
            $shopObject->save();
            if (!empty($shopObject->id)) {

                $this->generateShopTimings($shopObject);

                //upload image
                $request->request->add(["shop_id" => $shopObject->id, "dataUrl" => $request->get("shop")["dataUrl"]]);
                $imagePayload = $this->_uploadService->storeImage($request);

                $request->request->add(["upload" => [
                    'name' => $imagePayload["name"],
                    'relative_path' => $imagePayload["relative_path"],
                    'storage_url' => $imagePayload["storage_url"],
                    'shop_id' => $shopObject->id,
                    'extension' => $imagePayload["extension"]]
                ]);
                $this->_uploadRepository->store($request);

                //upload image
                $shopObject = $shopObject
                    ->with(['user', 'shop_time_slot', 'upload'])
                    ->where(["id" => $shopObject->id])
                    ->first();
            }

            $this->_collection->put("data", $shopObject);

        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! exception contact to admin",
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
     * @return Collection $_collection
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['shop'];
        try {
            $shopObject = Shop::find($id);
            if ($shopObject->update($requestObject)) {

                if (!empty($request->get('shop')["dataUrl"])) {

                    if (!empty($request->get("shop")["upload"])) {
                        $this->_uploadRepository->destroy($request->get("shop")["upload"]["id"]);
                    }

                    $request->request->add(["shop_id" => $id, "dataUrl" => $request->get("shop")["dataUrl"]]);
                    $imagePayload = $this->_uploadService->storeImage($request);

                    $request->request->add(["upload" => [
                        'name' => $imagePayload["name"],
                        'relative_path' => $imagePayload["relative_path"],
                        'storage_url' => $imagePayload["storage_url"],
                        'shop_id' => $id,
                        'extension' => $imagePayload["extension"]
                    ]]);
                    $this->_uploadRepository->store($request);

                }
                $shopObject = $shopObject
                    ->with(['user', 'shop_time_slot', 'upload'])
                    ->first();
            }

            $this->_collection->put("data", $shopObject);
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! exception contact to admin",
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
     * @return Collection $_collection
     */
    public function destroy($id)
    {
        try {

            $shopObject = Shop::find($id);
            if (!$shopObject) {
                $this->_collection->put("not_found", ['message' => 'User not found.']);
            }
            if ($shopObject->delete()) {
                $shopObject = $shopObject
                    ->with(['user', 'shop_time_slot', 'upload'])
                    ->first();
                $this->_collection->put("data", $shopObject);
            } else {
                $this->_collection->put("exception", ['message' => 'Internal server error user not deleted']);
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! Query exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! exception contact to admin",
                    "query_exception" => $exception
                ]
            );
        }
        return $this->_collection;
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
     * @param  Model $query
     * @return Collection
     */
    private function generateShopTimings($query)
    {
        $timeSlotStack = [];
        for ($i = 1; $i <= 7; $i++) {
            $tempArray = [
                'id' => Str::uuid()->toString(),
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