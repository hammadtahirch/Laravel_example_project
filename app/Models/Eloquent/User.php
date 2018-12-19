<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{

    use HasApiTokens,
        Notifiable;
    use EntrustUserTrait {
        restore as private restoreA;
    }
    use SoftDeletes {
        restore as private restoreB;
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'role_id',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Create a has one relation with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\belongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany('App\Models\Eloquent\Role', 'role_user', 'user_id', 'role_id');
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
