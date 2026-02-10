<?php

declare(strict_types=1);

namespace App\Enums\Solicitacao;

enum SolicitacaoStatus: string
{
    case RASCUNHO = 'rascunho';
    case ENVIADA = 'enviada';
    case EM_ANALISE = 'em_analise';
    case APROVADA = 'aprovada';
    case REJEITADA = 'rejeitada';
    case CANCELADA = 'cancelada';

    public static function all(): array
    {
        return [
            self::RASCUNHO,
            self::ENVIADA,
            self::EM_ANALISE,
            self::APROVADA,
            self::REJEITADA,
            self::CANCELADA,
        ];
    }

    public function description(): string
    {
        return match ($this) {
            self::RASCUNHO => 'Rascunho',
            self::ENVIADA => 'Enviada',
            self::EM_ANALISE => 'Em análise',
            self::APROVADA => 'Aprovada',
            self::REJEITADA => 'Rejeitada',
            self::CANCELADA => 'Cancelada',
        };
    }

    public function podeTransitarPara(SolicitacaoStatus $novo): bool
    {
        $permitidas = match ($this) {
            self::RASCUNHO => [self::ENVIADA, self::CANCELADA],
            self::ENVIADA => [self::EM_ANALISE, self::CANCELADA],
            self::EM_ANALISE => [self::APROVADA, self::REJEITADA],
            self::APROVADA => [],
            self::REJEITADA => [],
            self::CANCELADA => [],
        };

        return in_array($novo, $permitidas);
    }
}
