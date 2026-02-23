<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { Download, Trash2 } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type BackupItem = {
    id: number;
    filename: string;
    created_at: string;
    notes?: string;
    size?: string;
};

type PaginatedData = {
    data: BackupItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = defineProps<{
    backups: PaginatedData;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Backup',
    },
];

function formatDate(value: string) {
    try {
        return new Date(value).toLocaleString();
    } catch {
        return value;
    }
}
</script>

<template>
    <Head title="Backup" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Backup
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage system backups and restore points.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Button type="button">Run Backup</Button>
                    <Button type="button" variant="outline">Upload</Button>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Filename</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Created</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Notes</th>
                                <th class="text-right font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="backup in backups.data"
                                :key="backup.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                    {{ backup.filename }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ formatDate(backup.created_at) }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ backup.notes ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            class="h-8 w-8 p-0"
                                        >
                                            <span class="sr-only">Download</span>
                                            <Download class="size-4" />
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            class="h-8 w-8 p-0 text-red-600 hover:text-red-700"
                                        >
                                            <span class="sr-only">Delete</span>
                                            <Trash2 class="size-4" />
                                        </Button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="!backups.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No backups found.
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="backups.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in backups.links" :key="i">
                    <span
                        v-if="!link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-200 px-3 text-sm text-gray-400 dark:border-neutral-700"
                        v-html="link.label"
                    />
                    <Link
                        v-else
                        :href="link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-3 text-sm transition-colors"
                        :class="link.active
                            ? 'border-[#013CFC] bg-[#013CFC] text-white dark:border-[#60C8FC] dark:bg-[#60C8FC] dark:text-gray-900'
                            : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
