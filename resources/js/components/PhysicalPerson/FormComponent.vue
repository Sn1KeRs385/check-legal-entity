<script setup lang="ts">
import {computed, ref, watch} from "vue";
import ApiRoutes from "@/constants/api-routes";
import {AxiosError, AxiosResponse} from "axios";
import PhysicalPersonInterface from "@/types/physical-person.interface";

interface FormItemInterface {
    key: string,
    title: string,
    value: string,
    errors: string[],
    disabled?: () => boolean,
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

const formTitle = computed(() => props.id ? 'Редактирование физ. лица' : 'Добавление физ. лица')

const onSaveClick = () => {
    isLoading.value = true

    const data: { [key: string]: string } = {}
    formItems.value.forEach((formItem) => {
        data[formItem.key] = formItem.value
        formItem.errors.splice(0, formItem.errors.length)
    })
    globalError.value = false

    const thenHook = (response: AxiosResponse<PhysicalPersonInterface>) => {
        reset()
        emits('saved', response.data)
    }
    const errorHook = (error: Error) => {
        if (error instanceof AxiosError && error.response?.data?.errors) {
            Object.keys(error.response.data.errors).forEach((errorKey) => {
                const findFormItem = formItems.value.find((formItem) => formItem.key === errorKey)
                if (findFormItem) {
                    const keyFromResponse = errorKey.replace(/([a-z])([A-Z])/g, '$1 $2').toLowerCase()
                    const messages = ((error.response?.data?.errors?.[errorKey] || []) as string[])
                        .map((message) => message
                            .replace(`${keyFromResponse} `, '')
                            .replace(` ${keyFromResponse}`, ''))
                    findFormItem.errors.splice(0, findFormItem.errors.length, ...messages)
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

            <button type="button" class="btn btn-success mt-4" @click="onSaveClick" :disabled="isLoading">
                <span v-if="!isLoading">Сохранить</span>
                <span v-if="isLoading" class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                <span v-if="isLoading" class="visually-hidden" role="status">Loading...</span>
            </button>
        </form>
    </div>
</template>
