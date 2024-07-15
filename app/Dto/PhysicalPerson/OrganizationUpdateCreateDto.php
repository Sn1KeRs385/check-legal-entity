<?php

declare(strict_types=1);

namespace App\Dto\PhysicalPerson;

use App\Enums\OrganizationType;
use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\Validation\Enum;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\MaxDigits;
use Spatie\LaravelData\Attributes\Validation\MinDigits;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\Sometimes;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[OA\Schema, MapOutputName(SnakeCaseMapper::class)]
class OrganizationUpdateCreateDto extends Data
{
    #[Sometimes, IntegerType, Nullable, Exists('organizations', 'id')]
    public Optional|int|null $id;
    #[OA\Property, Required, StringType, Enum(OrganizationType::class)]
    public OrganizationType $type;
    #[OA\Property, Required, IntegerType, MinDigits(10), MaxDigits(12)]
    public int $inn;
    #[OA\Property, Required, StringType]
    public string $name;
}
