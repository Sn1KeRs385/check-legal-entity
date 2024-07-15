<?php

namespace App\Dto\PhysicalPerson;

use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

class OrganizationsParseDto extends Data
{
    /** @var int[]  */
    #[Required, ArrayType]
    public array $ids;

    /**
     * @return array<string, mixed>
     */
    public static function rules(ValidationContext $context): array
    {
        return [
            'id.*' => ['integer'],
        ];
    }
}
