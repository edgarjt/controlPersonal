<?php

namespace App;

use App\Models\CatRoleModel;
use App\Models\WorkPositionModel;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'first_surname',
        'last_surname',
        'nacimiento',
        'curp',
        'rfc',
        'street',
        'betweenStreet',
        'city',
        'cp',
        'genero',
        'date',
        'dep',
        'depa',
        'cargo',
        'email',
        'phone',
        'boss',
        'password',
        'theme',
        'role_id',
        'state'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role() {
        return $this->belongsTo(CatRoleModel::class, 'role_id');
    }
}
