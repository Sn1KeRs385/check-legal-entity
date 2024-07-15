<?php

declare(strict_types=1);

namespace App\Dto\PhysicalPerson;

use Spatie\LaravelData\Attributes\FromRouteParameter;
use Spatie\LaravelData\Attributes\Validation\Exists;
use Spatie\LaravelData\Attributes\Validation\IntegerType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;

class DeleteDto extends Data
{
    #[Required, IntegerType, Exists('physical_persons', 'id'), FromRouteParameter('id')]
    public int $id;
}
