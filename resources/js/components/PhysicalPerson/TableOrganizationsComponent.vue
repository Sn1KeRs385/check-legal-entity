<script setup lang="ts">
import {computed, ref} from "vue";
import ApiRoutes from "@/constants/api-routes";
import {AxiosError} from "axios";
import OrganizationTypeEnum from "@/enums/organization-type.enum";
import PhysicalPersonInterface from "@/types/physical-person.interface";

interface Props {
    person: PhysicalPersonInterface
    type: OrganizationTypeEnum
}

const SHOW_ORGANIZATION_COUNT = 2;

const props = defineProps<Props>()

const isOpened = ref(false)

const fullList = computed(() => props.person.organizations.filter((organization) => organization.type === props.type));

const toShow = computed(() => isOpened.value ? fullList.value : fullList.value.slice(0, SHOW_ORGANIZATION_COUNT))
const toShowText = computed(() => toShow.value.map((organization) => ` (${organization.inn}) ${organization.name}`).join('; '))

const canOpened = computed(() => !isOpened.value && fullList.value.length > toShow.value.length)
</script>

<template>

    <div v-if="toShow.length" class="d-flex flex-column">
        <div v-for="organization in toShow" :key="organization.inn">
            ({{ organization.inn }})
            {{ organization.name }}
        </div>
        <div v-if="canOpened" class="text-decoration-underline text-secondary" style="cursor: pointer;"
             @click="isOpened = true">
            Еще {{ fullList.length - toShow.length }}
        </div>
        <div v-if="!canOpened && isOpened" class="text-decoration-underline text-secondary" style="cursor: pointer;"
             @click="isOpened = false">
            Меньше
        </div>
    </div>
    <div v-else class="text-secondary">Нет</div>
</template>
