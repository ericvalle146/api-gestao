<?php

declare(strict_types=1);

namespace Tests\Feature\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\Feature\Helpers\UserHelpers;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_should_list_user_succesfully(): void
    {
        foreach (range(0, 10) as $number) {
            UserHelpers::createUser();
        }
        $adminData = UserHelpers::createAdminToken();

        $response = $this->getJson(
            '/api/users',
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);
    }

    public function test_should_return_users_page_2(): void
    {
        foreach (range(1, 29) as $number) {
            UserHelpers::createUser();
        }

        $adminData = UserHelpers::createAdminToken();

        $response = $this->getJson(
            '/api/users?page=2',
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $responseData = $response['data'];
        $responseMeta = $response['meta'];

        $this->assertIsArray($responseData);
        $this->assertCount(15, $responseData);

        $this->assertEquals(2, $responseMeta['last_page']);
        $this->assertEquals(30, $responseMeta['total']);
        $this->assertEquals(15, $responseMeta['per_page']);
    }

    public function test_should_return_5_user_per_page(): void
    {
        foreach (range(1, 29) as $number) {
            UserHelpers::createUser();
        }

        $adminData = UserHelpers::createAdminToken();

        $response = $this->getJson(
            '/api/users?per_page=5',
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'email',
                        'role',
                        'created_at',
                        'updated_at',
                    ],
                ],
            ]);

        $responseData = $response['data'];
        $responseMeta = $response['meta'];

        $this->assertIsArray($responseData);
        $this->assertCount(5, $responseData);

        $this->assertEquals(6, $responseMeta['last_page']);
        $this->assertEquals(30, $responseMeta['total']);
        $this->assertEquals(5, $responseMeta['per_page']);
    }

    public function test_should_return_user_succesfully(): void
    {
        $user = UserHelpers::createUser();
        $adminData = UserHelpers::createAdminToken();

        $response = $this->getJson(
            "api/users/{$user->id}",
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'role',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_should_return_not_found_for_non_existent_user(): void
    {
        $adminData = UserHelpers::createAdminToken();

        $response = $this->getJson(
            'api/users/00000000-0000-0000-0000-000000000000',
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function test_should_create_user_successfully()
    {
        $dataFake = UserHelpers::createUserDataFaker();

        $adminData = UserHelpers::createAdminToken();

        $params = [
            'name' => $dataFake->name,
            'email' => $dataFake->email,
            'role' => $dataFake->role,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(
            'api/users',
            $params,
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'role',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_should_fail_to_create_user_with_missing_data()
    {
        $dataFake = UserHelpers::createUserDataFaker();

        $adminData = UserHelpers::createAdminToken();

        $params = [
            'name' => $dataFake->name,
            'email' => '',
            'role' => $dataFake->role,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(
            'api/users',
            $params,
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_fail_to_create_user_with_invalid_email()
    {
        $dataFake = UserHelpers::createUserDataFaker();

        $adminData = UserHelpers::createAdminToken();

        $params = [
            'name' => $dataFake->name,
            'email' => 'teste',
            'role' => $dataFake->role,
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        $response = $this->postJson(
            'api/users',
            $params,
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function test_should_updated_user_successfully()
    {
        $user = UserHelpers::createUser();
        $userNewData = UserHelpers::createUserDataFaker();
        $adminData = UserHelpers::createAdminToken();

        $params = [
            'name' => $userNewData->name,
            'email' => $userNewData->email,
            'role' => $userNewData->role,
        ];

        $response = $this->putJson(
            "api/users/{$user->id}",
            $params,
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'role',
                'created_at',
                'updated_at',
            ]);
    }

    public function test_should_delete_user_successfully(): void
    {
        $adminData = UserHelpers::createAdminToken();
        $user = UserHelpers::createUser();

        $response = $this->deleteJson(
            "api/users/{$user->id}",
            [],
            ['Authorization' => 'Bearer ' . $adminData[1]]
        );

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }
}
