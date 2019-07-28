<?php

namespace App\Services;

use Throwable;
use App\Models\Aparelho;
use Codedge\Fpdf\Fpdf\Fpdf;
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

    public function relatorioAparalho()
    {
        // Criar Relatório
        $fpdf = new Fpdf('L', 'pt', 'A4');
        $fpdf->AddPage();

        // Título do Relatório
        $fpdf->SetFont('Arial', 'B', 18);
        $fpdf->Cell(0, 5, $this->convertText('Relatório de Aparelhos'), 0, 1, 'C');
        $fpdf->Cell(0, 5, '', 'B', 1, 'C');
        $fpdf->Ln(10);

        // Cabeção do Relatório
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->Cell(80, 20, $this->convertText('Código'), 1, 0, 'C');
        $fpdf->Cell(0, 20, $this->convertText('Descrição'), 1, 1, 'L');

        // Linhas do Relatório
        $fpdf->SetFont('Arial', '', 12);
        foreach (Aparelho::orderBy('id', 'asc')->get() as $aparelho) {
            $fpdf->Cell(80, 20, $aparelho->codigo, 1, 0, 'C');
            $fpdf->Cell(0, 20, $this->convertText($aparelho->descricao), 1, 1, 'L');
        }

        $fpdf->Output('F', 'RelAparelhos.pdf', true);

        return response()->file('RelAparelhos.pdf');
    }

    private function convertText(string $text)
    {
        return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
    }
}
