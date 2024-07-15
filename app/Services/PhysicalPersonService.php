<?php

declare(strict_types=1);

namespace App\Services;

use App\Dto\PhysicalPerson\CreateDto;
use App\Dto\PhysicalPerson\DeleteDto;
use App\Dto\PhysicalPerson\MassDeleteDto;
use App\Dto\PhysicalPerson\OrganizationUpdateCreateDto;
use App\Dto\PhysicalPerson\UpdateDto;
use App\Models\Organization;
use App\Models\PhysicalPerson;
use Illuminate\Support\Facades\DB;

class PhysicalPersonService
{
    public function create(CreateDto $data): PhysicalPerson
    {
        $physicalPerson = new PhysicalPerson();

        $physicalPerson->fill($data->toArray());

        $physicalPerson->save();

        if (null !== $data->organizations) {
            $physicalPerson->organizations()->createMany($data->organizations->toArray());
        }

        return $physicalPerson;
    }

    public function update(UpdateDto $data): PhysicalPerson
    {
        $physicalPerson = PhysicalPerson::query()
            ->findOrFail($data->id);

        $physicalPerson->fill($data->toArray());

        $physicalPerson->save();

        if (null !== $data->organizations) {
            $existsIds = [];
            foreach ($data->organizations as $organization) {
                /** @var OrganizationUpdateCreateDto $organization */
                $organizationModel = null;
                if (is_int($organization->id)) {
                    /** @var Organization $organizationModel */
                    $organizationModel = $physicalPerson->organizations()
                        ->findOrFail($organization->id);
                } else {
                    $organizationModel = new Organization();
                    $organizationModel->physical_person_id = $physicalPerson->id;
                }

                $organizationModel->fill($organization->toArray());
                $organizationModel->save();

                $existsIds[] = $organization->id;
            }

            $physicalPerson->organizations()
                ->whereNotIn('id', $existsIds)
                ->delete();
        }

        $physicalPerson->refresh();

        return $physicalPerson;
    }

    public function delete(DeleteDto $data): void
    {
        DB::transaction(function () use ($data): void {
            Organization::query()
                ->where('physical_person_id', $data->id)
                ->delete();
            PhysicalPerson::query()
                ->where('id', $data->id)
                ->delete();
        });
    }

    public function massDelete(MassDeleteDto $data): void
    {
        DB::transaction(function () use ($data): void {
            Organization::query()
                ->whereIn('physical_person_id', $data->ids)
                ->delete();
            PhysicalPerson::query()
                ->whereIn('id', $data->ids)
                ->delete();
        });
    }
}
