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
class Collection extends Model
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
        'id',
        'title',
        'description',
        'image_url',
    ];

    /**
     * relation function between 2 models (Collection & Upload)
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function upload()
    {
        return $this->hasOne('App\Models\Eloquent\Upload', 'collection_id', 'id');
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
