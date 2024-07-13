<?php

namespace App\Dto\Responses\PhysicalPerson;

use App\Enums\OrganizationType;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class OrganizationDto extends Data
{
    public OrganizationType $type;
    public int $inn;
    public string $name;
}
