<?php

declare(strict_types=1);

namespace App\Dto\Responses\PhysicalPerson;

use App\Dto\Responses\PaginationMetaDto;
use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

#[OA\Schema]
class PhysicalPersonPaginatedDto extends Data
{
    /** @var DataCollection<int, PhysicalPersonDto> */
    #[OA\Property(type: 'array', items: new OA\Items(ref: PhysicalPersonDto::class)), DataCollectionOf(PhysicalPersonDto::class)]
    public DataCollection $items;
    #[OA\Property]
    public PaginationMetaDto $meta;
}
