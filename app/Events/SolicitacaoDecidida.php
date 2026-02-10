<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\Solicitacao;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SolicitacaoDecidida
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public Solicitacao $solicitacao
    ) {}
}
