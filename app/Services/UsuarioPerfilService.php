<?php

namespace App\Services;

use App\Models\Usuario;
use App\Models\Perfil;

class UsuarioPerfilService
{
    public function attachUsuarioPerfil(Usuario $usuario, array $perfis): void
    {
        $usuario->perfis()->sync($perfis);
    }

    public function attachPerfilUsuario(Perfil $perfil, array $usuarios): void
    {
        $perfil->usuarios()->sync($usuarios);
    }

    public function detachUsuarioPerfil(Usuario $usuario): void
    {
        $usuario->perfis()->detach();
    }

    public function detachPerfilUsuario(Perfil $perfil): void
    {
        $perfil->usuarios()->detach();
    }
}
