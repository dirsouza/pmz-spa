<?php

namespace App\Services;

use App\Models\Perfil;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class PerfilService
{
    private $usuarioPerfilService;

    public function __construct()
    {
        $this->usuarioPerfilService = App()->make(UsuarioPerfilService::class);
    }

    public function getListaPerfis(): ?JsonResponse
    {
        try {
            $perfis = Perfil::with('usuarios')
                ->orderBy('id', 'asc')->get();

            return response()->json($perfis, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function storePerfil(array $dados): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            $perfil = Perfil::create([
                'nome' => $dados['nome']
            ]);

            $this->usuarioPerfilService->attachPerfilUsuario($perfil, $dados['usuarios'] ?? []);

            DB::commit();

            return response()->json($perfil, 200);
        } catch (Throwable $e) {
            DB::rollback();

            return response()->json($e->getMessage(), 500);
        }
    }

    public function updatePerfil(int $id, array $dados): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            $perfil = Perfil::find($id);

            $perfil->update([
                'nome' => $dados['nome']
            ]);

            $this->usuarioPerfilService->attachPerfilUsuario($perfil, $dados['usuarios'] ?? []);

            DB::commit();

            return response()->json($perfil, 200);
        } catch (Throwable $e) {
            DB::rollback();

            return response()->json($e->getMessage(), 500);
        }
    }

    public function deletePerfil(int $id): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            $perfil = Perfil::find($id);

            $this->usuarioPerfilService->detachPerfilUsuario($perfil);

            $perfil->delete();

            DB::commit();

            return response()->json($perfil, 200);
        } catch (Throwable $e) {
            DB::rollback();

            return response()->json($e->getMessage(), 500);
        }
    }
}
