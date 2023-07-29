<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'usuario', 'nombre', 'paterno', 'materno', 'ci', 'ci_exp',
        'dir', 'correo', 'fono', 'cel', 'tipo', 'password',
        'foto', 'fecha_registro'
    ];


    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    protected $appends = ['full_name', 'full_ci', 'path_image'];

    public static function getNombreUsuario($nom, $apep)
    {
        //determinando el nombre de usuario inicial del 1er_nombre+apep+tipoUser
        $nombre_user = substr(mb_strtoupper($nom), 0, 1); //inicial 1er_nombre
        $nombre_user .= mb_strtoupper($apep);

        return $nombre_user;
    }
    public function getFullNameAttribute()
    {
        return $this->nombre . ' ' . $this->paterno . ($this->materno != NULL && $this->materno != '' ? ' ' . $this->materno : '');
    }

    public function getFullCiAttribute()
    {
        return $this->ci . ' ' . $this->ci_exp;
    }

    public function getPathImageAttribute()
    {
        if ($this->foto && trim($this->foto) != "") {
            return asset('imgs/users/' . $this->foto);
        }
        return asset('imgs/users/default.png');
    }

    public function notificacions_user()
    {
        return $this->hasMany(NotificacionUser::class, 'user_id');
    }
}
