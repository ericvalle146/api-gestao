<?php

declare(strict_types=1);

namespace App\Support;

use App\DTOs\Common\PaginationDTO;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class Pagination
{
    public const ALL_COLUMNS = ['*'];

    public const DEFAULT_PAGE_NAME = 'page';

    public static function apply(
        Builder $builder,
        PaginationDTO $dto,
        array $columns = self::ALL_COLUMNS,
        string $pageName = self::DEFAULT_PAGE_NAME
    ): LengthAwarePaginator|Collection {
        return $builder->paginate($dto->per_page, $columns, $pageName, $dto->page);
    }
}
