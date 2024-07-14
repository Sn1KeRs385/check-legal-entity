<script setup lang="ts">
import {computed} from "vue";
import PhysicalPersonInterface from "@/types/physical-person.interface";
import OrganizationTypeEnum from "@/enums/organization-type.enum";

interface Props {
    physicalPersons: PhysicalPersonInterface[]
}

defineProps<Props>()

const physicalPersonName = computed(() => (person: PhysicalPersonInterface) => `${person.secondName} ${person.firstName} ${person.lastName || ''}`)
const supervisorOrganizations = computed(() =>
    (person: PhysicalPersonInterface) => person.organizations.filter((organization) => organization.type === OrganizationTypeEnum.SUPERVISOR)
)
const founderOrganizations = computed(() =>
    (person: PhysicalPersonInterface) => person.organizations.filter((organization) => organization.type === OrganizationTypeEnum.FOUNDER)
)
const individualOrganizations = computed(() =>
    (person: PhysicalPersonInterface) => person.organizations.filter((organization) => organization.type === OrganizationTypeEnum.INDIVIDUAL)
)
</script>

<template>
    <div class="d-flex flex-column p-4 gap-4">
        <h1 class="fs-5 text-center">Отчет по выявленным юр. лицам</h1>
        <div v-if="physicalPersons.length === 0">Новых организаций не выявлено</div>
        <table v-else class="table table-hover">
            <thead>
            <tr>
                <th scope="col" class="sticky-top">ФИО</th>
                <th scope="col" class="sticky-top">ИНН</th>
                <th scope="col" class="sticky-top">Руководитель</th>
                <th scope="col" class="sticky-top">Учредитель</th>
                <th scope="col" class="sticky-top">ИП</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="row in physicalPersons" :key="row.id">
                <td>{{ physicalPersonName(row) }}</td>
                <td>{{ row.inn }}</td>
                <td>
                    <div class="d-flex flex-column">
                        <div v-for="organization in supervisorOrganizations(row)">{{ organization.name }}</div>
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <div v-for="organization in founderOrganizations(row)">{{ organization.name }}</div>
                    </div>
                </td>
                <td>
                    <div class="d-flex flex-column">
                        <div v-for="organization in individualOrganizations(row)">{{ organization.name }}</div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</template>
