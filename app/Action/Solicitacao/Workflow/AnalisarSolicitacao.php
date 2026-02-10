<?php

declare(strict_types=1);

namespace App\Action\Solicitacao\Workflow;

use App\Enums\Solicitacao\SolicitacaoStatus;
use App\Models\Solicitacao;
use App\Models\SolicitacaoTransicao;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AnalisarSolicitacao
{
    public function handle(Solicitacao $solicitacao, User $user): Solicitacao
    {
        if ($solicitacao->avaliador_id && $solicitacao->avaliador_id !== $user->id) {
            throw ValidationException::withMessages(['solicitacao' => 'Apenas o avaliador designado pode analisar.']);
        }

        if (! $solicitacao->status->podeTransitarPara(SolicitacaoStatus::EM_ANALISE)) {
            throw ValidationException::withMessages(['status' => 'Transição inválida.']);
        }

        $statusAnterior = $solicitacao->status;

        $dadosAtualizacao = [
            'status' => SolicitacaoStatus::EM_ANALISE,
        ];

        if (! $solicitacao->avaliador_id) {
            $dadosAtualizacao['avaliador_id'] = $user->id;
        }

        $solicitacao->update($dadosAtualizacao);

        SolicitacaoTransicao::create([
            'solicitacao_id' => $solicitacao->id,
            'status_anterior' => $statusAnterior->value,
            'status_novo' => SolicitacaoStatus::EM_ANALISE->value,
            'comentario' => null,
            'responsavel_id' => $user->id,
        ]);

        return $solicitacao;
    }
}
