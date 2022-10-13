<?php

namespace App\Models;

use App\Traits\ModelThumbsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    use ModelThumbsTrait;

    protected $settings_key = 'team_img';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'en',
        'ua',
        'country_id',
        'img_url'
    ];

    /**
     * Related country
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Extend model behavior
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // remove team image
            unlink(public_path($model->img_url));
            foreach ($model->thumbs as $thumb) {
                unlink(public_path($thumb));
            }

            TestingEntity::where(['entity' => self::class, 'entity_id' => $model->id])->delete();
        });
    }
}
