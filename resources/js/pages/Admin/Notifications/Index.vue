<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Button } from '@/components/ui/button';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Notifications',
    },
];
</script>

<template>
    <Head title="Notifications" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4 max-w-4xl mx-auto">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Notifications
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Stay updated with the latest alerts and activities.
                    </p>
                </div>
                <Button type="button" variant="outline" disabled>Mark all as read</Button>
            </div>

            <div class="space-y-3">
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4 flex items-start gap-3">
                    <div class="shrink-0 w-10 h-10 rounded-full bg-blue-50 text-blue-600 dark:bg-blue-900/20 dark:text-blue-400 flex items-center justify-center" />
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <div class="h-4 w-40 rounded bg-gray-100 dark:bg-neutral-800" />
                            <div class="h-3 w-16 rounded bg-gray-100 dark:bg-neutral-800" />
                        </div>
                        <div class="h-3 w-full rounded bg-gray-100 dark:bg-neutral-800" />
                        <div class="h-3 w-2/3 rounded bg-gray-100 dark:bg-neutral-800 mt-2" />
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4 flex items-start gap-3">
                    <div class="shrink-0 w-10 h-10 rounded-full bg-amber-50 text-amber-600 dark:bg-amber-900/20 dark:text-amber-400 flex items-center justify-center" />
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <div class="h-4 w-48 rounded bg-gray-100 dark:bg-neutral-800" />
                            <div class="h-3 w-16 rounded bg-gray-100 dark:bg-neutral-800" />
                        </div>
                        <div class="h-3 w-full rounded bg-gray-100 dark:bg-neutral-800" />
                        <div class="h-3 w-1/2 rounded bg-gray-100 dark:bg-neutral-800 mt-2" />
                    </div>
                </div>

                <div class="hidden rounded-lg border border-dashed border-gray-200 dark:border-neutral-700 p-8 text-center">
                    <div class="w-12 h-12 rounded bg-gray-100 dark:bg-neutral-800 flex items-center justify-center mx-auto mb-3" />
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">No new notifications</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">We'll notify you when something important happens.</p>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
