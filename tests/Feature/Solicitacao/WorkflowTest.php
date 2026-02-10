<?php

declare(strict_types=1);

namespace Tests\Feature\Solicitacao;

use App\Action\Solicitacao\Workflow\AprovarSolicitacao;
use App\Action\Solicitacao\Workflow\EnviarSolicitacao;
use App\Enums\Solicitacao\SolicitacaoStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\Feature\Helpers\SolicitacaoHelpers;
use Tests\TestCase;

class WorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_block_invalid_transition_when_approving_from_rascunho(): void
    {
        $user = User::factory()->create();
        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::RASCUNHO,
            'aprovador_id' => $user->id,
        ]);

        $action = app(AprovarSolicitacao::class);

        $this->expectException(ValidationException::class);

        $action->handle($solicitacao, $user, 'ok');
    }

    public function test_should_send_and_register_transition(): void
    {
        $user = User::factory()->create();
        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::RASCUNHO,
            'solicitante_id' => $user->id,
        ]);

        $action = app(EnviarSolicitacao::class);
        $action->handle($solicitacao, $user);

        $solicitacao->refresh();

        $this->assertEquals(SolicitacaoStatus::ENVIADA, $solicitacao->status);
        $this->assertCount(1, $solicitacao->transicoes);
    }
}
