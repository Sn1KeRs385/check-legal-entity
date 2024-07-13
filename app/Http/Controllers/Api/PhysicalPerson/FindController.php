<?php

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\Responses\PhysicalPerson\PhysicalPersonDto;
use App\Http\Controllers\Controller;
use App\Models\PhysicalPerson;
use Illuminate\Http\JsonResponse;

class FindController extends Controller
{
    public function __invoke(int $id): JsonResponse
    {
        $person = PhysicalPerson::query()
            ->findOrFail($id);

        return response()->json(PhysicalPersonDto::from($person));
    }
}
