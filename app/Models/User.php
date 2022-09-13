<?php

namespace App\Models;

use App\Traits\ModelThumbsTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasFactory, ModelThumbsTrait, Notifiable;

    protected $settings_key = 'user_img';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'img_url',
        'role_id',
        'email_verified_at',
        'last_activity'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_activity'     => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * Get the User role permissions
     *
     * @return array
     */
    public function getPermissionsAttribute(): array
    {
        return $this->role->permissions()->get()->keyBy('controller')->toArray();
    }

    /**
     * Set email value
     *
     * @param string $value
     */
    public function setEmailAttribute(string $value)
    {
        $this->attributes['email'] = mb_strtolower($value);
    }

    /**
     * Set password value
     *
     * @param string|null $value
     */
    public function setPasswordAttribute(?string $value)
    {
        $this->attributes['password'] = !empty($value) ? bcrypt($value) : '';
    }

    /**
     * Related email verification
     *
     * @return HasOne
     */
    public function emailVerification(): HasOne
    {
        return $this->hasOne(VerifiedEmail::class, 'email', 'email');
    }

    /**
     * User login history
     *
     * @return HasMany
     */
    public function loginHistory(): HasMany
    {
        return $this->hasMany(LoginHistory::class);
    }

    /**
     * User password reset entity
     *
     * @return HasOne
     */
    public function passwordReset(): HasOne
    {
        return $this->hasOne(PasswordReset::class, 'email', 'email');
    }

    /**
     * User role
     *
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Extend model behavior
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            TestingEntity::where(['entity' => self::class, 'entity_id' => $model->id])->delete();
        });
    }
}
