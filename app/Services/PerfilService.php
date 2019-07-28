<?php

namespace App\Services;

use App\Models\Perfil;
use Throwable;
use Illuminate\Http\JsonResponse;

class PerfilService
{
    public function getListaPerfis(): ?JsonResponse
    {
        try {
            $perfis = Perfil::with([
                'usuarios' => function ($query) {
                    $query->orderBy('perfil_id', 'asc');
                }
            ])->orderBy('id', 'asc')->get();

            return response()->json($perfis, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
