<?php

namespace App\Models\Eloquent;

use Illuminate\Support\Facades\Auth;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{

    protected $fillable = [
        "id",
        "name",
        "display_name",
        "description",
        "created_at",
        "updated_at"
    ];

    public function permission()
    {
        return $this->belongsToMany('App\Models\Eloquent\Permission', 'permission_role', 'role_id', 'permission_id');
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
