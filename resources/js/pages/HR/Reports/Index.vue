<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports' },
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
                    View and export HR reports. Additional report types can be added here.
                </p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center dark:border-neutral-700 dark:bg-neutral-800/50">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Reports placeholder. Add leave reports, training reports, or other analytics as needed.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
