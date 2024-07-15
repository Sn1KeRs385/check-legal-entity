<?php

declare(strict_types=1);

namespace App\Dto\PhysicalPerson;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\MaxDigits;
use Spatie\LaravelData\Attributes\Validation\MinDigits;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Attributes\Validation\Unique;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[OA\Schema, MapOutputName(SnakeCaseMapper::class)]
class CreateDto extends Data
{
    #[OA\Property, Required, IntegerType, Unique('physical_persons', 'inn'), MinDigits(12), MaxDigits(12)]
    public int $inn;
    #[OA\Property, Required, StringType]
    public string $firstName;
    #[OA\Property, Required, StringType]
    public string $secondName;
    #[OA\Property, Nullable, StringType]
    public ?string $lastName;

    /** @var DataCollection<int, OrganizationUpdateCreateDto>|null */
    #[OA\Property(type: 'array', items: new OA\Items(ref: OrganizationUpdateCreateDto::class)), Nullable, ArrayType, DataCollectionOf(OrganizationUpdateCreateDto::class)]
    public ?DataCollection $organizations;
}
