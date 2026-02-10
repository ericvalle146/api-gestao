<?php

declare(strict_types=1);

namespace App\Action\Solicitacao;

use App\Models\Solicitacao;
use App\Models\User;

class FetchSolicitacao
{
    public function handle(string $id, User $user): Solicitacao
    {
        $query = Solicitacao::query();

        if (! $user->hasRole('admin')) {
            $query->where(function ($q) use ($user) {
                $q->where('solicitante_id', $user->id)
                    ->orWhere('avaliador_id', $user->id)
                    ->orWhere('aprovador_id', $user->id);
            });
        }

        return $query->findOrFail($id);
    }
}
