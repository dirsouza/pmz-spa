<?php

namespace App\Services;

use Throwable;
use App\Models\User;
use Codedge\Fpdf\Fpdf\Fpdf;
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
    {
        // Criar Relatório
        $fpdf = new Fpdf('P', 'pt', 'A4');
        $fpdf->AddPage();

        // Título do Relatório
        $fpdf->SetFont('Arial', 'B', 18);
        $fpdf->Cell(0, 5, $this->convertText('Relatório de Usuários'), 0, 1, 'C');
        $fpdf->Cell(0, 5, '', 'B', 1, 'C');
        $fpdf->Ln(10);

        // Cabeção do Relatório
        $fpdf->SetFont('Arial', 'B', 14);
        $fpdf->Cell(80, 20, $this->convertText('Código'), 1, 0, 'C');
        $fpdf->Cell(208, 20, 'Nome', 1, 0, 'L');
        $fpdf->Cell(200, 20, 'Email', 1, 0, 'L');
        $fpdf->Cell(0, 20, 'Status', 1, 1, 'C');

        // Linhas do Relatório
        $fpdf->SetFont('Arial', '', 12);
        foreach (User::orderBy('id', 'asc')->get() as $user) {
            $fpdf->Cell(80, 20, $user->id, 1, 0, 'C');
            $fpdf->Cell(208, 20, $this->convertText($user->name), 1, 0, 'L');
            $fpdf->Cell(200, 20, $user->email, 1, 0, 'L');
            $fpdf->Cell(0, 20, $user->status ? 'Ativo' : 'Inativo', 1, 1, 'C');
        }

        $fpdf->Output('F', 'RelUsuarios.pdf', true);

        return response()->file('RelUsuarios.pdf');
    }

    private function convertText(string $text)
    {
        return mb_convert_encoding($text, 'ISO-8859-1', 'UTF-8');
    }
}
