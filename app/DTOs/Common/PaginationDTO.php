<?php

declare(strict_types=1);

namespace App\DTOs\Common;

use WendellAdriel\ValidatedDTO\Casting\IntegerCast;
use WendellAdriel\ValidatedDTO\ValidatedDTO;

class PaginationDTO extends ValidatedDTO
{
    protected function rules(): array
    {
        return [
            'page' => ['sometimes', 'string'],
            'per_page' => ['sometimes', 'string'],
        ];
    }

    protected function defaults(): array
    {
        return [
            'page' => 1,
            'per_page' => 15,
        ];
    }

    protected function casts(): array
    {
        return [
            'page' => new IntegerCast(),
            'per_page' => new IntegerCast(),

        ];
    }
}
