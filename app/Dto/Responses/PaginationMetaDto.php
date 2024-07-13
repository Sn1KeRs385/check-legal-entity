<?php

declare(strict_types=1);

namespace App\Dto\Responses;

use Spatie\LaravelData\Data;

class PaginationMetaDto extends Data
{
    public int $page;
    public int $perPage;
    public int $itemCount;
    public int $pageCount;
    public bool $hasPreviousPage;
    public bool $hasNextPage;
}
