<?php

declare(strict_types=1);

namespace Tests\Feature\Solicitacao;

use App\Action\Solicitacao\Workflow\AprovarSolicitacao;
use App\Action\Solicitacao\Workflow\EnviarSolicitacao;
use App\Enums\Solicitacao\SolicitacaoStatus;
use App\Events\SolicitacaoDecidida;
use App\Jobs\EnviarEmailSolicitacaoDecidida;
use App\Mail\SolicitacaoDecididaMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\Helpers\SolicitacaoHelpers;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_dispatch_event_when_approved(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::EM_ANALISE,
            'aprovador_id' => $user->id,
        ]);

        $action = app(AprovarSolicitacao::class);
        $action->handle($solicitacao, $user, 'ok');

        Event::assertDispatched(SolicitacaoDecidida::class);
    }

    public function test_should_not_dispatch_event_when_sending(): void
    {
        Event::fake();

        $user = User::factory()->create();
        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::RASCUNHO,
            'solicitante_id' => $user->id,
        ]);

        $action = app(EnviarSolicitacao::class);
        $action->handle($solicitacao, $user);

        Event::assertNotDispatched(SolicitacaoDecidida::class);
    }

    public function test_should_dispatch_job_when_event_is_fired(): void
    {
        Bus::fake();

        $solicitacao = SolicitacaoHelpers::createSolicitacao();

        event(new SolicitacaoDecidida($solicitacao));

        Bus::assertDispatched(EnviarEmailSolicitacaoDecidida::class);
    }

    public function test_should_send_email_in_job(): void
    {
        Mail::fake();

        $solicitacao = SolicitacaoHelpers::createSolicitacao();

        (new EnviarEmailSolicitacaoDecidida($solicitacao))->handle();

        Mail::assertQueued(SolicitacaoDecididaMail::class, fn ($mail) => $mail->hasTo($solicitacao->solicitante->email));
    }
}
