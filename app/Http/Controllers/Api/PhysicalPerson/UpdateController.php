<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\UpdateDto;
use App\Dto\Responses\PhysicalPerson\PhysicalPersonDto;
use App\Http\Controllers\Controller;
use App\Services\PhysicalPersonService;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class UpdateController extends Controller
{
    public function __construct(private readonly PhysicalPersonService $service)
    {
    }

    #[
        OA\Put(
            path: '/api/physical-persons/{id}',
            operationId: 'Update physical person by id',
            description: 'Обновить данные физ. лица по идентификатору',
            requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: UpdateDto::class)),
            tags: ['Physical person'],
            parameters: [
                new OA\Parameter(
                    parameter: 'id',
                    name: 'id',
                    description: 'Идентификатор физ. лица',
                    in: 'path',
                    required: true,
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Успешный ответ',
                    content: new OA\JsonContent(ref: PhysicalPersonDto::class)
                ),
            ]
        )
    ]
    public function __invoke(UpdateDto $requestData): JsonResponse
    {
        $person = $this->service->update($requestData);

        return response()->json(PhysicalPersonDto::from($person));
    }
}
