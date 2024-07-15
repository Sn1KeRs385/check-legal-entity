<?php

declare(strict_types=1);

namespace App\Modules\Checko\Dto\PhysicalPerson;

use Spatie\LaravelData\Data;

class GetPhysicalPersonDataDto extends Data
{
    public PhysicalPersonDto $data;
}
