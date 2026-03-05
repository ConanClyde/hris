<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Cpu, Server, Zap } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type Metrics = {
    memory_peak_mb: number;
    php_version: string;
    laravel_version: string;
};

defineProps<{
    metrics: Metrics;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Performance' },
];
</script>

<template>
    <Head title="Performance" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Performance
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    System metrics for this request.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                            <Cpu class="size-5 text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Peak memory</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ metrics?.memory_peak_mb ?? '—' }} MB</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                            <Server class="size-5 text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">PHP</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ metrics?.php_version ?? '—' }}</p>
                        </div>
                    </div>
                </div>
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary/10">
                            <Zap class="size-5 text-primary" />
                        </div>
                        <div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Laravel</p>
                            <p class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ metrics?.laravel_version ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
