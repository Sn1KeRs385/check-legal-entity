<?php

declare(strict_types=1);

namespace App\Dto\PhysicalPerson;

use OpenApi\Attributes as OA;
use Spatie\LaravelData\Attributes\Validation\ArrayType;
use Spatie\LaravelData\Attributes\Validation\Required;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Support\Validation\ValidationContext;

#[OA\Schema]
class OrganizationsParseDto extends Data
{
    /** @var int[] */
    #[OA\Property(items: new OA\Items(type: 'integer')), Required, ArrayType]
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
