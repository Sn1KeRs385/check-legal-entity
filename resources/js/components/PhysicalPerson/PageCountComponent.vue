<script setup lang="ts">
import PaginationMetaInterface from "@/types/pagination-meta.interface";
import {computed} from "vue";

interface Props {
    meta: PaginationMetaInterface
    perPages?: number[]
}

const props = defineProps<Props>()

const emits = defineEmits<{
    (e: 'onPerPageClick', perPage: number): void
}>()


const paginationMeta = computed(() => props.meta)

const onPerPageClick = (event: Event) => {
    const value = parseInt((event.target as HTMLSelectElement).value)
    if (paginationMeta.value.perPage === value) {
        return
    }
    emits('onPerPageClick', value)
}

const perPageOptions = computed(() => {
    const options: number[] = [];
    if (props.perPages?.length) {
        options.push(...props.perPages)
    } else {
        options.push(...[5, 25, 50, 100, 200])
    }

    if (!options.includes(paginationMeta.value.perPage)) {
        options.push(paginationMeta.value.perPage)
    }

    return options.sort((a, b) => b - a);
})
</script>

<template>
    <select class="form-select" aria-label="Количество записей на странице" @change="onPerPageClick">
        <option
            v-for="option in perPageOptions"
            :value="option"
            :selected="option === paginationMeta.perPage"
        >
            {{ option }}
        </option>
    </select>
</template>
