<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Mail\SolicitacaoDecididaMail;
use App\Models\Solicitacao;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarEmailSolicitacaoDecidida implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public Solicitacao $solicitacao
    ) {}

    public function handle(): void
    {
        $this->solicitacao->loadMissing('solicitante');

        Mail::to($this->solicitacao->solicitante->email)
            ->send(new SolicitacaoDecididaMail($this->solicitacao));
    }
}
