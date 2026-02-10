<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\Solicitacao\SolicitacaoStatus;
use App\Enums\Solicitacao\SolicitacaoTipo;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Solicitacao>
 */
class SolicitacaoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(),
            'justificativa' => fake()->paragraph(),
            'tipo' => fake()->randomElement(SolicitacaoTipo::all()),
            'status' => SolicitacaoStatus::RASCUNHO,
            'solicitante_id' => User::factory(),
        ];
    }
}
