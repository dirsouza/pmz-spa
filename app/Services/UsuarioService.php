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
            return response()->json(User::all(), 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
