<?php

namespace App\Models;

use App\Traits\ModelThumbsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class ForumTopic extends Model
{
    use ModelThumbsTrait;

    protected $settings_key = 'forum_img';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'created_by',
        'img_url',
        'description',
        'text',
        'pinned',
        'position'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pinned' => 'boolean',
    ];

    /**
     * Forum topic related author
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Forum topic messages
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ForumMessage::class, 'topic_id', 'id');
    }

    /**
     * Extend model behavior
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // remove forum image
            self::removeImage($model);
            // Remove related messages
            $model->messages()->get()->each(fn($entity) => $entity->delete());
        });
    }
}
