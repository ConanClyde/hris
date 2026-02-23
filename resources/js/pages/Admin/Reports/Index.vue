<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Reports',
    },
];
</script>

<template>
    <Head title="Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Reports &amp; Analytics
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Generate insights and export analytics.
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-6">
                <div class="h-4 w-48 rounded bg-gray-100 dark:bg-neutral-800" />
                <div class="h-3 w-96 max-w-full rounded bg-gray-100 dark:bg-neutral-800 mt-3" />
                <div class="h-40 rounded border border-dashed border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-950 mt-6" />
            </div>
        </div>
    </AppLayout>
</template>
