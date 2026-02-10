<?php

declare(strict_types=1);

namespace App\Action\Solicitacao;

use App\DTOs\Solicitacao\UpdateSolicitacaoDTO;
use App\Models\Solicitacao;
use App\Models\User;

class UpdateSolicitacao
{
    public function __construct(
        private FetchSolicitacao $fetchSolicitacao
    ) {}

    public function handle(string $id, UpdateSolicitacaoDTO $dto, User $user): Solicitacao
    {
        $solicitacao = $this->fetchSolicitacao->handle($id, $user);
        $solicitacao->fill($dto->toArray());
        $solicitacao->save();

        return $solicitacao;
    }
}
