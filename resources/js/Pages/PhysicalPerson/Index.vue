<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {onBeforeMount, ref} from "vue";
import PaginatedInterface from "@/types/paginated.interface";
import PhysicalPersonInterface from "@/types/physical-person.interface";
import PhysicalPersonFormComponent from "@/components/PhysicalPerson/FormComponent.vue";
import ApiRoutes from "@/constants/api-routes";
import OrganizationTypeEnum from "@/enums/organization-type.enum";
import TableOrganiationsComponent from "@/components/PhysicalPerson/TableOrganiationsComponent.vue";

const toggleModalButton = ref<HTMLButtonElement>()
const isLoading = ref(false)

const data = ref<PaginatedInterface<PhysicalPersonInterface>>({
    items: [],
    meta: {
        page: 1,
        perPage: 25,
        itemCount: 0,
        pageCount: 0,
        hasPreviousPage: false,
        hasNextPage: false,
    }
})

onBeforeMount(() => {
    loadPage(1)
})

const loadPage = (page = 1, perPage: number | undefined = 25) => {
    isLoading.value = true

    if (!perPage) {
        perPage = data.value.meta.perPage
    }

    window.axios
        .get<PaginatedInterface<PhysicalPersonInterface>>(ApiRoutes.physicalPersons.index, {
            params: {
                page,
                perPage
            }
        })
        .then((response) => {
            Object.assign(data.value.meta, response.data.meta)
            data.value.items.splice(
                0,
                data.value.items.length,
                ...response.data.items
            )
        })
        .finally(() => {
            isLoading.value = false
        })
}

const closePhysicalPersonModal = () => {
    toggleModalButton.value?.click()
    loadPage(1)
}
</script>

<template>
    <Head title="Physical Persons"/>

    <div class="d-flex flex-column p-2">
        <div class="d-flex flex-row justify-content-between">
            <button ref="toggleModalButton" type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#physicalPersonModal">
                Добавить
            </button>
            <div v-if="isLoading" class="d-flex flex-row align-items-center column-gap-2">
                <div>Загрузка...</div>
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
        <table class="table flex-grow mt-4">
            <thead>
            <tr>
                <th scope="col">Id</th>
                <th scope="col">ИНН</th>
                <th scope="col">Фамилия</th>
                <th scope="col">Имя</th>
                <th scope="col">Отчество</th>
                <th scope="col">Руководитель</th>
                <th scope="col">Учредитель</th>
                <th scope="col">ИП</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="row in data.items" :key="row.id">
                <th scope="row">{{ row.id }}</th>
                <td>{{ row.inn }}</td>
                <td>{{ row.secondName }}</td>
                <td>{{ row.firstName }}</td>
                <td>{{ row.lastName || '-' }}</td>
                <td>
                    <table-organiations-component :person="row" :type="OrganizationTypeEnum.SUPERVISOR"/>
                </td>
                <td>
                    <table-organiations-component :person="row" :type="OrganizationTypeEnum.FOUNDER"/>
                </td>
                <td>
                    <table-organiations-component :person="row" :type="OrganizationTypeEnum.INDIVIDUAL"/>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="physicalPersonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <physical-person-form-component
                    :id="undefined"
                    @saved="closePhysicalPersonModal"
                />
            </div>
        </div>
    </div>
</template>
