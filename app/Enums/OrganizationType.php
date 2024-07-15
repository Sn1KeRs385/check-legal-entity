<?php

declare(strict_types=1);

namespace App\Enums;

use OpenApi\Attributes as OA;

#[OA\Schema]
enum OrganizationType: string
{
    case INDIVIDUAL = 'INDIVIDUAL';
    case SUPERVISOR = 'SUPERVISOR';
    case FOUNDER = 'FOUNDER';
}
