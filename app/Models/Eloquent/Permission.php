<?php

namespace App\Models\Eloquent;

use App\Services\Constants\GeneralConstants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Permission extends Model
{
    /**
     * @trait
     */
    use SoftDeletes;

    /**
     * @var
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        "id",
        "name",
        "display_name",
        "description",
        "created_at",
        "updated_at"
    ];

    /**
     * Create belongs to many relation with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Eloquent\Role', 'permission_role', 'permission_id', 'role_id')
            ->select('id', "name", "display_name", "description");
    }

    /**
     * Remove Roles from permission role table
     */
    public function detachRoles($id)
    {
        DB::table('permission_role')->where('role_id', '<>', GeneralConstants::SUPPER_ADMIN_ID)->where(["permission_id" => $id])->delete();
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
            self::set_model_id($model);
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

    /**
     * set model id attribute.
     *
     * @param $model
     */
    public static function set_model_id($model)
    {
        $model->{$model->getKeyName()} = Str::uuid()->toString();
    }

    /**
     * set deleted by attribute.
     *
     * @param $model
     */
    public static function deleted_by($model)
    {
        $model->deleted_by = Auth::user()->id;
        $model->save();
    }
}
