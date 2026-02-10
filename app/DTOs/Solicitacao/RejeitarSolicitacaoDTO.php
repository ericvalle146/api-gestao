<?php

declare(strict_types=1);

namespace App\DTOs\Solicitacao;

use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class RejeitarSolicitacaoDTO extends ValidatedDTO
{
    public string $comentario;

    protected function rules(): array
    {
        return [
            'comentario' => ['required', 'string'],
        ];
    }

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'comentario' => new StringCast(),
        ];
    }
}
