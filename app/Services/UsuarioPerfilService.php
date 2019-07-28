<?php

namespace App\Services;

use App\Models\User;

class UsuarioPerfilService
{
    public function attachUsuarioPerfil(User $usuario, array $perfis): void
    {
        $usuario->perfis()->sync($perfis);
    }

    public function detachUsuarioPerfil(User $usuario): void
    {
        $usuario->perfis()->detach();
    }
}
