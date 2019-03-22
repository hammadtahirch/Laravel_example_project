<?php

namespace App\Models\Repositories;

use App\Models\Eloquent\Permission;
use App\Models\Eloquent\Role;
use App\Services\Constants\GeneralConstants;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Validator;

class PermissionRepository
{
    /*
    |--------------------------------------------------------------------------
    | Permission Repository
    |--------------------------------------------------------------------------
    |
    | This Repository is responsible for handling Permission
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
     * @return Collection $collection
     */
    public function index($request)
    {
        try {
            $permissionPagination = Permission::query()
                ->with(
                    [
                        'roles' => function ($query) {
                            $query->where('name', '<>', GeneralConstants::SUPPER_ADMIN);
                        }
                    ]
                )
                ->orderBy("created_by", "DESC")
                ->paginate(10);

            $this->_collection->put("data", $permissionPagination);
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception]);
        }
        return $this->_collection;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return Collection $collection
     */
    public function store($request)
    {
        $requestObject = $request->all();
        try {

            $permissionObject = new Permission();
            $permissionObject->name = $requestObject['permission']['name'];
            $permissionObject->display_name = $requestObject['permission']['display_name'];
            $permissionObject->description = $requestObject['permission']['description'];

            $permissionObject->save();
            if (!empty($permissionObject->id)) {
                DB::table('permission_role')->insert(["permission_id" => $permissionObject->id, "role_id" => GeneralConstants::SUPPER_ADMIN_ID]);
                $permissionObject = $permissionObject->where(["id" => $requestObject->id])->first();
                $this->_collection->put("data", $permissionObject);
            }
        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception]);
        }

        return $this->_collection;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return Collection $collection
     */
    public function update($request, $id)
    {
        $requestObject = $request->all();
        try {
            $permissionObject = Permission::find($id);
            $permissionObject->name = $requestObject['permission']['name'];
            $permissionObject->display_name = $requestObject['permission']['display_name'];
            $permissionObject->description = $requestObject['permission']['description'];

            $permissionObject->update($permissionObject->toArray());

            DB::table('permission_role')->where(["permission_id" => $id])->delete();
            foreach ($requestObject['permission']['roles'] as $index => $role) {
                DB::table('permission_role')
                    ->insert(["permission_id" => $id, "role_id" => $role['id']]);
            }
            $permissionObject = $permissionObject->where(["id" => $id])->with(
                [
                    'roles' => function ($query) {
                        $query->where('name', '<>', GeneralConstants::SUPPER_ADMIN);
                    }
                ]
            )->first();
            $this->_collection->put("data", $permissionObject);


        } catch (QueryException $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception]);
        } catch (\Exception $exception) {
            $this->_collection->put("exception",
                [
                    "message" => "Uh-oh! query exception contact to admin",
                    "query_exception" => $exception]);
        }
        return $this->_collection;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Collection $collection
     */
    public function destroy($id)
    {
        try {

            $permissionObject = Permission::find($id);
            if (!$permissionObject) {
                $this->_collection->put("not_found", ['message' => 'Permission not found.']);
            }
            if ($permissionObject->delete()) {
                $this->_collection->put("data", $permissionObject->first());
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
}