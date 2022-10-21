<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class UserForm extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'competition_id',
        'points'
    ];

    /**
     * Related bets
     *
     * @return HasMany
     */
    public function bets(): HasMany
    {
        return $this->hasMany(UserFormBets::class, 'user_form_id', 'id');
    }

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
     * Related user
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Extend model behavior
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Remove related bets
            $model->bets()->get()->each(fn($entity) => $entity->delete());
        });
    }

}
