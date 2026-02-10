<?php

declare(strict_types=1);

namespace Tests\Feature\Solicitacao;

use App\Enums\Roles\UserRoles;
use App\Enums\Solicitacao\SolicitacaoStatus;
use App\Enums\Solicitacao\SolicitacaoTipo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Helpers\SolicitacaoHelpers;
use Tests\TestCase;

class SolicitacaoTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        SolicitacaoHelpers::seedRolesAndPermissions();
    }

    public function test_should_list_solicitacoes_successfully(): void
    {
        foreach (range(1, 5) as $number) {
            SolicitacaoHelpers::createSolicitacao();
        }

        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::ADMIN);

        $response = $this->getJson(
            '/api/solicitacoes',
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'titulo',
                        'justificativa',
                        'tipo',
                        'status',
                        'comentario_decisao',
                        'solicitante_id',
                        'avaliador_id',
                        'aprovador_id',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_should_return_solicitacao_successfully(): void
    {
        $solicitacao = SolicitacaoHelpers::createSolicitacao();
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::ADMIN);

        $response = $this->getJson(
            "/api/solicitacoes/{$solicitacao->id}",
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'titulo',
                'justificativa',
                'tipo',
                'status',
                'comentario_decisao',
                'solicitante_id',
                'avaliador_id',
                'aprovador_id',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_should_not_return_unrelated_solicitacao(): void
    {
        $solicitacao = SolicitacaoHelpers::createSolicitacao();
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);

        $response = $this->getJson(
            "/api/solicitacoes/{$solicitacao->id}",
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_should_create_solicitacao_successfully(): void
    {
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);

        $params = [
            'titulo' => 'Acesso ao sistema X',
            'justificativa' => 'Preciso para trabalhar',
            'tipo' => 'acesso',
        ];

        $response = $this->postJson(
            '/api/solicitacoes',
            $params,
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'titulo',
                'justificativa',
                'tipo',
                'status',
                'comentario_decisao',
                'solicitante_id',
                'avaliador_id',
                'aprovador_id',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_should_fail_to_create_solicitacao_with_missing_data(): void
    {
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);

        $params = [
            'titulo' => '',
            'justificativa' => '',
            'tipo' => '',
        ];

        $response = $this->postJson(
            '/api/solicitacoes',
            $params,
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_list_only_own_or_assigned(): void
    {
        [$user, $token] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);

        SolicitacaoHelpers::createSolicitacao([
            'solicitante_id' => $user->id,
            'status' => SolicitacaoStatus::RASCUNHO,
        ]);

        SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::RASCUNHO,
        ]);

        $response = $this->getJson(
            '/api/solicitacoes',
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK);
        $this->assertCount(1, $response['data']);
    }

    public function test_should_update_solicitacao_successfully(): void
    {
        $admin = SolicitacaoHelpers::createUserWithRole(UserRoles::ADMIN);
        $token = $admin->createToken('teste_token')->plainTextToken;

        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::RASCUNHO,
        ]);

        $payload = [
            'titulo' => 'Novo titulo',
            'justificativa' => 'Nova justificativa',
            'tipo' => SolicitacaoTipo::RECURSO->value,
        ];

        $response = $this->putJson(
            "/api/solicitacoes/{$solicitacao->id}",
            $payload,
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('titulo', 'Novo titulo')
            ->assertJsonPath('justificativa', 'Nova justificativa')
            ->assertJsonPath('tipo', SolicitacaoTipo::RECURSO->value);
    }

    public function test_should_forbid_update_for_non_admin(): void
    {
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);

        $solicitacao = SolicitacaoHelpers::createSolicitacao();

        $response = $this->putJson(
            "/api/solicitacoes/{$solicitacao->id}",
            ['titulo' => 'Nao pode'],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_should_validate_invalid_tipo_on_update(): void
    {
        $admin = SolicitacaoHelpers::createUserWithRole(UserRoles::ADMIN);
        $token = $admin->createToken('teste_token')->plainTextToken;

        $solicitacao = SolicitacaoHelpers::createSolicitacao();

        $response = $this->putJson(
            "/api/solicitacoes/{$solicitacao->id}",
            ['tipo' => 'invalido'],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_update_only_provided_fields(): void
    {
        $admin = SolicitacaoHelpers::createUserWithRole(UserRoles::ADMIN);
        $token = $admin->createToken('teste_token')->plainTextToken;

        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'titulo' => 'Titulo antigo',
            'justificativa' => 'Justificativa antiga',
            'tipo' => SolicitacaoTipo::ACESSO,
        ]);

        $response = $this->putJson(
            "/api/solicitacoes/{$solicitacao->id}",
            ['titulo' => 'Titulo novo'],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('titulo', 'Titulo novo')
            ->assertJsonPath('justificativa', 'Justificativa antiga');
    }

    public function test_should_not_change_status_on_update(): void
    {
        $admin = SolicitacaoHelpers::createUserWithRole(UserRoles::ADMIN);
        $token = $admin->createToken('teste_token')->plainTextToken;

        $solicitacao = SolicitacaoHelpers::createSolicitacao([
            'status' => SolicitacaoStatus::RASCUNHO,
        ]);

        $response = $this->putJson(
            "/api/solicitacoes/{$solicitacao->id}",
            [
                'titulo' => 'Titulo novo',
                'status' => SolicitacaoStatus::APROVADA->value,
            ],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonPath('status', SolicitacaoStatus::RASCUNHO->value);
    }

    public function test_should_delete_solicitacao_successfully(): void
    {
        $admin = SolicitacaoHelpers::createUserWithRole(UserRoles::ADMIN);
        $token = $admin->createToken('teste_token')->plainTextToken;

        $solicitacao = SolicitacaoHelpers::createSolicitacao();

        $response = $this->deleteJson(
            "/api/solicitacoes/{$solicitacao->id}",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('solicitacoes', ['id' => $solicitacao->id]);
    }

    public function test_should_forbid_delete_for_non_admin(): void
    {
        [, $token] = SolicitacaoHelpers::createUserToken(UserRoles::SOLICITANTE);

        $solicitacao = SolicitacaoHelpers::createSolicitacao();

        $response = $this->deleteJson(
            "/api/solicitacoes/{$solicitacao->id}",
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_FORBIDDEN);
    }

    public function test_should_return_not_found_when_deleting_non_existent(): void
    {
        $admin = SolicitacaoHelpers::createUserWithRole(UserRoles::ADMIN);
        $token = $admin->createToken('teste_token')->plainTextToken;

        $response = $this->deleteJson(
            '/api/solicitacoes/00000000-0000-0000-0000-000000000000',
            [],
            ['Authorization' => 'Bearer ' . $token]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }
}
