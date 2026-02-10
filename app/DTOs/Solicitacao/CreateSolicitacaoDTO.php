<?php

declare(strict_types=1);

namespace App\DTOs\Solicitacao;

use App\Enums\Solicitacao\SolicitacaoTipo;
use Illuminate\Validation\Rules\Enum as EnumRule;
use WendellAdriel\ValidatedDTO\Casting\EnumCast;
use WendellAdriel\ValidatedDTO\Casting\StringCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class CreateSolicitacaoDTO extends ValidatedDTO
{
    public string $titulo;

    public string $justificativa;

    public SolicitacaoTipo $tipo;

    protected function rules(): array
    {
        return [
            'titulo' => ['required', 'string'],
            'justificativa' => ['required', 'string'],
            'tipo' => ['required', new EnumRule(SolicitacaoTipo::class)],
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
