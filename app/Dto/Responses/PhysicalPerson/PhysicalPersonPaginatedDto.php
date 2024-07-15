<?php

declare(strict_types=1);

namespace App\Dto\Responses\PhysicalPerson;

use App\Dto\Responses\PaginationMetaDto;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class PhysicalPersonPaginatedDto extends Data
{
    /** @var DataCollection<int, PhysicalPersonDto> */
    #[DataCollectionOf(PhysicalPersonDto::class)]
    public DataCollection $items;
    public PaginationMetaDto $meta;
}
