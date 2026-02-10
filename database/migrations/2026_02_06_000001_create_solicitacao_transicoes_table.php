<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('solicitacao_transicoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('solicitacao_id')->constrained('solicitacoes');
            $table->string('status_anterior');
            $table->string('status_novo');
            $table->text('comentario')->nullable();
            $table->foreignUuid('responsavel_id')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacao_transicoes');
    }
};
