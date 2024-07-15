<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\Requests\PaginationDto;
use App\Dto\Responses\PhysicalPerson\PhysicalPersonPaginatedDto;
use App\Http\Controllers\Controller;
use App\Models\PhysicalPerson;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;

class PaginatedController extends Controller
{
    #[
        OA\Get(
            path: '/api/physical-persons',
            operationId: 'Paginated physical persons',
            description: 'Получить пагинированный список физ. лиц',
            tags: ['Physical person'],
            parameters: [
                new OA\Parameter(
                    name: 'paginationData',
                    description: "page - номер страницы
\r\nperPage - количество записей на странице",
                    in: 'query',
                    schema: new OA\Schema(ref: PaginationDto::class),
                    explode: true,
                ),
            ],
            responses: [
                new OA\Response(
                    response: 200,
                    description: 'Успешный ответ',
                    content: new OA\JsonContent(ref: PhysicalPersonPaginatedDto::class)
                ),
            ]
        )
    ]
    public function __invoke(PaginationDto $paginationData): JsonResponse
    {
        $paginated = PhysicalPerson::query()
            ->orderByDesc('id')
            ->with(['organizations'])
            ->paginate(perPage: $paginationData->perPage, page: $paginationData->page);

        return response()->json(PhysicalPersonPaginatedDto::from([
            'items' => $paginated->items(),
            'meta' => [
                'page' => $paginated->currentPage(),
                'perPage' => $paginated->perPage(),
                'itemCount' => $paginated->total(),
                'pageCount' => $paginated->lastPage(),
                'hasPreviousPage' => $paginated->currentPage() > 1,
                'hasNextPage' => $paginated->hasMorePages(),
            ],
        ]));
    }
}
