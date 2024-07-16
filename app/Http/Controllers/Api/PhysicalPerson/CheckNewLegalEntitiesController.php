<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\CheckNewLegalEntitiesDto;
use App\Dto\Responses\PhysicalPerson\PhysicalPersonDto;
use App\Http\Controllers\Controller;
use App\Models\PhysicalPerson;
use App\Modules\Checko\Parsers\PhysicalPersonOrganizationsParser;
use OpenApi\Attributes as OA;
use Throwable;

class CheckNewLegalEntitiesController extends Controller
{
    public function __construct(private readonly PhysicalPersonOrganizationsParser $parser)
    {
    }

    #[
        OA\Post(
            path: '/api/physical-persons/check-new-legal-entities',
            operationId: 'Check new legal entities for  physical persons',
            description: 'Проверить новые юр. лица. В ответе будут только новые организации, а не все',
            requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: CheckNewLegalEntitiesDto::class)),
            tags: ['Physical person'],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Успешный ответ',
                    content: new OA\JsonContent(type: 'array', items: new OA\Items(ref: PhysicalPersonDto::class))
                ),
            ]
        )
    ]
    public function __invoke(CheckNewLegalEntitiesDto $requestData): \Illuminate\Http\JsonResponse
    {
        $physicalPersons = PhysicalPerson::query()
            ->with(['organizations'])
            ->whereIn('id', $requestData->ids)
            ->get();

        $results = [];

        foreach ($physicalPersons as $physicalPerson) {
            try {
                $newOrganizations = $this->parser->__invoke($physicalPerson);
                if (count($newOrganizations) > 0) {
                    $results[] = PhysicalPersonDto::from([
                        ...$physicalPerson->attributesToArray(),
                        'organizations' => $newOrganizations->toArray(),
                    ]);
                }
            } catch (Throwable $exception) {
            }
        }

        return response()->json($results);
    }
}
