<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\MassDeleteDto;
use App\Http\Controllers\Controller;
use App\Services\PhysicalPersonService;
use Illuminate\Http\Response;

class MassDeleteController extends Controller
{
    public function __construct(private readonly PhysicalPersonService $service)
    {
    }

    public function __invoke(MassDeleteDto $requestData): Response
    {
        $this->service->massDelete($requestData);

        return response()->noContent();
    }
}
