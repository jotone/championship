<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'level'
    ];

    /**
     * Related role permissions
     *
     * @return HasMany
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class, 'role_id', 'id');
    }

    /**
     * Role related users
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Extend model behavior
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            $model->permissions()->get()->each(fn($entity) => $entity->delete());
            $model->users()->get()->each(fn($entity) => $entity->update(['role_id' => null]));
        });
    }
}
