<?php

namespace App\Enums;

enum OrganizationType: string
{
    case INDIVIDUAL = 'INDIVIDUAL';
    case SUPERVISOR = 'SUPERVISOR';
    case FOUNDER = 'FOUNDER';
}
