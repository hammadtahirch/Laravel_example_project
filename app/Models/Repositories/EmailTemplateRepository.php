<?php

namespace App\Models\Repositories;

use App\Models\Eloquent\EmailTemplate;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use PharIo\Manifest\Email;
use Validator;

class EmailTemplateRepository
{
    /*
    |--------------------------------------------------------------------------
    | Email template Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling email template Activity
    |
    */

    /**
     * @var Collection
     */
    protected $_collection;

    /**
     * Create a new Service instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->_collection = new Collection();

    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return Collection $_collection
     * @throws \Exception
     */
    public function index($request)
    {
        try {
            $collectionPagination = EmailTemplate::query();

            $this->_emailTemplateFilter($collectionPagination, $request);
            if ($request->has("_render")) {
                $this->_collection->put("data", $collectionPagination->get());
            } else {
                $this->_collection->put("data", $collectionPagination->paginate(10));
            }
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
     * @param  \Illuminate\Http\Request $request
     * @return Collection $_collection
     * @throws \Exception
     */
    public function store($request)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['template'];
        try {
            $collectionObject = new EmailTemplate($requestObject);
            $collectionObject->save();
            if (!empty($collectionObject->id)) {
                $collectionObject = $collectionObject
                    ->where(["id" => $collectionObject->id])
                    ->first();
            }

            $this->_collection->put("data", $collectionObject);

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
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Collection $_collection
     * @throws \Exception
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        $requestObject = $requestObject['template'];
        try {
            $collectionObject = EmailTemplate::find($id);
            if ($collectionObject->update($requestObject)) {
                $this->_collection->put("data", $collectionObject);
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
     * @param  int $id
     * @return Collection $_collection
     * @throws \Exception
     */
    public function destroy($id)
    {
        try {

            $collectionObject = EmailTemplate::find($id);
            if (!$collectionObject) {
                throw new \Exception('Template not found');
            }
            if ($collectionObject->delete()) {
                $this->_collection->put("data", $collectionObject);
            } else {
                throw new \Exception('Internal server error Template not deleted');
            }
        } catch (QueryException $exception) {
            throw new \Exception($exception);
        } catch (\Exception $exception) {
            throw new \Exception($exception);
        }
        return $this->_collection;
    }

    /**
     * This function responsible for filter records from Query.
     *
     * @param  array $request
     * @return Collection
     */
    private function _emailTemplateFilter($query, $request)
    {

        if ($request->has("key")) {
            return $query->where('key', '=', $request->key);
        } elseif ($request->has("subject")) {
            return $query->where('subject', '=', $request->subject);
        } elseif ($request->has("is_enabled")) {
            return $query->where('is_enabled', '=', $request->is_enabled);
        } else {
            return $query;
        }
    }
}