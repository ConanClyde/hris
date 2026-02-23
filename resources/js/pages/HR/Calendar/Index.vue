<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Calendar' },
];
</script>

<template>
    <Head title="Calendar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Calendar
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View leave, training, and holidays. Events are loaded via the calendar events API.
                </p>
            </div>
            <div class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center dark:border-neutral-700 dark:bg-neutral-800/50">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Calendar view (e.g. month grid with leave, training, custom holidays) can be added here. Use <code class="rounded bg-gray-200 px-1.5 py-0.5 text-xs dark:bg-neutral-700">/hr/calendar/events?start=...&amp;end=...</code> to fetch events.
                </p>
            </div>
        </div>
    </AppLayout>
</template>
