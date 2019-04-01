<?php

namespace App\Models\Repositories;

use App\Models\Eloquent\Collect;
use App\Models\Eloquent\Product;

use App\Services\AppServices\UploadService;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Validator;

class ShopProductsRepository
{
    /*
    |--------------------------------------------------------------------------
    | Shop Product Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling shop Products Activity
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
     * @var ShopProductsRepository|UploadRepository
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
     * @param $shop_id
     * @param $request
     * @return Collection $_collection
     * @throws \Exception
     */
    public function index($shop_id, $request)
    {
        try {
            $productPagination = Product::query()
                ->with("upload")
                ->with("product_variance")
                ->with("product_variance.product_variance_option")
                ->with("collect")
                ->with("collect.collection")
                ->where(["shop_id" => $shop_id])
                ->orderBy("created_at", "DESC")
                ->paginate(10);
            $this->_collection->put("data", $productPagination);
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
        return $this->_collection;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  $shop_id
     * @param  \Illuminate\Http\Request $request
     * @return Collection $_collection
     * @throws \Exception
     */
    public function store($shop_id, $request)
    {
        try {
            $requestObject = $request->all();
            $requestObject["product"]["shop_id"] = $shop_id;
            if ($requestObject["product"]["is_published"]) {
                $requestObject["product"]["published_date"] = date("Y-m-d H:i:s");
            }
            $productObject = new Product($requestObject["product"]);
            $productObject->save();
            if ($productObject->id) {

                //upload image
                if (!empty($request->get('product')["dataUrl"])) {

                    $request->request->add(["product_id" => $productObject->id, "dataUrl" => $request->get("product")["dataUrl"]]);
                    $imagePayload = $this->_uploadService->storeImage($request);

                    $request->request->add(["upload" => [
                        'name' => $imagePayload["name"],
                        'relative_path' => $imagePayload["relative_path"],
                        'storage_url' => $imagePayload["storage_url"],
                        'extension' => $imagePayload["extension"],
                        'product_id' => $productObject->id]
                    ]);
                    $this->_uploadRepository->store($request);
                }
                //upload image
                //sync collection
                Collect::updateOrCreate(
                    ['shop_id' => $shop_id, 'product_id' => $productObject->id],
                    ['collection_id' => $requestObject["product"]["collection"]["id"]]
                );
                //sync
                $productObject = $productObject->with('upload')->where(["id" => $productObject->id])->first();
                $this->_collection->put("data", $productObject);
            }
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }

        return $this->_collection;
    }

    /**
     * Display the specified resource.
     *
     * @param  $shop_id
     * @param  $id
     * @return Collection $_collection
     * @throws \Exception
     */
    public function show($shop_id, $id)
    {
        try {
            $productPagination = Product::query()
                ->where(["id" => $id])
                ->with(["product_variance" => function ($query) {
                    $query->with("product_variance_option");
                }, "upload", "collect", "collect.collection"])
                ->first();
            $this->_collection->put("data", $productPagination);
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
        return $this->_collection;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $shop_id
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Collection $_collection
     * @throws \Exception
     */
    public function update($shop_id, $request, $id)
    {
        try {
            $requestObject = $request->all();
            $requestObject = $requestObject;
            if ($requestObject["product"]["is_published"]) {
                $requestObject["product"]["published_date"] = date("Y-m-d H:i:s");
            }
            $productObject = Product::find($id);
            if ($productObject->update($requestObject["product"])) {
                //image upload
                if (!empty($request->get('product')["dataUrl"])) {

                    if (!empty($request->get("product")["upload"])) {
                        $this->_uploadRepository->destroy($request->get("product")["upload"]["id"]);
                    }
                    $request->request->add(["product_id" => $productObject->id, "dataUrl" => $request->get("product")["dataUrl"]]);
                    $imagePayload = $this->_uploadService->storeImage($request);

                    $request->request->add(["upload" => [
                        'name' => $imagePayload["name"],
                        'relative_path' => $imagePayload["relative_path"],
                        'storage_url' => $imagePayload["storage_url"],
                        'product_id' => $productObject->id,
                        'extension' => $imagePayload["extension"]
                    ]]);
                    $this->_uploadRepository->store($request);

                }
                //image upload
                //sync collection
                Collect::updateOrCreate(
                    ['shop_id' => $shop_id, 'product_id' => $id],
                    ['collection_id' => $requestObject["product"]["collection"]["id"]]
                );
                //sync
                $this->_collection->put("data", $productObject->where(["id" => $id])->with(["upload"])->first());
            }
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }

        return $this->_collection;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $shop_id
     * @param  $id
     * @return Collection $_collection
     * @throws \Exception
     */
    public function destroy($shop_id, $id)
    {
        try {
            $productObject = Product::with(["upload"])->find($id);
            if (!$productObject) {
                throw new \Exception('Product not found.');
            } else if ($productObject->delete()) {

                $this->_collection->put("data", $productObject);
            } else {
                throw new \Exception('Internal server error product not deleted');
            }
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }

        return $this->_collection;
    }
}