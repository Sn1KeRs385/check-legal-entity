<?php

namespace App\Http\Controllers\Api\PhysicalPerson;

use App\Dto\PhysicalPerson\OrganizationsParseDto;
use App\Dto\Responses\PhysicalPerson\PhysicalPersonDto;
use App\Http\Controllers\Controller;
use App\Models\PhysicalPerson;
use App\Modules\Checko\Parsers\PhysicalPersonOrganizationsParser;
use Illuminate\Http\Response;
use Throwable;

class OrganizationsParseController extends Controller
{
    public function __construct(private readonly PhysicalPersonOrganizationsParser $parser)
    {
    }

    public function __invoke(OrganizationsParseDto $requestData): \Illuminate\Http\JsonResponse
    {
        $physicalPersons = PhysicalPerson::query()
            ->with(['organizations'])
            ->whereIn('id', $requestData->ids)
            ->get();

        $results = [];

        foreach($physicalPersons as $physicalPerson) {
            try {
                $newOrganizations = $this->parser->__invoke($physicalPerson);
                if (count($newOrganizations) > 0) {
                    $results[] = PhysicalPersonDto::from([
                        ...($physicalPerson->attributesToArray()),
                        'organizations' => $newOrganizations->toArray(),
                    ]);
                }
            } catch (Throwable $exception) {

            }
        }

        return response()->json($results);
    }
}
