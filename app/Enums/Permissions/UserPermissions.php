<?php

declare(strict_types=1);

namespace App\Enums\Permissions;

enum UserPermissions: string
{
    case LIST_USERS = 'users.list';
    case VIEW_USERS = 'users.view';
    case CREATE_USERS = 'users.create';
    case UPDATE_USERS = 'users.update';
    case DELETE_USERS = 'users.delete';

    public static function all(): array
    {
        return [
            self::LIST_USERS,
            self::VIEW_USERS,
            self::CREATE_USERS,
            self::UPDATE_USERS,
            self::DELETE_USERS,
        ];
    }

    public static function toArray(): array
    {
        return array_column(UserPermissions::cases(), 'value');
    }

    public function description()
    {
        return match ($this) {
            self::LIST_USERS => 'Listar todos usuários',
            self::VIEW_USERS => 'Visualizar usuário',
            self::CREATE_USERS => 'Criar usuário',
            self::UPDATE_USERS => 'Atualizar usuário',
            self::DELETE_USERS => 'Deletar usuário',
        };
    }
}
