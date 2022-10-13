<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomPage extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'url',
        'editable',
        'enabled',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'text'
    ];

    public $casts = [
        'editable' => 'boolean',
        'enabled ' => 'boolean',
    ];
}
