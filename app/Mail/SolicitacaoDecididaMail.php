<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\Solicitacao;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SolicitacaoDecididaMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public Solicitacao $solicitacao
    ) {}

    public function envelope(): Envelope
    {
        $status = $this->solicitacao->status?->value ?? 'decidida';

        return new Envelope(
            subject: "Solicitação {$status}"
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.solicitacao-decidida',
            with: [
                'status' => $this->solicitacao->status?->value,
                'comentario' => $this->solicitacao->comentario_decisao,
            ],
        );
    }
}
