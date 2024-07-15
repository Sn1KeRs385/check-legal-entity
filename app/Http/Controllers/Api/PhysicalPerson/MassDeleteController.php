<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\MassDeleteDto;
use App\Http\Controllers\Controller;
use App\Services\PhysicalPersonService;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class MassDeleteController extends Controller
{
    public function __construct(private readonly PhysicalPersonService $service)
    {
    }

    #[
        OA\Delete(
            path: '/api/physical-persons/mass-delete',
            operationId: 'Mass delete physical persons',
            description: 'Массовое удаление физ. лиц',
            tags: ['Physical person'],
            parameters: [
                new OA\Parameter(
                    name: 'ids[]',
                    description: 'ids - индетификаторы записей, которые нужно удалить',
                    in: 'query',
                    schema: new OA\Schema(type: 'array', items: new OA\Items(type: 'integer')),
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
    public function __invoke(MassDeleteDto $requestData): Response
    {
        $this->service->massDelete($requestData);

        return response()->noContent();
    }
}
