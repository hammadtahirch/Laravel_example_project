<?php

namespace App\Models\Eloquent;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class Collection
 * @package App\Models\Eloquent
 */
class Log extends Model
{

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
        'id',
        'log_type',
        'message',
        'system_message',
    ];

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
            self::set_model_id($model);
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
}
