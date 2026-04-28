<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'dni',
        'active',
        'lastname',
        'phone',
        'google_id'
    ];
    protected function name():Attribute
    {
        return Attribute::set(
                fn(string $value): string => strtoupper(trim($value))
        );
    }
    protected function lastname():Attribute
    {
        return Attribute::set(
                fn(string $value): string => strtoupper(trim($value))
        );
    }
    protected function email(): Attribute
    {
        return Attribute::set(
                fn (string $value): string => strtoupper(trim($value)),
        );
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function orders()
    {
        return $this->hasMany(\App\Models\Order::class);
    }
    public function vehicles()
    {
        return $this->hasMany(\App\Models\Vehicle::class);
    }
    public function reservations()
    {
        return $this->hasMany(\App\Models\Reservation::class);
    }
    protected function dashboardTitle(): Attribute
    {
        return Attribute::make(
            get: function () {
                if ($this->hasRole('ADMINISTRADOR')) {
                    return __('Panel de Administración');
                }
                if ($this->hasRole('USUARIO')) {
                    return __('Mi Panel de Atenciones');
                }
                return __('Mi Panel de Reservas');
            },
        );
    }
     public function getStatusActionConfig(): object
    {
        $isActive = (bool) $this->active;

        return (object) [
            'isActive'    => $isActive,
            'color'       => $isActive ? 'bg-red-600 hover:bg-red-700' : 'bg-blue-600 hover:bg-blue-700',
            'btnText'     => $isActive ? 'Desactivar' : 'Activar',
            'swalTitle'   => $isActive ? '¿Desactivar usuario?' : '¿Activar usuario?',
            'swalConfirm' => $isActive ? 'Sí, Desactivar' : 'Sí, Activar',
            'confirmColor'=> $isActive ? '#d33' : '#16a34a',
            'icon'        => $isActive ? 'warning' : 'question',
        ];
    }
}
