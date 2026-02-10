<?php

declare(strict_types=1);

namespace App\Http\Controllers\Solicitacoes;

use App\Action\Solicitacao\Workflow\RejeitarSolicitacao;
use App\DTOs\Solicitacao\RejeitarSolicitacaoDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\Solicitacao\SolicitacaoResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\Solicitacao;
use Illuminate\Http\Request;

class RejeitarController extends Controller
{
    public function __invoke(Solicitacao $solicitacao, RejeitarSolicitacaoDTO $dto, RejeitarSolicitacao $action, Request $request): ApiSuccessResponse
    {
        return new ApiSuccessResponse(
            new SolicitacaoResource($action->handle($solicitacao, $request->user(), $dto->comentario))
        );
    }
}
