<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class Shop
 * @package App\Models\Eloquent
 */
class Shop extends Model
{
    /**
     * @trait
     */
    use SoftDeletes;

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'user_id',
        'title',
        'description',
        'address',
        'city',
        'province',
        'country',
        'portal_code',
        'latitude',
        'longitude',
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
     * relation function between 2 models (Collection & Upload)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function upload()
    {
        return $this->hasOne('App\Models\Eloquent\Upload', 'shop_id', 'id');
    }

    /**
     * Create a has one relation with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne('App\Models\Eloquent\User', 'id', 'user_id');
    }

    /**
     * Create a has many relation with user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function shop_time_slot()
    {
        return $this->hasMany('App\Models\Eloquent\ShopTimeSlot', 'shop_id', 'id');
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
    public static function set_model_id($model)
    {
        $model->{$model->getKeyName()} = Str::uuid()->toString();
    }
    public static function deleted_by($model)
    {
        $model->deleted_by = Auth::user()->id;
        $model->save();
    }

}
