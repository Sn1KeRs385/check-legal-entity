<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\Responses\PhysicalPerson\PhysicalPersonDto;
use App\Http\Controllers\Controller;
use App\Models\PhysicalPerson;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class FindController extends Controller
{
    #[
        OA\Get(
            path: '/api/physical-persons/{id}',
            operationId: 'Get physical person by id',
            description: 'Получить физ. лицо по идентификатору',
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
    public function __invoke(int $id): JsonResponse
    {
        $person = PhysicalPerson::query()
            ->findOrFail($id);

        return response()->json(PhysicalPersonDto::from($person));
    }
}
