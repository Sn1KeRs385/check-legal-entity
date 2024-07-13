<?php

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\CreateDto;
use App\Dto\Responses\PhysicalPerson\PhysicalPersonDto;
use App\Http\Controllers\Controller;
use App\Services\PhysicalPersonService;
use Illuminate\Http\JsonResponse;

class CreateController extends Controller
{
    public function __construct(private readonly PhysicalPersonService $service)
    {
    }

    public function __invoke(CreateDto $requestData): JsonResponse
    {
        $person = $this->service->create($requestData);

        return response()->json(PhysicalPersonDto::from($person));
    }
}
