<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function aparelhos()
    {
        return $this->belongsToMany(Aparelho::class, 'usuarios_aparelhos', 'usuario_id', 'aparelho_id');
    }

    public function perfis()
    {
        return $this->belongsToMany(Perfil::class, 'usuarios_perfis', 'usuario_id', 'perfil_id');
    }
}
