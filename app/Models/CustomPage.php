<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    protected $table = 'custom_pages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'slug',
        'editable',
        'enabled',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'content',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'editable' => 'boolean',
        'enabled'  => 'boolean',
    ];
}
