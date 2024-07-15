<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(version: '1.0.0', title: 'API'), OA\Tag(name: 'Physical person', description: 'Физ. лицо')]
abstract class Controller
{
}
