<script setup lang="ts">
import {computed, ref} from "vue";
import ApiRoutes from "@/constants/api-routes";
import {AxiosError} from "axios";

interface FormItemInterface {
    key: string,
    title: string,
    value: string,
    errors: string[],
}

interface Props {
    id: number | undefined
}

const props = defineProps<Props>()

const emits = defineEmits<{
    (e: 'saved'): void
}>()
const globalError = ref(false)
const isLoading = ref(false)

const formItems = ref<FormItemInterface[]>([
    {
        key: 'inn',
        title: 'ИНН',
        value: '',
        errors: [],
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

    window.axios
        .post(ApiRoutes.physicalPersons.index, data)
        .then(() => {
            reset()
            emits('saved')
        })
        .catch((error) => {
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
                throw error
            }
        })
        .finally(() => {
            isLoading.value = false
        })
}

const reset = () => {
    formItems.value.forEach((formItem) => {
        formItem.value = ''
        formItem.errors.splice(0, formItem.errors.length)
    })
    globalError.value = false
}
</script>

<template>

    <div class="d-flex flex-column p-4 gap-4">
        <h1 class="fs-5 text-center">{{ formTitle }}</h1>
        <div v-if="globalError" class="text-center text-danger">Ошибка сервера. Повторите позже!</div>
        <form class="d-flex flex-column gap-2">
            <div v-for="formItem in formItems" :key="formItem.key">
                <label :for="formItem.key" class="form-label">{{ formItem.title }}</label>
                <input type="text" class="form-control" :class="{'is-invalid': formItem.errors.length}"
                       :id="formItem.key" v-model="formItem.value">
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
