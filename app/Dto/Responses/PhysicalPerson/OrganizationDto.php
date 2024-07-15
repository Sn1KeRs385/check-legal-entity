<?php

declare(strict_types=1);

namespace App\Dto\Responses\PhysicalPerson;

use App\Enums\OrganizationType;
use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[OA\Schema, MapInputName(SnakeCaseMapper::class)]
class OrganizationDto extends Data
{
    #[OA\Property]
    public OrganizationType $type;
    #[OA\Property]
    public int $inn;
    #[OA\Property]
    public string $name;
}
