<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'key',
        'value',
        'data_type',
        'section',
        'caption',
        'position'
    ];

    /**
     * Get settings value
     * @return bool|mixed
     */
    public function getConvertedValueAttribute()
    {
        switch ($this->attributes['data_type']) {
            case 'boolean':
                return (bool)(int)$this->attributes['value'];
            case 'array':
                return json_decode($this->attributes['value']);
            case 'timestamp':
                return Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['value']);
            default:
                return $this->attributes['value'];
        }
    }

    /**
     * Set setting value
     * @param $value
     */
    public function setValueAttribute($value)
    {
        $this->attributes['data_type'] = gettype($value);
        if ($this->attributes['data_type'] === 'object' && $value instanceof Carbon) {
            $this->attributes['data_type'] = 'timestamp';
            $value = $value->format('Y-m-d H:i:s');
        }
        $this->attributes['value'] = is_array($value) ? json_encode($value) : $value;
    }
}
