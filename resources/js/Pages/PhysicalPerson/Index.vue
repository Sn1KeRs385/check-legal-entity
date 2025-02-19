<script setup lang="ts">
import {Head} from '@inertiajs/vue3';
import {onBeforeMount, ref} from "vue";
import PaginatedInterface from "@/types/paginated.interface";
import PhysicalPersonInterface from "@/types/physical-person.interface";
import PhysicalPersonFormComponent from "@/components/PhysicalPerson/FormComponent.vue";
import ApiRoutes from "@/constants/api-routes";
import OrganizationTypeEnum from "@/enums/organization-type.enum";
import TableOrganizationsComponent from "@/components/PhysicalPerson/TableOrganizationsComponent.vue";
import PaginationComponent from "@/components/PhysicalPerson/PaginationComponent.vue";
import PageCountComponent from "@/components/PhysicalPerson/PageCountComponent.vue";
import OrganizationsParseResultComponent from "@/components/PhysicalPerson/OrganizationsParseResultComponent.vue";

interface PhysicalPersonWithActionsLoadedInterface extends PhysicalPersonInterface {
    isDeleting: boolean
    isOrganizationParsing: boolean
}

const toggleModalButton = ref<HTMLButtonElement>()
const toggleResultButton = ref<HTMLButtonElement>()
const isLoading = ref(false)
const isMassDeleteLoading = ref(false)
const isOrganizationsParseLoading = ref(false)
const organizationsParseResult = ref<PhysicalPersonInterface[]>([])
const editId = ref<number | undefined>()
const checkedIds = ref<number[]>([])

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

const loadPage = (page = 1, perPage: number | undefined = undefined) => {
    checkedIds.value.splice(0, checkedIds.value.length)

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
                ...(response.data.items).map((item) => ({...item, isDeleting: false, isOrganizationParsing: false}))
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
            searchRow.organizations.splice(0, searchRow.organizations.length, ...physicalPerson.organizations)
        }
    } else {
        loadPage(1)
    }
    editId.value = undefined
}

const onRowClick = (physicalPerson: PhysicalPersonInterface) => {
    if (!checkedIds.value.includes(physicalPerson.id)) {
        checkedIds.value.push(physicalPerson.id)
    } else {
        const findIndex = checkedIds.value.findIndex((id) => id === physicalPerson.id)
        if (findIndex !== -1) {
            checkedIds.value.splice(findIndex, 1)
        }
    }
}

const onMassDelete = () => {
    isMassDeleteLoading.value = true
    window.axios
        .delete(ApiRoutes.physicalPersons.massDelete, {params: {ids: checkedIds.value}})
        .then(() => {
            loadPage(1)
        })
        .finally(() => {
            isMassDeleteLoading.value = false
        })
}

const onOrganizationsParse = () => {
    isOrganizationsParseLoading.value = true
    const physicalPersons = data.value.items.filter(({id}) => checkedIds.value.includes(id))
    physicalPersons.forEach((person) => {
        person.isOrganizationParsing = true
    })
    window.axios
        .post<PhysicalPersonInterface[]>(ApiRoutes.physicalPersons.checkNewLegalEntities, {ids: checkedIds.value})
        .then((response) => {
            organizationsParseResult.value.splice(0, organizationsParseResult.value.length, ...response.data)
            toggleResultButton.value?.click()
            loadPage(data.value.meta.page)
        })
        .finally(() => {
            isOrganizationsParseLoading.value = false
            physicalPersons.forEach((person) => {
                person.isOrganizationParsing = false
            })
        })
}


const onClickRefreshButton = (physicalPerson: PhysicalPersonWithActionsLoadedInterface) => {
    isOrganizationsParseLoading.value = true
    physicalPerson.isOrganizationParsing = true
    window.axios
        .post<PhysicalPersonInterface[]>(ApiRoutes.physicalPersons.checkNewLegalEntities, {ids: [physicalPerson.id]})
        .then((response) => {
            organizationsParseResult.value.splice(0, organizationsParseResult.value.length, ...response.data)
            toggleResultButton.value?.click()
            loadPage(data.value.meta.page)
        })
        .finally(() => {
            isOrganizationsParseLoading.value = false
            physicalPerson.isOrganizationParsing = false
        })
}

const onPageChange = (page: number) => {
    loadPage(page)
}

const onPerPageChange = (perPage: number) => {
    loadPage(1, perPage)
}

