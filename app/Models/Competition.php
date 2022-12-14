<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{HasMany, HasManyThrough};

class Competition extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'groups_number',
        'rounds',
        'img_url',
        'bg_color',
        'text_color',
        'start_at',
        'finish_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    public $casts = [
        'start_at'  => 'datetime',
        'finish_at' => 'datetime',
    ];

    /**
     * Related competition user forms
     *
     * @return HasMany
     */
    public function userForms()
    {
        return $this->hasMany(UserForm::class, 'competition_id', 'id');
    }

    /**
     * Related games
     *
     * @return HasManyThrough
     */
    public function games()
    {
        return $this->hasManyThrough(
            CompetitionGame::class,
            CompetitionGroup::class,
            'competition_id',
            'group_id',
            'id',
            'id'
        );
    }

    /**
     * Related competition groups
     *
     * @return HasMany
     */
    public function groups(): HasMany
    {
        return $this->hasMany(CompetitionGroup::class, 'competition_id', 'id');
    }

    /**
     * Server queue entities
     * @return HasMany
     */
    public function queueItems(): HasMany
    {
        return $this->hasMany(ServerQueue::class, 'competition_id', 'id');
    }

    /**
     * Related teams
     *
     * @return HasManyThrough
     */
    public function teams(): HasManyThrough
    {
        return $this->hasManyThrough(
            CompetitionTeam::class,
            CompetitionGroup::class,
            'competition_id',
            'group_id',
            'id',
            'id'
        );
    }

    /**
     * Extend model behavior
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Remove groups
            $model->groups()->get()->each(fn($entity) => $entity->delete());
            // Remove user forms
            $model->userForms()->get()->each(fn($entity) => $entity->delete());
            // Remvoe server queue entries
            $model->queueItems()->get()->each(fn($entity) => $entity->delete());
        });
    }
}
