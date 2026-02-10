<?php

declare(strict_types=1);

namespace Tests\Feature\Solicitacao;

use App\Enums\Roles\UserRoles;
use App\Enums\Solicitacao\SolicitacaoStatus;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Helpers\SolicitacaoHelpers;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SolicitacaoHelpers::seedRolesAndPermissions();
    }

    public function test_should_block_solicitante_from_analyzing(): void
    {
        [$user, $token] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);
        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::ENVIADA,
        ]);

        $response = $this->postJson(
            "/api/solicitacoes/{$solicitacao->id}/analisar",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(403);
    }

    public function test_should_allow_avaliador_to_analyze(): void
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

        $response->assertStatus(200);
    }
}
