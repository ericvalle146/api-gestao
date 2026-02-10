<?php

declare(strict_types=1);

namespace App\Http\Controllers\Solicitacoes;

use App\Action\Solicitacao\CreateSolicitacao;
use App\Action\Solicitacao\DeleteSolicitacao;
use App\Action\Solicitacao\FetchListSolicitacao;
use App\Action\Solicitacao\FetchSolicitacao;
use App\Action\Solicitacao\UpdateSolicitacao;
use App\DTOs\Common\PaginationDTO;
use App\DTOs\Solicitacao\CreateSolicitacaoDTO;
use App\DTOs\Solicitacao\UpdateSolicitacaoDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Solicitacao\SolicitacaoResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\NoContentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SolicitacaoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginationDTO $dto, FetchListSolicitacao $action, Request $request): JsonResponse
    {
        $result = $action->handle($dto, $request->user());

        return SolicitacaoResource::collection($result)->response();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateSolicitacaoDTO $dto, CreateSolicitacao $action, Request $request)
    {
        $solicitacao = $action->handle($dto, $request->user());

        return new ApiSuccessResponse(new SolicitacaoResource($solicitacao), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, FetchSolicitacao $action, Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new SolicitacaoResource($action->handle($id, $request->user())));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, UpdateSolicitacaoDTO $dto, UpdateSolicitacao $action, Request $request): ApiSuccessResponse
    {
        $solicitacao = $action->handle($id, $dto, $request->user());

        return new ApiSuccessResponse(new SolicitacaoResource($solicitacao));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, DeleteSolicitacao $action, Request $request): NoContentResponse
    {
        $action->handle($id, $request->user());

        return new NoContentResponse();
    }
}
