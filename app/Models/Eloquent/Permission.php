<?php

namespace App\Models\Eloquent;

use App\Services\ConstantServices\GeneralConstants;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Zizaco\Entrust\EntrustPermission;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class Permission extends EntrustPermission
{
    use EntrustUserTrait {
        restore as private restoreA;
    }
    use SoftDeletes {
        restore as private restoreB;
    }

    protected $fillable = [
        "id",
        "name",
        "display_name",
        "description",
        "created_at",
        "updated_at"
    ];


    public function roles()
    {
        return $this->belongsToMany('App\Models\Eloquent\Role', 'permission_role', 'permission_id', 'role_id')
            ->select('id', "name", "display_name", "description");
    }

    public function detachRoles($id)
    {
        DB::table('permission_role')->where('role_id', '<>', GeneralConstants::SUPPER_ADMIN_ID)->where(["permission_id" => $id])->delete();
        return $this;
    }

    public function restore()
    {
        $this->restoreA();
        $this->restoreB();
    }

    /**
     *
     * boot
     *
     * @Override
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!empty(Auth::user())) {
                $model->created_by = Auth::user()->id;
            }
        });
        static::updating(function ($model) {
            if (!empty(Auth::user())) {
                $model->updated_by = Auth::user()->id;
            }
        });
        static::deleting(function ($model) {
            if (!empty(Auth::user())) {
                self::deleted_by($model);
            }
        });
    }

    public static function deleted_by($model)
    {
        $model->deleted_by = Auth::user()->id;
        $model->save();
    }
}
