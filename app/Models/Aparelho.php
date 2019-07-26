<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Aparelho extends Model
{
    protected $table = 'aparelhos';
    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'usuarios_aparelhos', 'aparelho_id', 'user_id');
    }
}
