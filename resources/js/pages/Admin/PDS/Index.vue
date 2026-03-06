<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'PDS' }];
</script>

<template>
    <Head title="PDS Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div>
                <h1
                    class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                >
                    PDS Management
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    PDS is managed by HR. Use the link below to go to HR PDS.
                </p>
            </div>

            <div
                class="rounded-md border border-gray-200 bg-white p-6 shadow-sm dark:border-neutral-800 dark:bg-neutral-900"
            >
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Personal Data Sheets are reviewed and managed by the HR
                    team.
                </p>
                <Link :href="hr.pds.index().url">
                    <Button variant="outline" class="mt-4"
                        >Go to HR PDS →</Button
                    >
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
