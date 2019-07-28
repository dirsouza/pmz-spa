<?php

namespace App\Services;

use App\Models\Aparelho;
use Illuminate\Http\JsonResponse;

class AparelhoService
{
    public function getListaAparelhos(): ?JsonResponse
    {
        try {
            $aparelhos = Aparelho::with([
                'usuarios' => function ($query) {
                    $query->orderBy('perfil_id', 'asc');
                }
            ])->orderBy('id', 'asc')->get();

            return response()->json($aparelhos, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
