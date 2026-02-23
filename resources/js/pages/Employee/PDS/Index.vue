<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

import type { BreadcrumbItem } from '@/types';
defineProps<{ pds?: unknown }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'PDS',
    },
];
</script>

<template>
    <Head title="PDS" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h2 class="text-lg font-semibold">PDS</h2>
        </div>
    </AppLayout>
</template>
