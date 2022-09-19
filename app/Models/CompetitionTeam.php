<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompetitionTeam extends Model
{
    use HasFactory;

    protected $table = 'competition_group_teams';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'group_id',
        'team_id',
        'position',
        'games',
        'wins',
        'draws',
        'loses',
        'balls',
        'score',
    ];

    /**
     * Related group
     *
     * @return BelongsTo
     */
    public function group(): BelongsTo
    {
        return $this->belongsTo(CompetitionGroup::class, 'group_id', 'id');
    }

    /**
     * Related team
     *
     * @return BelongsTo
     */
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_id', 'id');
    }
}