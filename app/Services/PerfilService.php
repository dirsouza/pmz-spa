<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;
use App\Models\Perfil;
use Codedge\Fpdf\Fpdf\Fpdf;
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

    public function relatorioPerfil(): ?BinaryFileResponse
    {
        // Criar Relatório
        $fpdf = new Fpdf('P', 'pt', 'A4');
        $fpdf->AddPage();

        // Título do Relatório
        $fpdf->SetFont('Arial', 'B', 18);
        $fpdf->Cell(0, 5, $this->convertText('Relatório de Perfis'), 0, 1, 'C');
        $fpdf->Cell(0, 5, '', 'B', 1, 'C');
        $fpdf->Ln(10);

        // Cabeção do Relatório
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->Cell(80, 20, $this->convertText('Código'), 1, 0, 'C');
        $fpdf->Cell(0, 20, 'Nome', 1, 1, 'L');

        // Linhas do Relatório
        $fpdf->SetFont('Arial', '', 12);
        foreach (Perfil::orderBy('id', 'asc')->get() as $perfil) {
            $fpdf->Cell(80, 20, $perfil->id, 1, 0, 'C');
            $fpdf->Cell(0, 20, $this->convertText($perfil->nome), 1, 1, 'L');
        }

        $fpdf->Output('F', 'RelPerfis.pdf', true);

        return response()->file('RelPerfis.pdf');
    }

    private function convertText(string $text): ?string
    {
        return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
    }
}
