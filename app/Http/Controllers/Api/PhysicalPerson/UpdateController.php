<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\UpdateDto;
use App\Dto\Responses\PhysicalPerson\PhysicalPersonDto;
use App\Http\Controllers\Controller;
use App\Services\PhysicalPersonService;
use Illuminate\Http\JsonResponse;

class UpdateController extends Controller
{
    public function __construct(private readonly PhysicalPersonService $service)
    {
    }

    public function __invoke(UpdateDto $requestData): JsonResponse
    {
        $person = $this->service->update($requestData);

        return response()->json(PhysicalPersonDto::from($person));
    }
}
