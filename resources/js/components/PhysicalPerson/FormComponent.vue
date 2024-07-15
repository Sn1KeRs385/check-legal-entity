<script setup lang="ts">
import {computed, ref, watch} from "vue";
import ApiRoutes from "@/constants/api-routes";
import {AxiosError, AxiosResponse} from "axios";
import PhysicalPersonInterface from "@/types/physical-person.interface";
import OrganizationInterface from "@/types/organization.interface";
import OrganizationTypeEnum from "@/enums/organization-type.enum";
import TableOrganizationsComponent from "@/components/PhysicalPerson/TableOrganizationsComponent.vue";

interface FormItemInterface {
    key: string,
    title: string,
    value: string,
    errors: string[],
    disabled?: () => boolean,
}

interface OrganizationErrors {
    inn: string[]
    name: string[]
    type: string[]
}

interface Props {
    id: number | undefined
}

const props = defineProps<Props>()

const emits = defineEmits<{
    (e: 'saved', physicalPerson: PhysicalPersonInterface): void
}>()
const globalError = ref(false)
const isLoading = ref(false)
const isLoadingForm = ref(false)

const formItems = ref<FormItemInterface[]>([
    {
        key: 'inn',
        title: 'ИНН',
        value: '',
        errors: [],
        disabled: () => !!props.id
    },
    {
        key: 'secondName',
        title: 'Фамилия',
        value: '',
        errors: [],
    },
    {
        key: 'firstName',
        title: 'Имя',
        value: '',
        errors: [],
    },
    {
        key: 'lastName',
        title: 'Отчество',
        value: '',
        errors: [],
    },
])

const organizations = ref<Partial<OrganizationInterface>[]>([])
const organizationErrors = ref<OrganizationErrors[]>([])

const formTitle = computed(() => props.id ? 'Редактирование физ. лица' : 'Добавление физ. лица')

const onSaveClick = () => {
    isLoading.value = true

    const data: { [key: string]: string | Partial<OrganizationInterface>[] } = {}
    formItems.value.forEach((formItem) => {
        data[formItem.key] = formItem.value
        formItem.errors.splice(0, formItem.errors.length)
    })
    data.organizations = organizations.value
    organizationErrors.value.splice(0, organizationErrors.value.length)
    globalError.value = false

    const thenHook = (response: AxiosResponse<PhysicalPersonInterface>) => {
        reset()
        emits('saved', response.data)
    }
    const errorHook = (error: Error) => {
        if (error instanceof AxiosError && error.response?.data?.errors) {
            Object.keys(error.response.data.errors).forEach((errorKey) => {
                if (errorKey.includes('organizations')) {
                    const matches = errorKey.match(/organizations\.([0-9]*)\.([a-z]*)/)
                    if (matches && matches[1] && matches[2]) {
                        const index = parseInt(matches[1])
                        if (!organizationErrors.value[index]) {
                            organizationErrors.value[index] = {inn: [], name: [], type: []}
                        }
                        // @ts-ignore-next-line
                        organizationErrors.value[index][matches[2]] = ((error.response?.data?.errors?.[errorKey] || []) as string[])
                            .map((message) => message.replace(matches[0], ''))
                    }
                } else {
                    const findFormItem = formItems.value.find((formItem) => formItem.key === errorKey)
                    if (findFormItem) {
                        const keyFromResponse = errorKey.replace(/([a-z])([A-Z])/g, '$1 $2').toLowerCase()
                        const messages = ((error.response?.data?.errors?.[errorKey] || []) as string[])
                            .map((message) => message
                                .replace(`${keyFromResponse} `, '')
                                .replace(` ${keyFromResponse}`, ''))
                        findFormItem.errors.splice(0, findFormItem.errors.length, ...messages)
                    }
                }
            })
        } else {
            globalError.value = true
        }
        throw error
    }

    const finallyHook = () => {
        isLoading.value = false
    }

    const id = props.id

    if (id) {
        window.axios
            .put<PhysicalPersonInterface>(ApiRoutes.physicalPersons.byId.replace('{id}', id.toString()), data)
            .then(thenHook)
            .catch(errorHook)
            .finally(finallyHook)
    } else {
        window.axios
            .post<PhysicalPersonInterface>(ApiRoutes.physicalPersons.index, data)
            .then(thenHook)
            .catch(errorHook)
            .finally(finallyHook)
    }
}

const reset = () => {
    formItems.value.forEach((formItem) => {
        formItem.value = ''
        formItem.errors.splice(0, formItem.errors.length)
    })
    organizations.value.splice(0, organizations.value.length)
    globalError.value = false
}

