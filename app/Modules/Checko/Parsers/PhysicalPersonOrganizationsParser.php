<?php

namespace App\Modules\Checko\Parsers;

use App\Enums\OrganizationType;
use App\Models\Organization;
use App\Models\PhysicalPerson;
use App\Modules\Checko\CheckoApiClient;
use Illuminate\Support\Collection;

class PhysicalPersonOrganizationsParser
{
    public function __construct(private readonly CheckoApiClient $apiClient)
    {
    }

    /**
     * Возвращает только новые организации. В уже существующих обновляет только названия
     *
     * @return Collection<int, Organization>
     */
    public function __invoke(PhysicalPerson $physicalPerson): Collection
    {
        $returnData = [];

        $personInfo = $this->apiClient->getPhysicalPerson($physicalPerson->inn);

        if($personInfo->data->individuals) {
            foreach ($personInfo->data->individuals as $individual) {
                /** @var Organization $organization */
                $organization = $physicalPerson->organizations()
                    ->updateOrCreate([
                        'type' => OrganizationType::INDIVIDUAL,
                        'inn' => (int)$individual->inn,
                    ], [
                        'name' => $individual->fio,
                    ]);

                if ($organization->wasRecentlyCreated) {
                    $returnData[] = $organization;
                }
            }
        }

        if($personInfo->data->founders) {
            foreach ($personInfo->data->founders as $founder) {
                /** @var Organization $organization */
                $organization = $physicalPerson->organizations()
                    ->updateOrCreate([
                        'type' => OrganizationType::FOUNDER,
                        'inn' => (int)$founder->inn,
                    ], [
                        'name' => $founder->fullName,
                    ]);

                if ($organization->wasRecentlyCreated) {
                    $returnData[] = $organization;
                }
            }
        }

        if($personInfo->data->supervisors) {
            foreach ($personInfo->data->supervisors as $supervisor) {
                /** @var Organization $organization */
                $organization = $physicalPerson->organizations()
                    ->updateOrCreate([
                        'type' => OrganizationType::SUPERVISOR,
                        'inn' => (int)$supervisor->inn,
                    ], [
                        'name' => $supervisor->fullName,
                    ]);

                if ($organization->wasRecentlyCreated) {
                    $returnData[] = $organization;
                }
            }
        }

        return collect($returnData);
    }
}
