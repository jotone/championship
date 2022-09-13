<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AdminMenu extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'img_url',
        'position',
        'parent_id',
        'is_section'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_section' => 'boolean',
    ];

    /**
     * Menu inheritors
     *
     * @return HasMany
     */
    public function subMenu(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    /**
     * Get menu inheritors with submenus
     *
     * @return HasMany
     */
    public function subMenus(): HasMany
    {
        return $this->subMenu()->with('subMenus');
    }
}
