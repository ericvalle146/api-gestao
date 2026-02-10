<?php

declare(strict_types=1);

namespace App\Action\Solicitacao;

use App\Models\User;

class DeleteSolicitacao
{
    public function __construct(
        private FetchSolicitacao $fetchSolicitacao
    ) {}

    public function handle(string $id, User $user): void
    {
        $solicitacao = $this->fetchSolicitacao->handle($id, $user);
        $solicitacao->delete();
    }
}
