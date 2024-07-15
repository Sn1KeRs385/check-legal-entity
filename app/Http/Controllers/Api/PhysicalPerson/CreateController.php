<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\CreateDto;
use App\Dto\Responses\PhysicalPerson\PhysicalPersonDto;
use App\Http\Controllers\Controller;
use App\Services\PhysicalPersonService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class CreateController extends Controller
{
    public function __construct(private readonly PhysicalPersonService $service)
    {
    }

    #[
        OA\Post(
            path: '/api/physical-persons',
            operationId: 'Create physical person',
            description: 'Добавить физ. лицо',
            requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: CreateDto::class)),
            tags: ['Physical person'],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Успешный ответ',
                    content: new OA\JsonContent(ref: PhysicalPersonDto::class)
                ),
            ]
        )
    ]
    public function __invoke(CreateDto $requestData): JsonResponse
    {
        $person = $this->service->create($requestData);

        return response()->json(PhysicalPersonDto::from($person));
    }
}
