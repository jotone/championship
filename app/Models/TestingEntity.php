<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TestingEntity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'entity',
        'entity_id'
    ];

    /**
     * Related entity
     *
     * @return BelongsTo
     */
    public function relatedEntity(): BelongsTo
    {
        return $this->belongsTo($this->attributes['entity'], 'entity_id', 'id');
    }
}
