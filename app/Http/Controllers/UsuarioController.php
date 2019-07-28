<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UsuarioService;

class UsuarioController extends Controller
{
    private $usuarioService;

    public function __construct()
    {
        $this->usuarioService = App()->make(UsuarioService::class);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->usuarioService->getListaUsuarios();
    }

    /**
     * Create the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->usuarioService->storeUsuario($request->all());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  integer $id
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, Request $request)
    {
        return $this->usuarioService->updateUsuario($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        return $this->usuarioService->deleteUsuario($id);
    }

    public function relatorio()
    {
        return $this->usuarioService->relatorioUsuario();
    }
}
