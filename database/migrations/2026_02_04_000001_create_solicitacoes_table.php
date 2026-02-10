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
        Schema::create('solicitacoes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('titulo');
            $table->text('justificativa');
            $table->string('tipo');
            $table->string('status')->default('rascunho');
            $table->text('comentario_decisao')->nullable();
            $table->foreignUuid('solicitante_id')->constrained('users');
            $table->foreignUuid('avaliador_id')->nullable()->constrained('users');
            $table->foreignUuid('aprovador_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solicitacoes');
    }
};
