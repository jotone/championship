<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'groups_number',
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
        'start_at' => 'datetime',
        'finish_at' => 'datetime',
    ];

    /**
     * Related competition groups
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function groups()
    {
        return $this->hasMany(CompetitionGroup::class, 'competition_id', 'id');
    }

    /**
     * Extend model behavior
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            TestingEntity::where(['entity' => self::class, 'entity_id' => $model->id])->delete();
            // Remove groups
            $model->groups()->get()->each(fn($entity) => $entity->delete());
        });
    }
}
