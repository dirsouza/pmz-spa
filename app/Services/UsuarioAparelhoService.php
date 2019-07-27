<?php

namespace App\Services;

use App\Models\User;

class UsuarioAparelhoService
{
    public function detachUsuarioAparelho(User $usuario): void
    {
        $usuario->aparelhos()->detach();
    }
}
