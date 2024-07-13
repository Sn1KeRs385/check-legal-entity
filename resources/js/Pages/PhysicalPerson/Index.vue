<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {onBeforeMount, ref} from "vue";
import PaginatedInterface from "@/types/paginated.interface";
import PhysicalPersonInterface from "@/types/physical-person.interface";
import PhysicalPersonFormComponent from "@/components/PhysicalPerson/FormComponent.vue";
import ApiRoutes from "@/constants/api-routes";
import OrganizationTypeEnum from "@/enums/organization-type.enum";
import TableOrganizationsComponent from "@/components/PhysicalPerson/TableOrganizationsComponent.vue";

interface PhysicalPersonWithActionsLoadedInterface extends PhysicalPersonInterface {
    isDeleting: boolean
}

const toggleModalButton = ref<HTMLButtonElement>()
const isLoading = ref(false)
const editId = ref<number | undefined>()

const data = ref<PaginatedInterface<PhysicalPersonWithActionsLoadedInterface>>({
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
                ...(response.data.items).map((item) => ({...item, isDeleting: false}))
            )
        })
        .finally(() => {
            isLoading.value = false
        })
}

const onClickEditButton = (physicalPerson: PhysicalPersonInterface) => {
    editId.value = physicalPerson.id
    toggleModalButton.value?.click()
}

const onClickDeleteButton = (physicalPerson: PhysicalPersonWithActionsLoadedInterface) => {
    physicalPerson.isDeleting = true

    window.axios
        .delete<PhysicalPersonInterface>(ApiRoutes.physicalPersons.byId.replace('{id}', physicalPerson.id.toString()))
        .then(() => {
            loadPage(1)
        })
        .finally(() => {
            physicalPerson.isDeleting = false
        })
}

const closePhysicalPersonModal = (physicalPerson: PhysicalPersonInterface) => {
    toggleModalButton.value?.click()
    if (editId.value) {
        const searchRow = data.value.items.find((item) => item.id === physicalPerson.id)
        if (searchRow) {
            Object.assign(searchRow, physicalPerson)
        }
    } else {
        loadPage(1)
    }
    editId.value = undefined
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
                    <span class="visually-hidden">Загрузка...</span>
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
                <th scope="col">Действия</th>
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
                    <table-organizations-component :person="row" :type="OrganizationTypeEnum.SUPERVISOR"/>
                </td>
                <td>
                    <table-organizations-component :person="row" :type="OrganizationTypeEnum.FOUNDER"/>
                </td>
                <td>
                    <table-organizations-component :person="row" :type="OrganizationTypeEnum.INDIVIDUAL"/>
                </td>
                <td class="d-flex flex-row column-gap-1">
                    <button type="button" class="btn btn-primary" @click="onClickEditButton(row)">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button type="button" class="btn btn-danger" @click="onClickDeleteButton(row)"
                            :disabled="row.isDeleting">
                        <i v-if="!row.isDeleting" class="bi bi-trash"></i>
                        <div v-else class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Удаление...</span>
                        </div>
                    </button>
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="physicalPersonModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <physical-person-form-component
                    :id="editId"
                    @saved="closePhysicalPersonModal"
                />
            </div>
        </div>
    </div>
</template>
