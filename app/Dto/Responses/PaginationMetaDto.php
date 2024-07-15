<?php

declare(strict_types=1);

namespace App\Dto\Responses;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Data;

#[OA\Schema]
class PaginationMetaDto extends Data
{
    #[OA\Property]
    public int $page;
    #[OA\Property]
    public int $perPage;
    #[OA\Property]
    public int $itemCount;
    #[OA\Property]
    public int $pageCount;
    #[OA\Property]
    public bool $hasPreviousPage;
    #[OA\Property]
    public bool $hasNextPage;
}
