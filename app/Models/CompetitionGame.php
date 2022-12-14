<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionGame extends Model
{
    protected $table = 'competition_group_games';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'host_team',
        'guest_team',
        'entity',
        'start_at',
        'place',
        'score',
        'accept'
    ];

    public $casts = [
        'score'    => 'array',
        'start_at' => 'datetime',
        'accept'   => 'boolean'
    ];

    /**
     * Related competition group
     *
     * @return BelongsTo
     */
    public function group()
    {
        return $this->belongsTo(CompetitionGroup::class, 'group_id', 'id');
    }

    /**
     * Related team
     *
     * @return BelongsTo
     */
    public function hostTeam()
    {
        return $this->belongsTo($this->attributes['entity'], 'host_team', 'id');
    }

    /**
     * Related team
     *
     * @return BelongsTo
     */
    public function guestTeam()
    {
        return $this->belongsTo($this->attributes['entity'], 'guest_team', 'id');
    }
}