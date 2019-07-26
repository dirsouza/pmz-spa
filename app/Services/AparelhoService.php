<?php

namespace App\Services;

use App\Models\Aparelho;
use Illuminate\Support\Collection;

class AparelhoService
{
    /**
     * Retorna todos os Aparelhos
     * @return Collection|null
     */
    public function getAparelhos(): ?Collection
    {
        return Aparelho::all();
    }
}
