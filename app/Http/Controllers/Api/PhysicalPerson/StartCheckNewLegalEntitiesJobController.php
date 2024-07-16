<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Http\Controllers\Controller;
use App\Jobs\CheckNewLegalEntitiesJob;
use Illuminate\Http\Response;
use OpenApi\Attributes as OA;

class StartCheckNewLegalEntitiesJobController extends Controller
{
    #[
        OA\Post(
            path: '/api/physical-persons/start-check-new-legal-entities-job',
            operationId: 'Start job check new legal entities for  physical persons',
            description: 'Ручной запуск процедуры массовой проверки всех физ. лиц на новые юр. лица. Результат придет в письме',
            tags: ['Physical person'],
            responses: [
                new OA\Response(
                    response: 204,
                    description: 'Успешный ответ',
                ),
            ]
        )
    ]
    public function __invoke(): Response
    {
        CheckNewLegalEntitiesJob::dispatch();

        return response()->noContent();
    }
}
