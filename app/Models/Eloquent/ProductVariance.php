<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class ProductVariance
 * @package App\Models\Eloquent
 */
class ProductVariance extends Model
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
     * @var string
     */
    protected $table = "product_variances";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'title',
        'product_id',
        'description',
        'max_permitted',
        'min_permitted',
        'description',
    ];

    /**
     * Create a has one relation with Role.
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function product_variance_option()
    {
        return $this->hasMany('App\Models\Eloquent\ProductVarianceOption', 'variance_id', 'id');
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
