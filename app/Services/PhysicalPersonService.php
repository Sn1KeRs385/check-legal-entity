<?php

namespace App\Services;

use App\Dto\PhysicalPerson\CreateDto;
use App\Dto\PhysicalPerson\DeleteDto;
use App\Dto\PhysicalPerson\MassDeleteDto;
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

        return $physicalPerson;
    }

    public function update(UpdateDto $data): PhysicalPerson
    {
        $physicalPerson = PhysicalPerson::query()
            ->findOrFail($data->id);

        $physicalPerson->fill($data->toArray());

        $physicalPerson->save();

        return $physicalPerson;
    }

    public function delete(DeleteDto $data): void
    {
        DB::transaction(function () use($data) {
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
        DB::transaction(function () use($data) {
            Organization::query()
                ->whereIn('physical_person_id', $data->ids)
                ->delete();
            PhysicalPerson::query()
                ->whereIn('id', $data->ids)
                ->delete();
        });
    }
}
