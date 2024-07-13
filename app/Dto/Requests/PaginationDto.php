<?php

namespace App\Dto\Requests;

use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Min;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Data;

class PaginationDto extends Data
{
    #[Sometimes, IntegerType, Min(1)]
    public int $page = 1;
    #[Sometimes, IntegerType]
    public int $perPage = 25;
}
