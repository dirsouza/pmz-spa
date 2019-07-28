<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AparelhoService;

class AparelhoController extends Controller
{
    private $aparelhoService;

    public function __construct()
    {
        $this->aparelhoService = App()->make(AparelhoService::class);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->aparelhoService->getListaAparelhos();
    }

    /**
     * Create the form for creating a new resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param integer $id
     * @return \Illuminate\Http\Response
     */
    public function delete(int $id)
    {
        //
    }
}
