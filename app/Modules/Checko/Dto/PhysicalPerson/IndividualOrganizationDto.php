<?php

declare(strict_types=1);

namespace App\Modules\Checko\Dto\PhysicalPerson;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class IndividualOrganizationDto extends Data
{
    #[MapInputName('ОГРНИП')]
    public string $ogrnip;
    #[MapInputName('ИНН')]
    public string $inn;
    #[MapInputName('ФИО')]
    public string $fio;
    #[MapInputName('Тип')]
    public string $type;
    #[MapInputName('ДатаРег')]
    public string $registrationDate;
    #[MapInputName('Статус')]
    public string $status;
    #[MapInputName('ДатаПрекращ')]
    public ?string $stopDate;
    #[MapInputName('РегионКод')]
    public string $regionCode;
    #[MapInputName('ОКВЭД')]
    public string $okved;
}
