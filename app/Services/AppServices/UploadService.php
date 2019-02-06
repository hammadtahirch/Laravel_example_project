<?php

namespace App\Services\AppServices;

use App\Models\Eloquent\Upload;
use App\Models\Repositories\CollectionRepository;
use App\Models\Repositories\UploadRepository;
use Faker\Provider\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadService extends BaseService
{
    /*
    |--------------------------------------------------------------------------
    | Upload Service
    |--------------------------------------------------------------------------
    |
    | This Service is responsible for handling Upload Activity
    |
    */

    /**
     * @var CollectionRepository
     */
    protected $_uploadRepository;

    /**
     * Create a new Service instance.
     *
     * @param UploadRepository $uploadRepository
     * @return void
     */
    public function __construct(UploadRepository $uploadRepository)
    {
        $this->_uploadRepository = $uploadRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param $request
     * @return mixed
     */
    public function storeImage($request)
    {
        $requestObject = $request->all();
        if (strlen($requestObject["dataUrl"]) > 128) {

            list($mime, $data) = explode(';', $requestObject["dataUrl"]);
            list(, $data) = explode(',', $data);
            $data = base64_decode($data);

            $mime = explode(':', $mime)[1];
            $ext = explode('/', $mime)[1];
            $name = Str::uuid()->toString();
            $relative_path = public_path() . "/uploads/images/";
            $absolute_path = secure_asset("uploads/images");

            if (!File::exists($relative_path)) {
                File::makeDirectory(public_path() . '/' . "uploads/images/", $mode = 0777, true, true);
            }
            if (file_put_contents($relative_path . $name . '.' . $ext, $data) > 0) {
                return [
                    "name" => $name,
                    "relative_path" => $relative_path . $name . '.' . $ext,
                    "absolute_path" => $absolute_path . '/' . $name . '.' . $ext,
                    "extension" => $ext
                ];
            } else {
                return ["status" => false, "message" => "Whoops! File is too big."];
            }

        } else {
            return ["status" => false, "message" => "Whoops! File is too big."];
        }

    }

    /**
     * this function get image form serve and convert image into different sizes.
     *
     * @param array $imagePayload
     * @param array $sizePayload
     */
    public function resizeDifferentResolution(array $imagePayload, array $sizePayload)
    {
        ini_set('memory_limit', '64M');
        foreach ($sizePayload as $index => $size) {
            try {
                \Intervention\Image\Facades\Image::make($imagePayload["relative_path"])
                    ->resize(explode("X", $size)[0], null, function ($constraint) {
                        $constraint->aspectRatio();
                    })
                    ->save(public_path("/uploads/images") . "/" . $imagePayload["name"] . "_w" . explode("X", $size)[0] . "." . $imagePayload["extension"]);

            } catch (\Exception $exception) {
                echo $exception->getMessage();
            }
        }
    }
}