<?php

declare(strict_types=1);

namespace App\Modules\Checko\Dto\PhysicalPerson;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class PhysicalPersonDto extends Data
{
    #[MapInputName('ИНН')]
    public string $inn;
    #[MapInputName('ФИО')]
    public string $fio;

    /** @var ?DataCollection<int, LegalOrganizationDto> */
    #[MapInputName('Руковод'), DataCollectionOf(LegalOrganizationDto::class)]
    public ?DataCollection $supervisors;

    /** @var ?DataCollection<int, LegalOrganizationDto> */
    #[MapInputName('Учред'), DataCollectionOf(LegalOrganizationDto::class)]
    public ?DataCollection $founders;

    /** @var ?DataCollection<int, IndividualOrganizationDto> */
    #[MapInputName('ИП'), DataCollectionOf(IndividualOrganizationDto::class)]
    public ?DataCollection $individuals;
}
