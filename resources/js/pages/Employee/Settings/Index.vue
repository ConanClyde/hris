<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import SettingsContent from '@/components/SettingsContent.vue';
import employee from '@/routes/employee';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [

    { title: 'Settings' },
];
</script>

<template>
    <Head title="Settings" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Settings
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage your workspace preferences and security settings.
                </p>
            </div>

            <SettingsContent />
        </div>
    </AppLayout>
</template>