watch(() => props.id, () => {
    reset()

    const id = props.id

    if (id) {
        isLoading.value = true
        isLoadingForm.value = true

        window.axios
            .get<PhysicalPersonInterface>(ApiRoutes.physicalPersons.byId.replace('{id}', id.toString()))
            .then((response) => {
                formItems.value.forEach((item) => {
                    // @ts-ignore-next-line
                    item.value = response.data?.[item.key] || ''
                })
                organizations.value.splice(0, organizations.value.length, ...response.data.organizations)
            })
            .catch((error) => {
                globalError.value = true
                throw error
            })
            .finally(() => {
                isLoading.value = false
                isLoadingForm.value = false
            })
    }
})

const onDeleteOrganizationByIndex = (index: number) => {
    organizations.value.splice(index, 1)
    organizationErrors.value.splice(0, organizationErrors.value.length)
}

const onAddOrganization = () => {
    organizations.value.push({
        id: undefined,
        type: OrganizationTypeEnum.SUPERVISOR,
        name: '',
        inn: undefined,
    })
    organizationErrors.value.splice(0, organizationErrors.value.length)
}
</script>

<template>

    <div class="d-flex flex-column p-4 gap-4">
        <h1 class="fs-5 text-center">{{ formTitle }}</h1>
        <div v-if="globalError" class="text-center text-danger">Ошибка сервера. Повторите позже!</div>
        <div v-if="isLoadingForm" class="spinner-border text-primary align-self-center" role="status">
            <span class="visually-hidden">Загрузка...</span>
        </div>
        <form v-if="!isLoadingForm" class="d-flex flex-column gap-2">
            <div v-for="formItem in formItems" :key="formItem.key">
                <label :for="formItem.key" class="form-label">{{ formItem.title }}</label>
                <input
                    type="text"
                    class="form-control"
                    :class="{'is-invalid': formItem.errors.length}"
                    :id="formItem.key" v-model="formItem.value"
                    :disabled="formItem.disabled ? formItem.disabled() : false"
                >
                <div v-if="formItem.errors.length" class="invalid-feedback d-flex flex-column">
                    <div v-for="(message, index) in formItem.errors" :key="`input_${formItem.key}_error_${index}`">
                        {{ message }}
                    </div>
                </div>
            </div>
            <div class="d-flex flex-row justify-content-between">
                <h3>Связанные организации</h3>

                <button ref="toggleModalButton" type="button" class="btn btn-success" @click="onAddOrganization">
                    Добавить
                </button>
            </div>
            <table class="table table-hover">
                <thead>
                <tr>
                    <th scope="col" style="width: 17%">Тип</th>
                    <th scope="col" style="width: 17%">ИНН</th>
                    <th scope="col">Название</th>
                    <th scope="col" style="width: 3%"></th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(row, index) in organizations" :key="index">
                    <td>
                        <div>
                            <label :for="`type_${index}`" class="form-label">Тип привязки</label>
                            <select
                                class="form-select"
                                aria-label="Тип клиента"
                                v-model="row.type"
                                :class="{'is-invalid': organizationErrors?.[index]?.type.length}"
                            >
                                <option :value="OrganizationTypeEnum.SUPERVISOR">
                                    Руководитель
                                </option>
                                <option :value="OrganizationTypeEnum.FOUNDER">
                                    Учредитель
                                </option>
                                <option :value="OrganizationTypeEnum.INDIVIDUAL">
                                    ИП
                                </option>
                            </select>
                            <div v-if="organizationErrors?.[index]?.type.length"
                                 class="invalid-feedback d-flex flex-column">
                                <div v-for="(message, messageIndex) in organizationErrors[index].type"
                                     :key="`type_${index}_error_${messageIndex}`">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <label :for="`inn_${index}`" class="form-label">ИНН</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid': organizationErrors?.[index]?.inn.length}"
                                :id="`inn_${index}`" v-model="row.inn"
                            >
                            <div v-if="organizationErrors?.[index]?.inn.length"
                                 class="invalid-feedback d-flex flex-column">
                                <div v-for="(message, messageIndex) in organizationErrors[index].inn"
                                     :key="`inn_${index}_error_${messageIndex}`">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>
                            <label :for="`name_${index}`" class="form-label">Наименование</label>
                            <input
                                type="text"
                                class="form-control"
                                :class="{'is-invalid': organizationErrors?.[index]?.name.length}"
                                :id="`name_${index}`" v-model="row.name"
                            >
                            <div v-if="organizationErrors?.[index]?.name.length"
                                 class="invalid-feedback d-flex flex-column">
                                <div v-for="(message, messageIndex) in organizationErrors[index].name"
                                     :key="`inn_${index}_error_${messageIndex}`">
                                    {{ message }}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <button
                            type="button"
                            class="btn btn-danger"
                            @click="onDeleteOrganizationByIndex(index)"
                        >
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                </tbody>
            </table>

            <button type="button" class="btn btn-success mt-4" @click="onSaveClick" :disabled="isLoading">
                <span v-if="!isLoading">Сохранить</span>
                <span v-if="isLoading" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span v-if="isLoading" class="visually-hidden" role="status">Loading...</span>
            </button>
        </form>
    </div>
</template>