const onStartCheckNewLegalEntitiesJob = () => {
    window.axios
        .post(ApiRoutes.physicalPersons.startCheckNewLegalEntitiesJob)
}
</script>

<template>
    <Head title="Physical Persons"/>

    <div class="d-flex flex-column p-2 overflow-hidden" style="height: 100vh">
        <div class="d-flex flex-row justify-content-between">
            <div class="d-flex flex-row column-gap-2">
                <button ref="toggleModalButton" type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#physicalPersonModal">
                    Добавить
                </button>
                <button type="button" class="btn btn-warning"
                        @click="onStartCheckNewLegalEntitiesJob">
                    Запустить массовую проверку
                </button>
            </div>
            <button v-show="false" ref="toggleResultButton" type="button" class="btn btn-success" data-bs-toggle="modal"
                    data-bs-target="#organizationResultModal">
                Отчет
            </button>
            <div v-if="checkedIds.length" class="d-flex flex-row column-gap-4 mt-2">
                <button type="button" class="btn btn-success" @click="onOrganizationsParse">
                    <span v-if="!isOrganizationsParseLoading">Проверить юр. лица</span>
                    <div v-else class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Проверка...</span>
                    </div>
                </button>
                <button type="button" class="btn btn-danger" @click="onMassDelete">
                    <span v-if="!isMassDeleteLoading">Удалить выбранные</span>
                    <div v-else class="spinner-border spinner-border-sm text-primary" role="status">
                        <span class="visually-hidden">Удаление...</span>
                    </div>
                </button>
            </div>
            <div v-if="isLoading" class="d-flex flex-row align-items-center column-gap-2">
                <div>Загрузка...</div>
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Загрузка...</span>
                </div>
            </div>
        </div>
        <div class="mt-4 flex-grow-1 overflow-scroll">
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col" class="sticky-top"></th>
                    <th scope="col" class="sticky-top">Id</th>
                    <th scope="col" class="sticky-top">ИНН</th>
                    <th scope="col" class="sticky-top">Фамилия</th>
                    <th scope="col" class="sticky-top">Имя</th>
                    <th scope="col" class="sticky-top">Отчество</th>
                    <th scope="col" class="sticky-top">Руководитель</th>
                    <th scope="col" class="sticky-top">Учредитель</th>
                    <th scope="col" class="sticky-top">ИП</th>
                    <th scope="col" class="sticky-top">Действия</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="row in data.items" :key="row.id" :class="{'table-active': checkedIds.includes(row.id)}">
                    <td>
                        <input
                            class="form-check-input"
                            type="checkbox"
                            value=""
                            :disabled="isLoading"
                            @click="onRowClick(row)"
                            :checked="checkedIds.includes(row.id)"
                        />
                    </td>
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
                    <td>
                        <div class="d-flex flex-row column-gap-1">
                            <button type="button" class="btn btn-primary" @click="onClickEditButton(row)"
                                    :disabled="isLoading">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <button type="button" class="btn btn-success" @click="onClickRefreshButton(row)"
                                    :disabled="isLoading || row.isOrganizationParsing">
                                <i v-if="!row.isOrganizationParsing" class="bi bi-arrow-clockwise"></i>
                                <span v-else class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Удаление...</span>
                                </span>
                            </button>
                            <button
                                type="button"
                                class="btn btn-danger"
                                @click="onClickDeleteButton(row)"
                                :disabled="row.isDeleting || isLoading"
                            >
                                <i v-if="!row.isDeleting" class="bi bi-trash"></i>
                                <span v-else class="spinner-border spinner-border-sm text-primary" role="status">
                                    <span class="visually-hidden">Удаление...</span>
                                </span>
                            </button>
                        </div>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="d-flex flex-row justify-content-between">
            <pagination-component :meta="data.meta" @on-page-click="onPageChange"/>
            <page-count-component :meta="data.meta" @on-per-page-click="onPerPageChange" style="width: 120px"/>
        </div>
    </div>

    <div class="modal modal-xl fade" id="physicalPersonModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <physical-person-form-component
                    :id="editId"
                    @saved="closePhysicalPersonModal"
                />
            </div>
        </div>
    </div>

    <div class="modal modal-xl fade" id="organizationResultModal" tabindex="-1">
        <div class="modal-dialog model-xl">
            <div class="modal-content">
                <organizations-parse-result-component :physical-persons="organizationsParseResult"/>
            </div>
        </div>
    </div>
</template>
