<?php

declare(strict_types=1);

namespace App\Action\Solicitacao\Workflow;

use App\Enums\Solicitacao\SolicitacaoStatus;
use App\Events\SolicitacaoDecidida;
use App\Models\Solicitacao;
use App\Models\SolicitacaoTransicao;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class AprovarSolicitacao
{
    public function handle(Solicitacao $solicitacao, User $user, string $comentario): Solicitacao
    {
        if ($solicitacao->aprovador_id && $solicitacao->aprovador_id !== $user->id) {
            throw ValidationException::withMessages(['solicitacao' => 'Apenas o aprovador designado pode aprovar.']);
        }

        if (! $solicitacao->status->podeTransitarPara(SolicitacaoStatus::APROVADA)) {
            throw ValidationException::withMessages(['status' => 'Transição inválida.']);
        }

        $statusAnterior = $solicitacao->status;

        $dadosAtualizacao = [
            'status' => SolicitacaoStatus::APROVADA,
            'comentario_decisao' => $comentario,
        ];

        if (! $solicitacao->aprovador_id) {
            $dadosAtualizacao['aprovador_id'] = $user->id;
        }

        $solicitacao->update($dadosAtualizacao);

        SolicitacaoTransicao::create([
            'solicitacao_id' => $solicitacao->id,
            'status_anterior' => $statusAnterior->value,
            'status_novo' => SolicitacaoStatus::APROVADA->value,
            'comentario' => $comentario,
            'responsavel_id' => $user->id,
        ]);

        event(new SolicitacaoDecidida($solicitacao));

        return $solicitacao;
    }
}
