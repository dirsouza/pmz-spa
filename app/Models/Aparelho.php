<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aparelho extends Model
{
    protected $table = 'aparelhos';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function usuarios()
    {
        return $this->belongsToMany(Usuario::class, 'usuarios_aparelhos', 'aparelho_id', 'usuario_id');
    }
}
