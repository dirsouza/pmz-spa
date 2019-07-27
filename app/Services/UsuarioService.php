<?php

namespace App\Services;

use App\Models\User;
use Throwable;
use Illuminate\Http\JsonResponse;

class UsuarioService
{
    public function getListaUsuarios(): ?JsonResponse
    {
        try {
            $usuarios = User::leftJoin('usuarios_perfis', 'usuarios_perfis.user_id', 'users.id')
                ->leftJoin('perfis', 'perfis.id', 'usuarios_perfis.perfil_id')
                ->select('users.id', 'users.name', 'users.email', 'users.status', 'perfis.nome as perfil')
                ->orderBy('users.id', 'asc')
                ->get();

            return response()->json($usuarios, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
