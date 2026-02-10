<?php

declare(strict_types=1);

namespace App\Action\Solicitacao\Workflow;

use App\Enums\Solicitacao\SolicitacaoStatus;
use App\Models\Solicitacao;
use App\Models\SolicitacaoTransicao;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class CancelarSolicitacao
{
    public function handle(Solicitacao $solicitacao, User $user): Solicitacao
    {
        if ($solicitacao->solicitante_id !== $user->id) {
            throw ValidationException::withMessages(['solicitacao' => 'Apenas o solicitante pode cancelar.']);
        }

        if (! $solicitacao->status->podeTransitarPara(SolicitacaoStatus::CANCELADA)) {
            throw ValidationException::withMessages(['status' => 'Transição inválida.']);
        }

        $statusAnterior = $solicitacao->status;

        $solicitacao->update([
            'status' => SolicitacaoStatus::CANCELADA,
        ]);

        SolicitacaoTransicao::create([
            'solicitacao_id' => $solicitacao->id,
            'status_anterior' => $statusAnterior->value,
            'status_novo' => SolicitacaoStatus::CANCELADA->value,
            'comentario' => null,
            'responsavel_id' => $user->id,
        ]);

        return $solicitacao;
    }
}
