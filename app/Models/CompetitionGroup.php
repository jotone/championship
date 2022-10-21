<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class CompetitionGroup extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'competition_id',
        'position',
        'stage',
        'games_number'
    ];

    /**
     * Related competition
     *
     * @return BelongsTo
     */
    public function competition(): BelongsTo
    {
        return $this->belongsTo(Competition::class, 'competition_id', 'id');
    }

    /**
     * Related games
     *
     * @return HasMany
     */
    public function games(): HasMany
    {
        return $this->hasMany(CompetitionGame::class, 'group_id', 'id')->orderBy('start_at');
    }

    /**
     * Related teams
     *
     * @return HasMany
     */
    public function teams(): HasMany
    {
        return $this->hasMany(CompetitionTeam::class, 'group_id', 'id');
    }

    /**
     * Extend model behavior
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Remove teams
            $model->teams()->get()->each(fn($entity) => $entity->delete());
            // Remove games
            $model->games()->get()->each(fn($entity) => $entity->delete());
        });
    }
}
