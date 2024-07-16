<?php

declare(strict_types=1);

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
     * Возвращает только новые организации. В уже существующих обновляет только названия.
     *
     * @return Collection<int, Organization>
     */
    public function __invoke(PhysicalPerson $physicalPerson): Collection
    {
        $returnData = [];

        $personInfo = $this->apiClient->getPhysicalPerson($physicalPerson->inn);

        if ($personInfo->data->individuals) {
            foreach ($personInfo->data->individuals as $individual) {
                $organization = $this->checkInnNotExists(
                    $physicalPerson,
                    OrganizationType::INDIVIDUAL,
                    (int) $individual->inn,
                    $individual->fio
                );

                if ($organization) {
                    $organization->id = 0;
                    $returnData[] = $organization;
                }
            }
        }

        if ($personInfo->data->founders) {
            foreach ($personInfo->data->founders as $founder) {
                $organization = $this->checkInnNotExists(
                    $physicalPerson,
                    OrganizationType::FOUNDER,
                    (int) $founder->inn,
                    $founder->fullName
                );

                if ($organization) {
                    $organization->id = 0;
                    $returnData[] = $organization;
                }
            }
        }

        if ($personInfo->data->supervisors) {
            foreach ($personInfo->data->supervisors as $supervisor) {
                $organization = $this->checkInnNotExists(
                    $physicalPerson,
                    OrganizationType::SUPERVISOR,
                    (int) $supervisor->inn,
                    $supervisor->fullName
                );

                if ($organization) {
                    $organization->id = 0;
                    $returnData[] = $organization;
                }
            }
        }

        return collect($returnData);
    }

    /**
     * Возращает модель организации если ее нет в бд (не сохраняет в бд). Если есть, то вернет null.
     */
    private function checkInnNotExists(
        PhysicalPerson $physicalPerson,
        OrganizationType $organizationType,
        int $inn,
        string $name,
    ): ?Organization {
        $organizationFind = $physicalPerson->organizations()
            ->where('type', $organizationType)
            ->where('inn', $inn)
            ->first();

        if (!$organizationFind) {
            $organization = new Organization();
            $organization->type = $organizationType;
            $organization->inn = $inn;
            $organization->name = $name;

            return $organization;
        }

        return null;
    }
}
