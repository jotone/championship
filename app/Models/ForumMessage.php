<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class ForumMessage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'topic_id',
        'author_id',
        'parent_id',
        'message',
        'pinned',
        'banned',
        'edited_by',
        'edit_reason',
        'edited_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'pinned'     => 'boolean',
        'banned'     => 'boolean',
        'edited_at'  => 'datetime',
        'deleted_at' => 'datetime'
    ];

    /**
     * Message author
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    /**
     * Editor entity
     *
     * @return BelongsTo
     */
    public function editedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'edited_by', 'id');
    }

    /**
     * Related sub comment
     *
     * @return HasMany
     */
    public function subComment(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }


    /**
     * Get message inheritors with comments
     *
     * @return HasMany
     */
    public function subComments(): HasMany
    {
        return $this->subComment()->with('subComments');
    }

    /**
     * Related topic
     *
     * @return BelongsTo
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(ForumTopic::class, 'topic_id', 'id');
    }
}
