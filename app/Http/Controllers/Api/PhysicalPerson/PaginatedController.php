<?php

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\Requests\PaginationDto;
use App\Dto\Responses\PhysicalPerson\PhysicalPersonPaginatedDto;
use App\Http\Controllers\Controller;
use App\Models\PhysicalPerson;
use Illuminate\Http\JsonResponse;

class PaginatedController extends Controller
{
    public function __invoke(PaginationDto $paginationData): JsonResponse
    {
        $paginated = PhysicalPerson::query()
            ->orderBy('id')
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
                'hasNextPage' => $paginated->hasMorePages()
            ],
        ]));
    }
}
