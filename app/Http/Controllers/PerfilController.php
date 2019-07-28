<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PerfilService;

class PerfilController extends Controller
{
    private $perfilService;

    public function __construct()
    {
        $this->perfilService = App()->make(PerfilService::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->perfilService->getListaPerfis();
    }

    /**
     * Create the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->perfilService->storePerfil($request->all());
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
        return $this->perfilService->updatePerfil($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        return $this->perfilService->deletePerfil($id);
    }

    public function relatorio()
    {
        return $this->aparelhoService->relatorioPerfil();
    }
}
