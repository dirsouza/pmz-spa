<?php

namespace App\Services;

use App\Models\Aparelho;
use Throwable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class AparelhoService
{
    private $usuarioAparelhoService;

    public function __construct()
    {
        $this->usuarioAparelhoService = App()->make(UsuarioAparelhoService::class);
    }

    public function getListaAparelhos(): ?JsonResponse
    {
        try {
            $aparelhos = Aparelho::with('usuarios')
                ->orderBy('codigo', 'asc')->get();

            return response()->json($aparelhos, 200);
        } catch (Throwable $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function storeAparelho(array $dados): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            $aparelho = Aparelho::create([
                'codigo' => $dados['codigo'],
                'descricao' => $dados['descricao']
            ]);

            $this->usuarioAparelhoService->attachAparelhoUsuario($aparelho, $dados['usuarios'] ?? []);

            DB::commit();

            return response()->json($aparelho, 200);
        } catch (Throwable $e) {
            DB::rollback();

            return response()->json($e->getMessage(), 500);
        }
    }

    public function updateAparelho(int $id, array $dados): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            $aparelho = Aparelho::find($id);

            $aparelho->update([
                'codigo' => $dados['codigo'],
                'descricao' => $dados['descricao']
            ]);

            $this->usuarioAparelhoService->attachAparelhoUsuario($aparelho, $dados['usuarios'] ?? []);

            DB::commit();

            return response()->json($aparelho, 200);
        } catch (Throwable $e) {
            DB::rollback();

            return response()->json($e->getMessage(), 500);
        }
    }

    public function deleteAparelho(int $id): ?JsonResponse
    {
        try {
            DB::beginTransaction();

            $aparelho = Aparelho::find($id);

            $this->usuarioAparelhoService->detachAparelhoUsuario($aparelho);

            $aparelho->delete();

            DB::commit();

            return response()->json($aparelho, 200);
        } catch (Throwable $e) {
            DB::rollback();

            return response()->json($e->getMessage(), 500);
        }
    }

    public function relatorioAparelho()
    { }
}
