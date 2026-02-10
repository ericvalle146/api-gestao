<?php

declare(strict_types=1);

namespace App\Action\User;

use App\DTOs\Common\PaginationDTO;
use App\Models\User;
use App\Support\Pagination;

class FetchListUser
{
    public function handle(PaginationDTO $dto)
    {
        $query = User::query();

        return Pagination::apply($query, $dto);
    }
}
