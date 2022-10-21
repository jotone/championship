<?php

namespace App\Models;

use App\Traits\ModelThumbsTrait;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use ModelThumbsTrait, Notifiable, SoftDeletes;

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
        'info',
        'role_id',
        'created_by',
        'email_verified_at',
        'last_activity',
        'deleted_at'
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
     * User creator
     *
     * @return BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(self::class, 'created_by', 'id');
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
     * Related forms
     *
     * @return HasMany
     */
    public function forms(): HasMany
    {
        return $this->hasMany(UserForm::class, 'user_id', 'id');
    }

    /**
     * User login history
     *
     * @return HasMany
     */
    public function loginHistory(): HasMany
    {
        return $this->hasMany(LoginHistory::class, 'user_id', 'id');
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
            // Remove user forms
            $model->forms()->get()->each(fn($entity) => $entity->delete());
            // Remove login history
            $model->loginHistory()->get()->each(fn($entity) => $entity->delete());
            // Remove password resets records
            PasswordReset::where('email', $model->email)->get()->each(fn($entity) => $entity->delete());
            // Remove email verification records
            VerifiedEmail::where('email', $model->email)->get()->each(fn($entity) => $entity->delete());
        });
    }
}
