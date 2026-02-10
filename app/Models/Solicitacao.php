<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\Solicitacao\SolicitacaoStatus;
use App\Enums\Solicitacao\SolicitacaoTipo;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solicitacao extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'solicitacoes';

    protected $fillable = [
        'titulo',
        'justificativa',
        'tipo',
        'status',
        'avaliador_id',
        'aprovador_id',
        'comentario_decisao',
        'solicitante_id',
    ];

    public function solicitante(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function avaliador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'avaliador_id');
    }

    public function aprovador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovador_id');
    }

    public function transicoes(): HasMany
    {
        return $this->hasMany(SolicitacaoTransicao::class, 'solicitacao_id');
    }

    protected function casts(): array
    {
        return [
            'status' => SolicitacaoStatus::class,
            'tipo' => SolicitacaoTipo::class,
        ];
    }
}
