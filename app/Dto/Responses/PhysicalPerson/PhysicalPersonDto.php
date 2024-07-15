<?php

declare(strict_types=1);

namespace App\Dto\Responses\PhysicalPerson;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[OA\Schema, MapInputName(SnakeCaseMapper::class)]
class PhysicalPersonDto extends Data
{
    #[OA\Property]
    public int $id;
    #[OA\Property]
    public int $inn;
    #[OA\Property]
    public string $firstName;
    #[OA\Property]
    public string $secondName;
    #[OA\Property]
    public ?string $lastName;

    /** @var DataCollection<int, OrganizationDto> */
    #[OA\Property(type: 'array', items: new OA\Items(ref: OrganizationDto::class)), DataCollectionOf(OrganizationDto::class)]
    public DataCollection $organizations;
}
