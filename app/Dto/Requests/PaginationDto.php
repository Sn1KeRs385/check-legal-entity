<?php

declare(strict_types=1);

namespace App\Dto\Requests;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;

#[OA\Schema]
class PaginationDto extends Data
{
    #[OA\Property(default: 1), Sometimes, IntegerType, Min(1)]
    public int $page = 1;
    #[OA\Property(default: 25), Sometimes, IntegerType]
    public int $perPage = 25;
}
