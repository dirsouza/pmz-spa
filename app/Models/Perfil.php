<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    protected $table = 'perfis';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuarios_perfis', 'perfil_id', 'usuario_id');
    }
}
