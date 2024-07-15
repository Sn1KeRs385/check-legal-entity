<?php

declare(strict_types=1);

namespace App\Dto\Responses\PhysicalPerson;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;

#[MapInputName(SnakeCaseMapper::class)]
class PhysicalPersonDto extends Data
{
    public int $id;
    public int $inn;
    public string $firstName;
    public string $secondName;
    public ?string $lastName;

    /** @var DataCollection<int, OrganizationDto> */
    #[DataCollectionOf(OrganizationDto::class)]
    public DataCollection $organizations;
}
