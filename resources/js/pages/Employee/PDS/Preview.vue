<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

import type { BreadcrumbItem } from '@/types';
defineProps<{ pds?: unknown }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'PDS Preview',
    },
];
</script>

<template>
    <Head title="PDS Preview" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h2 class="text-lg font-semibold">PDS Preview</h2>
        </div>
    </AppLayout>
</template>
