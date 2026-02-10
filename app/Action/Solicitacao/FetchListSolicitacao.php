<?php

declare(strict_types=1);

namespace App\Action\Solicitacao;

use App\DTOs\Common\PaginationDTO;
use App\Models\Solicitacao;
use App\Models\User;
use App\Support\Pagination;

class FetchListSolicitacao
{
    public function handle(PaginationDTO $dto, User $user)
    {
        $query = Solicitacao::query();

        if (! $user->hasRole('admin')) {
            $query->where(function ($q) use ($user) {
                $q->where('solicitante_id', $user->id)
                    ->orWhere('avaliador_id', $user->id)
                    ->orWhere('aprovador_id', $user->id);
            });
        }

        return Pagination::apply($query, $dto);
    }
}
