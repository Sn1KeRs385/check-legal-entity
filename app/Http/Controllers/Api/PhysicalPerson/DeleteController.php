<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\DeleteDto;
use App\Http\Controllers\Controller;
use App\Services\PhysicalPersonService;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class DeleteController extends Controller
{
    public function __construct(private readonly PhysicalPersonService $service)
    {
    }

    #[
        OA\Delete(
            path: '/api/physical-persons/{id}',
            operationId: 'Delete physical person by id',
            description: 'Удалить физ. лицо по идентификатору',
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
                    response: 204,
                    description: 'Успешный ответ',
                ),
            ]
        )
    ]
    public function __invoke(DeleteDto $requestData): Response
    {
        $this->service->delete($requestData);

        return response()->noContent();
    }
}
