<?php

declare(strict_types=1);

namespace App\Enums\Permissions;

enum SolicitacaoPermissions: string
{
    case LIST = 'solicitacoes.list';
    case VIEW = 'solicitacoes.view';
    case CREATE = 'solicitacoes.create';
    case UPDATE = 'solicitacoes.update';
    case DELETE = 'solicitacoes.delete';
    case ANALISAR = 'solicitacoes.analisar';
    case APROVAR = 'solicitacoes.aprovar';
    case REJEITAR = 'solicitacoes.rejeitar';
    case CANCELAR = 'solicitacoes.cancelar';

    public static function all(): array
    {
        return [
            self::LIST,
            self::VIEW,
            self::CREATE,
            self::UPDATE,
            self::DELETE,
            self::ANALISAR,
            self::APROVAR,
            self::REJEITAR,
            self::CANCELAR,
        ];
    }

    public static function toArray(): array
    {
        return array_column(SolicitacaoPermissions::cases(), 'value');
    }

    public function description(): string
    {
        return match ($this) {
            self::LIST => 'Listar solicitações',
            self::VIEW => 'Visualizar solicitação',
            self::CREATE => 'Criar solicitação',
            self::UPDATE => 'Atualizar solicitação',
            self::DELETE => 'Excluir solicitação',
            self::ANALISAR => 'Analisar solicitação',
            self::APROVAR => 'Aprovar solicitação',
            self::REJEITAR => 'Rejeitar solicitação',
            self::CANCELAR => 'Cancelar solicitação',
        };
    }
}
