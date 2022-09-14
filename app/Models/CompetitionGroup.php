<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
        'competition_id'
    ];

    /**
     * Related competition
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function competition()
    {
        return $this->belongsTo(Competition::class, 'competition_id', 'id');
    }
}
