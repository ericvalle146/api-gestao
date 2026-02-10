<?php

declare(strict_types=1);

namespace App\Enums\Roles;

use App\Enums\Permissions\SolicitacaoPermissions;
use App\Enums\Permissions\UserPermissions;

enum UserRoles: string
{
    case SOLICITANTE = 'solicitante';
    case AVALIADOR = 'avaliador';
    case APROVADOR = 'aprovador';
    case ADMIN = 'admin';

    public static function all(): array
    {
        return [
            self::SOLICITANTE,
            self::AVALIADOR,
            self::APROVADOR,
            self::ADMIN,
        ];
    }

    public function description(): string
    {
        return match ($this) {
            self::SOLICITANTE => 'Solicitante',
            self::AVALIADOR => 'Avaliador de solicitações',
            self::APROVADOR => 'Aprovador de solicitações',
            self::ADMIN => 'Administrador do sistema',
        };
    }

    public function permissions(): array
    {
        return match ($this) {
            self::SOLICITANTE => [
                SolicitacaoPermissions::LIST,
                SolicitacaoPermissions::VIEW,
                SolicitacaoPermissions::CREATE,
                SolicitacaoPermissions::CANCELAR,
            ],
            self::AVALIADOR => [
                SolicitacaoPermissions::LIST,
                SolicitacaoPermissions::VIEW,
                SolicitacaoPermissions::ANALISAR,
            ],
            self::APROVADOR => [
                SolicitacaoPermissions::LIST,
                SolicitacaoPermissions::VIEW,
                SolicitacaoPermissions::APROVAR,
                SolicitacaoPermissions::REJEITAR,
            ],
            self::ADMIN => array_merge(UserPermissions::all(), SolicitacaoPermissions::all()),
        };
    }
}
