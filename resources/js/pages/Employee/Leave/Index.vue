<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

import type { BreadcrumbItem } from '@/types';
defineProps<{ applications?: unknown }>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Leave Applications',
    },
];
</script>

<template>
    <Head title="Leave Applications" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-4">
            <h2 class="text-lg font-semibold">Leave Applications</h2>
        </div>
    </AppLayout>
</template>
