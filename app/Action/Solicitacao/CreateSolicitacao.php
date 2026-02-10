<?php

declare(strict_types=1);

namespace App\Action\Solicitacao;

use App\DTOs\Solicitacao\CreateSolicitacaoDTO;
use App\Models\Solicitacao;
use App\Models\User;

class CreateSolicitacao
{
    public function handle(CreateSolicitacaoDTO $dto, User $user)
    {
        return Solicitacao::create([
            'titulo' => $dto->titulo,
            'justificativa' => $dto->justificativa,
            'tipo' => $dto->tipo->value,
            'solicitante_id' => $user->id,
        ]);
    }
}
