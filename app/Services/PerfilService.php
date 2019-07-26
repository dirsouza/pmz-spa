<?php

namespace App\Services;

use App\Models\Perfil;
use Illuminate\Support\Collection;

class PerfilService
{
    /**
     * Retorna todos os Perfis
     * @return Collection|null
     */
    public function getPerfis(): ?Collection
    {
        return Perfil::all();
    }
}
