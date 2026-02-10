<?php

declare(strict_types=1);

namespace App\DTOs\Solicitacao;

use App\Enums\Solicitacao\SolicitacaoTipo;
use Illuminate\Validation\Rules\Enum as EnumRule;
use WendellAdriel\ValidatedDTO\Casting\EnumCast;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class UpdateSolicitacaoDTO extends ValidatedDTO
{
    protected function rules(): array
    {
        return [
            'titulo' => ['sometimes', 'string'],
            'justificativa' => ['sometimes', 'string'],
            'tipo' => ['sometimes', new EnumRule(SolicitacaoTipo::class)],
        ];
    }

    protected function defaults(): array
    {
        return [];
    }

    protected function casts(): array
    {
        return [
            'titulo' => new StringCast(),
            'justificativa' => new StringCast(),
            'tipo' => new EnumCast(SolicitacaoTipo::class),
        ];
    }
}
