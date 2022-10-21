<?php

namespace App\Models;

use App\Traits\ModelThumbsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumSection extends Model
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
        'images',
        'pinned',
        'position'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'images' => 'array',
        'pinned' => 'boolean',
    ];

    /**
     * Section related author
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Forum section messages
     *
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(ForumMessage::class, 'section_id', 'id');
    }

    /**
     * Extend model behavior
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // remove forum images
            unlink(public_path($model->img_url));
            foreach ($model->thumbs as $thumb) {
                unlink(public_path($thumb));
            }
            if (!empty($model->images)) {
                foreach ($model->images as $img)  {
                    unlink(public_path($img));
                }
            }
            // Remove related messages
            $model->messages()->get()->each(fn($entity) => $entity->delete());
        });
    }
}
