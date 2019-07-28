<?php

namespace App\Services;

use App\Models\User;
use App\Models\Aparelho;

class UsuarioAparelhoService
{
    public function attachAparelhoUsuario(Aparelho $aparelho, array $usuarios): void
    {
        $aparelho->usuarios()->sync($usuarios);
    }

    public function detachAparelhoUsuario(Aparelho $aparelho): void
    {
        $aparelho->usuarios()->detach();
    }

    public function detachUsuarioAparelho(User $usuario): void
    {
        $usuario->aparelhos()->detach();
    }
}
