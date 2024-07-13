<?php

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\DeleteDto;
use App\Http\Controllers\Controller;
use App\Services\PhysicalPersonService;
use Illuminate\Http\Response;

class DeleteController extends Controller
{
    public function __construct(private readonly PhysicalPersonService $service)
    {
    }

    public function __invoke(DeleteDto $requestData): Response
    {
        $this->service->delete($requestData);

        return response()->noContent();
    }
}
