<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import TableUserCell from '@/components/TableUserCell.vue';
import type { BreadcrumbItem } from '@/types';

type ActivityLogItem = {
    id: number;
    user_id: number;
    user_name?: string;
    action: string;
    description?: string;
    ip_address?: string;
    created_at: string;
};

type PaginatedData = {
    data: ActivityLogItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = withDefaults(
    defineProps<{
        logs: PaginatedData;
        filters?: { search?: string; action?: string };
    }>(),
    { filters: () => ({}) }
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Activity Logs',
    },
];

const searchInput = ref(props.filters?.search ?? '');
const filterAction = ref(props.filters?.action ?? 'all');

watch(
    () => [props.filters?.search, props.filters?.action],
    ([search, action]) => {
        searchInput.value = (search as string) ?? '';
        filterAction.value = (action as string) ?? 'all';
    },
    { immediate: true }
);

let debounce: ReturnType<typeof setTimeout> | null = null;
watch([searchInput, filterAction], () => {
    if (debounce) clearTimeout(debounce);
    debounce = setTimeout(() => {
        const query: Record<string, string> = {};
        if (searchInput.value) query.search = searchInput.value;
        if (filterAction.value && filterAction.value !== 'all') query.action = filterAction.value;
        router.get(window.location.pathname, query, { preserveState: true });
    }, 300);
});

function clearFilters() {
    searchInput.value = '';
    filterAction.value = 'all';
    router.get(window.location.pathname);
}

const actionOptions = [
    { value: 'create', label: 'Create' },
    { value: 'update', label: 'Update' },
    { value: 'delete', label: 'Delete' },
    { value: 'login', label: 'Login' },
    { value: 'logout', label: 'Logout' },
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
    <Head title="Activity Logs" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Activity Logs
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Audit trail of system activities and user actions.
                    </p>
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                <div class="min-w-[180px] flex-1">
                    <Label for="search" class="sr-only">Search</Label>
                    <Input
                        id="search"
                        v-model="searchInput"
                        type="search"
                        placeholder="Search user..."
                        class="h-9"
                    />
                </div>
                <div class="w-[160px]">
                    <Label for="filter-action" class="sr-only">Action</Label>
                    <Select v-model="filterAction">
                        <SelectTrigger id="filter-action" class="h-9">
                            <SelectValue placeholder="All actions" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All actions</SelectItem>
                            <SelectItem
                                v-for="opt in actionOptions"
                                :key="opt.value"
                                :value="opt.value"
                            >
                                {{ opt.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <Button type="button" variant="outline" size="sm" class="h-9" @click="clearFilters">
                    Clear filters
                </Button>
            </div>

            <!-- Table -->
            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">User</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Action</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Description</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">IP Address</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Date &amp; Time</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="log in logs.data"
                                :key="log.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="log.user_name ?? `User #${log.user_id}`"
                                        :subtitle="`#${log.user_id}`"
                                    />
                                </td>
                                <td class="px-4 py-3">
                                    <Badge variant="outline">{{ log.action }}</Badge>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ log.description ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ log.ip_address ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ formatDate(log.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="!logs.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No activity logs found.
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="logs.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in logs.links" :key="i">
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
