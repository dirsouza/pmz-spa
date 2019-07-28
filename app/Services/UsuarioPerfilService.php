<?php

namespace App\Services;

use App\Models\User;
use App\Models\Perfil;

class UsuarioPerfilService
{
    public function attachUsuarioPerfil(User $usuario, array $perfis): void
    {
        $usuario->perfis()->sync($perfis);
    }

    public function attachPerfilUsuario(Perfil $perfil, array $usuarios): void
    {
        $perfil->usuarios()->sync($usuarios);
    }

    public function detachUsuarioPerfil(User $usuario): void
    {
        $usuario->perfis()->detach();
    }

    public function detachPerfilUsuario(Perfil $perfil): void
    {
        $perfil->usuarios()->detach();
    }
}
