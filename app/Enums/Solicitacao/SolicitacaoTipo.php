<?php

declare(strict_types=1);

namespace App\Enums\Solicitacao;

enum SolicitacaoTipo: string
{
    case ACESSO = 'acesso';
    case RECURSO = 'recurso';
    case OUTROS = 'outros';

    public static function all(): array
    {
        return [
            self::ACESSO,
            self::RECURSO,
            self::OUTROS,
        ];
    }

    public function description(): string
    {
        return match ($this) {
            self::ACESSO => 'Acesso a sistema',
            self::RECURSO => 'Recurso de infraestrutura',
            self::OUTROS => 'Outros',
        };
    }
}
