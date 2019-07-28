<?php

namespace App\Services;

use App\Models\User;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class UsuarioService
{
    private $usuarioPerfilService;
    private $usuarioAparelhoService;

    public function __construct()
    {
        $this->usuarioPerfilService = App()->make(UsuarioPerfilService::class);
        $this->usuarioAparelhoService = App()->make(UsuarioAparelhoService::class);
    }

    public function getListaUsuarios(): ?JsonResponse
    {
        try {
            $usuarios = User::with('perfis')
                ->orderBy('id', 'asc')->get();

            return response()->json($usuarios, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function storeUsuario(array $dados): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            $usuario = User::create([
                'name' => $dados['name'],
                'email' => $dados['email'],
                'status' => $dados['status']
            ]);

            $this->usuarioPerfilService->attachUsuarioPerfil($usuario, $dados['perfis'] ?? []);

            DB::commit();

            return response()->json($usuario, 200);
        } catch (Throwable $e) {
            DB::rollback();

            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateUsuario(int $id, array $dados): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            $usuario = User::find($id);

            $usuario->update([
                'name' => $dados['name'],
                'email' => $dados['email'],
                'status' => $dados['status']
            ]);

            $this->usuarioPerfilService->attachUsuarioPerfil($usuario, $dados['perfis'] ?? []);

            DB::commit();

            return response()->json($usuario, 200);
        } catch (Throwable $e) {
            DB::rollback();

            return response()->json($e->getMessage(), 500);
        }
    }

    public function deleteUsuario(int $id): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            $usuario = User::find($id);

            $this->usuarioPerfilService->detachUsuarioPerfil($usuario);
            $this->usuarioAparelhoService->detachUsuarioAparelho($usuario);

            $usuario->delete();

            DB::commit();

            return response()->json($usuario, 200);
        } catch (Throwable $e) {
            DB::rollback();

            return response()->json($e->getMessage(), 500);
        }
    }

    public function relatorioUsuario()
    { }
}
