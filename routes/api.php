<?php

declare(strict_types=1);

use App\Http\Controllers\Solicitacoes\AnalisarController;
use App\Http\Controllers\Solicitacoes\AprovarController;
use App\Http\Controllers\Solicitacoes\CancelarController;
use App\Http\Controllers\Solicitacoes\EnviarController;
use App\Http\Controllers\Solicitacoes\RejeitarController;
use App\Http\Controllers\Solicitacoes\SolicitacaoController;
use App\Http\Controllers\User\Auth\AuthUserController;
use App\Http\Controllers\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::prefix('/users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->can('users.list');
        Route::post('/', [UserController::class, 'store'])->can('users.create');
        Route::get('/{user}', [UserController::class, 'show'])->can('users.view');
        Route::put('/{user}', [UserController::class, 'update'])->can('users.update');
        Route::delete('/{user}', [UserController::class, 'destroy'])->can('users.delete');

    });
    Route::prefix('solicitacoes')->group(function () {
        Route::get('/', [SolicitacaoController::class, 'index'])->can('solicitacoes.list');
        Route::get('/{solicitacao}', [SolicitacaoController::class, 'show'])->can('solicitacoes.view');
        Route::post('/', [SolicitacaoController::class, 'store'])->can('solicitacoes.create');
        Route::put('/{solicitacao}', [SolicitacaoController::class, 'update'])->can('solicitacoes.update');
        Route::delete('/{solicitacao}', [SolicitacaoController::class, 'destroy'])->can('solicitacoes.delete');

        // Rota de enviar
        Route::post('/{solicitacao}/enviar', EnviarController::class)->can('solicitacoes.create');

        // Rota de analisar
        Route::post('/{solicitacao}/analisar', AnalisarController::class)->can('solicitacoes.analisar');

        // Rota de aprovar
        Route::post('/{solicitacao}/aprovar', AprovarController::class)->can('solicitacoes.aprovar');

        // Rota de rejeitar
        Route::post('/{solicitacao}/rejeitar', RejeitarController::class)->can('solicitacoes.rejeitar');

        // Rota de cancelar
        Route::post('/{solicitacao}/cancelar', CancelarController::class)->can('solicitacoes.cancelar');
    });
});

Route::prefix('/auth')->group(function () {
    Route::post('login', [AuthUserController::class, 'login']);
    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthUserController::class, 'me']);
        Route::get('/logout', [AuthUserController::class, 'logout']);
    });
});

// Route::get('/solicitacao', [SolicitacaoController::class, 'index']);
// Route::post('/solicitacao', [SolicitacaoController::class, 'store']);
// Route::get('/solicitacao/{$id}', [SolicitacaoController::class, 'show']);

// Route::post('/solicitacoes/{solicitacao}/enviar', EnviarSolicitacaoController::class);
// Route::post('/solicitacoes/{solicitacao}/analisar', AnalisarSolicitacaoController::class);
// Route::post('/solicitacoes/{solicitacao}/aprovar', AprovarSolicitacaoController::class);
// Route::post('/solicitacoes/{solicitacao}/rejeitar', RejeitarSolicitacaoController::class);
// Route::post('/solicitacoes/{solicitacao}/cancelar', CancelarSolicitacaoController::class);
