<?php

declare(strict_types=1);

namespace App\Modules\Checko\Dto\PhysicalPerson;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class LegalOrganizationDto extends Data
{
    #[MapInputName('ОГРН')]
    public string $ogrn;
    #[MapInputName('ИНН')]
    public string $inn;
    #[MapInputName('КПП')]
    public string $kpp;
    #[MapInputName('НаимСокр')]
    public string $shortName;
    #[MapInputName('НаимПолн')]
    public string $fullName;
    #[MapInputName('ДатаРег')]
    public string $registrationDate;
    #[MapInputName('Статус')]
    public string $status;
    #[MapInputName('ДатаЛикв')]
    public ?string $stopDate;
    #[MapInputName('РегионКод')]
    public string $regionCode;
    #[MapInputName('ЮрАдрес')]
    public string $address;
    #[MapInputName('ОКВЭД')]
    public string $okved;
}
