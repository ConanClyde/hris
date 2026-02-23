<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

import type { BreadcrumbItem } from '@/types';
defineProps<{ trainings?: unknown }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Learning & Development',
    },
];
</script>

<template>
    <Head title="Learning & Development" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h2 class="text-lg font-semibold">Learning & Development</h2>
        </div>
    </AppLayout>
</template>
