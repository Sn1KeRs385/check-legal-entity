<script setup lang="ts">
import PaginationMetaInterface from "@/types/pagination-meta.interface";
import {computed} from "vue";

interface Props {
    meta: PaginationMetaInterface
}

const props = defineProps<Props>()

const emits = defineEmits<{
    (e: 'onPageClick', page: number): void
}>()


const paginationMeta = computed(() => props.meta)

const onPageClick = (page: number) => {
    if (paginationMeta.value.page === page) {
        return
    }
    emits('onPageClick', page)
}

const pageButtons = computed(() => {
    const buttons: [number | null] = [1]

    /**
     * Многоточие после первой страницы только если текущая страница > 3
     * Иначе там сразу 2 будет без троеточия
     */
    if (paginationMeta.value.page > 3) {
        buttons.push(null)
    }

    /**
     * Кнопку предыдущей страницы вставляем только если текущая больше чем 2
     * Иначе кнопка первой страницы уже есть в массиве
     */
    if (paginationMeta.value.page > 2) {
        buttons.push(paginationMeta.value.page - 1)
    }

    /**
     * Кнопку текущей страницы вставляем только если это не первая и не последняя
     * Первая и последняя кнопки страниц по другой логике вставляются
     */
    if (paginationMeta.value.page !== 1 && paginationMeta.value.page !== paginationMeta.value.pageCount) {
        buttons.push(paginationMeta.value.page)
    }

    /**
     * Кнопку следующей страницы ставим если текущая не последняя и не предпоследняя
     */
    if (paginationMeta.value.page < paginationMeta.value.pageCount - 1) {
        buttons.push(paginationMeta.value.page + 1)
    }

    /**
     * Многоточие перед последней страницей ставим только текущая ни одна из трех последних
     */
    if (paginationMeta.value.page < paginationMeta.value.pageCount - 2) {
        buttons.push(null)
    }

    if(paginationMeta.value.pageCount > 1) {
        buttons.push(paginationMeta.value.pageCount)
    }

    return buttons
})
</script>

<template>
    <nav aria-label="Пагинация" class="align-self-center">
        <ul class="pagination">
            <li class="page-item" :class="{'disabled': !paginationMeta.hasPreviousPage}"
                @click="onPageClick(paginationMeta.page - 1)">
                <span
                    class="page-link"
                    aria-label="Назад"
                    :style="{'cursor': paginationMeta.hasPreviousPage ? 'pointer' : ''}"
                >
                    <span aria-hidden="true">&laquo;</span>
                </span>
            </li>
            <template v-for="(button, index) in pageButtons" :key="`pagination_button_${index}`">
                <li
                    v-if="button"
                    class="page-item"
                    :class="{'disabled': button === paginationMeta.page}"
                    @click="onPageClick(button)"
                >
                    <span
                        class="page-link"
                        :style="{'cursor': button !== paginationMeta.page ? 'pointer' : ''}"
                    >
                        {{ button }}
                    </span>
                </li>
                <span v-else class="mx-2 align-self-end pb-1">...</span>
            </template>
            <li
                class="page-item"
                :class="{'disabled': !paginationMeta.hasNextPage}"
                @click="onPageClick(paginationMeta.page + 1)"
            >
                <span
                    class="page-link"
                    aria-label="Вперед"
                    :style="{'cursor': paginationMeta.hasNextPage ? 'pointer' : ''}"
                >
                    <span aria-hidden="true">&raquo;</span>
                </span>
            </li>
        </ul>
    </nav>
</template>
