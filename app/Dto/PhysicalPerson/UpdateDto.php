<?php

declare(strict_types=1);

namespace App\Dto\PhysicalPerson;

use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\MapOutputName;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Nullable;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Attributes\Validation\StringType;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapOutputName(SnakeCaseMapper::class)]
class UpdateDto extends Data
{
    #[Required, IntegerType, Exists('physical_persons', 'id'), FromRouteParameter('id')]
    public int $id;
    #[Required, StringType]
    public string $firstName;
    #[Required, StringType]
    public string $secondName;
    #[Nullable, StringType]
    public ?string $lastName;
}
