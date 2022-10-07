<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserFormBets extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_form_id',
        'group_id',
        'game_id',
        'scores'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scores' => 'array',
    ];

    /**
     * Related user form
     *
     * @return BelongsTo
     */
    public function userForm(): BelongsTo
    {
        return $this->belongsTo(UserForm::class, 'user_form_id', 'id');
    }

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
     * Related game
     *
     * @return BelongsTo
     */
    public function game(): BelongsTo
    {
        return $this->belongsTo(CompetitionGame::class, 'game_id', 'id');
    }
}

