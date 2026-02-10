<?php

declare(strict_types=1);

namespace Tests\Feature\Solicitacao;

use App\Enums\Roles\UserRoles;
use App\Enums\Solicitacao\SolicitacaoStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Helpers\SolicitacaoHelpers;
use Tests\TestCase;

class SolicitacaoWorkflowHttpTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SolicitacaoHelpers::seedRolesAndPermissions();
    }

    public function test_should_send_solicitacao(): void
    {
        [$user, $token] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);

        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::RASCUNHO,
            'solicitante_id' => $user->id,
        ]);

        $response = $this->postJson(
            "/api/solicitacoes/{$solicitacao->id}/enviar",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(SolicitacaoStatus::ENVIADA->value, $response['status']);
    }

    public function test_should_analyze_solicitacao(): void
    {
        [$user, $token] = SolicitacaoHelpers::createUserToken(UserRoles::AVALIADOR);

        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::ENVIADA,
            'avaliador_id' => null,
        ]);

        $response = $this->postJson(
            "/api/solicitacoes/{$solicitacao->id}/analisar",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(SolicitacaoStatus::EM_ANALISE->value, $response['status']);
    }

    public function test_should_require_comment_to_approve(): void
    {
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::APROVADOR);

        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::EM_ANALISE,
            'aprovador_id' => null,
        ]);

        $response = $this->postJson(
            "/api/solicitacoes/{$solicitacao->id}/aprovar",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_approve_solicitacao(): void
    {
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::APROVADOR);

        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::EM_ANALISE,
            'aprovador_id' => null,
        ]);

        $response = $this->postJson(
            "/api/solicitacoes/{$solicitacao->id}/aprovar",
            ['comentario' => 'ok'],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(SolicitacaoStatus::APROVADA->value, $response['status']);
    }

    public function test_should_require_comment_to_reject(): void
    {
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::APROVADOR);

        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::EM_ANALISE,
            'aprovador_id' => null,
        ]);

        $response = $this->postJson(
            "/api/solicitacoes/{$solicitacao->id}/rejeitar",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_reject_solicitacao(): void
    {
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::APROVADOR);

        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::EM_ANALISE,
            'aprovador_id' => null,
        ]);

        $response = $this->postJson(
            "/api/solicitacoes/{$solicitacao->id}/rejeitar",
            ['comentario' => 'nao'],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(SolicitacaoStatus::REJEITADA->value, $response['status']);
    }

    public function test_should_fail_to_cancel_when_not_solicitante(): void
    {
        [$owner] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);
        [, $otherToken] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);

        $solicitacao = SolicitacaoHelpers::createSolicitacaoForUser($owner, [
            'status' => SolicitacaoStatus::RASCUNHO,
        ]);

        $response = $this->postJson(
            "/api/solicitacoes/{$solicitacao->id}/cancelar",
            [],
            ['Authorization' => 'Bearer ' . $otherToken]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_cancel_by_solicitante(): void
    {
        [$owner, $ownerToken] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);

        $solicitacao = SolicitacaoHelpers::createSolicitacaoForUser($owner, [
            'status' => SolicitacaoStatus::RASCUNHO,
        ]);

        $response = $this->postJson(
            "/api/solicitacoes/{$solicitacao->id}/cancelar",
            [],
            ['Authorization' => 'Bearer ' . $ownerToken]
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertEquals(SolicitacaoStatus::CANCELADA->value, $response['status']);
    }
}
