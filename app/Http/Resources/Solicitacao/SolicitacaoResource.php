<?php

declare(strict_types=1);

namespace App\Http\Resources\Solicitacao;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SolicitacaoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'justificativa' => $this->justificativa,
            'tipo' => $this->tipo?->value,
            'status' => $this->status?->value,
            'comentario_decisao' => $this->comentario_decisao,
            'solicitante_id' => $this->solicitante_id,
            'avaliador_id' => $this->avaliador_id,
            'aprovador_id' => $this->aprovador_id,
            'created_at' => $this->created_at?->toIsoString(),
            'updated_at' => $this->updated_at?->toIsoString(),
        ];
    }
}
