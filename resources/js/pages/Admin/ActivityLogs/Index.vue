<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Eye, Download, Copy } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';
import TableUserCell from '@/components/TableUserCell.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import type { BreadcrumbItem } from '@/types';

type ActivityLogItem = {
    id: number;
    user_id: number;
    user_name?: string;
    avatar?: string | null;
    role?: string | null;
    action: string;
    description?: string;
    ip_address?: string;
    user_agent?: string | null;
    subject_type?: string | null;
    subject_id?: number | null;
    metadata?: Record<string, unknown> | null;
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
        subjectTypeOptions?: string[];
        filters?: { search?: string; action?: string };
        scoped?: boolean;
        indexUrl?: string;
        exportUrl?: string;
    }>(),
    { filters: () => ({}) },
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Activity Logs',
    },
];

const searchInput = ref(props.filters?.search ?? '');
const filterAction = ref(props.filters?.action ?? 'all');
const filterFrom = ref((props.filters as any)?.from ?? '');
const filterTo = ref((props.filters as any)?.to ?? '');
const filterRole = ref((props.filters as any)?.role ?? 'all');
const isScoped = computed(() => Boolean(props.scoped));
const indexUrl = computed(
    () => props.indexUrl ?? admin.activityLogs.index.url(),
);
const exportUrl = computed(
    () => props.exportUrl ?? admin.activityLogs.export.url(),
);

watch(
    () => [props.filters?.search, props.filters?.action],
    ([search, action]) => {
        searchInput.value = (search as string) ?? '';
        filterAction.value = (action as string) ?? 'all';
    },
    { immediate: true },
);

watch(
    () => props.filters,
    (filters) => {
        const f = (filters ?? {}) as any;
        filterFrom.value = f.from ?? '';
        filterTo.value = f.to ?? '';
        filterRole.value = f.role ?? 'all';
    },
    { immediate: true },
);

let debounce: ReturnType<typeof setTimeout> | null = null;
watch([searchInput, filterAction, filterFrom, filterTo, filterRole], () => {
    if (debounce) clearTimeout(debounce);
    debounce = setTimeout(() => {
        const query: Record<string, string> = {};
        if (searchInput.value) query.search = searchInput.value;
        if (filterAction.value && filterAction.value !== 'all')
            query.action = filterAction.value;
        if (filterFrom.value) query.from = filterFrom.value;
        if (filterTo.value) query.to = filterTo.value;
        if (!isScoped.value && filterRole.value && filterRole.value !== 'all')
            query.role = filterRole.value;
        router.get(indexUrl.value, query, {
            preserveState: true,
        });
    }, 300);
});

function clearFilters() {
    searchInput.value = '';
    filterAction.value = 'all';
    filterFrom.value = '';
    filterTo.value = '';
    filterRole.value = 'all';
    router.get(indexUrl.value);
}

function exportCsv() {
    const query: Record<string, string> = {};
    if (searchInput.value) query.search = searchInput.value;

    if (filterAction.value && filterAction.value !== 'all')
        query.action = filterAction.value;
    if (filterFrom.value) query.from = filterFrom.value;
    if (filterTo.value) query.to = filterTo.value;
    if (!isScoped.value && filterRole.value && filterRole.value !== 'all')
        query.role = filterRole.value;

    const url =
        exportUrl.value +
        (Object.keys(query).length
            ? `?${new URLSearchParams(query).toString()}`
            : '');
    window.open(url, '_blank');
}

const viewLogModalOpen = ref(false);
const viewingLog = ref<ActivityLogItem | null>(null);

function openViewLog(log: ActivityLogItem) {
    viewingLog.value = log;
    viewLogModalOpen.value = true;
}
function closeViewLog() {
    viewLogModalOpen.value = false;
    viewingLog.value = null;
}

const actionOptions = [
    { value: 'create', label: 'Create' },
    { value: 'update', label: 'Update' },
    { value: 'delete', label: 'Delete' },
    { value: 'login', label: 'Login' },
    { value: 'logout', label: 'Logout' },
];

const roleOptions = [
    { value: 'admin', label: 'Admin' },
    { value: 'hr', label: 'HR' },
    { value: 'employee', label: 'Employee' },
];

function activeFilters() {
    const chips: Array<{ key: string; label: string; value: string }> = [];
    if (searchInput.value)
        chips.push({
            key: 'search',
            label: 'Search',
            value: searchInput.value,
        });
    if (filterAction.value && filterAction.value !== 'all')
        chips.push({
            key: 'action',
            label: 'Action',
            value: filterAction.value,
        });
    if (!isScoped.value && filterRole.value && filterRole.value !== 'all')
        chips.push({ key: 'role', label: 'Role', value: filterRole.value });
    if (filterFrom.value)
        chips.push({ key: 'from', label: 'From', value: filterFrom.value });
    if (filterTo.value)
        chips.push({ key: 'to', label: 'To', value: filterTo.value });
    return chips;
}

function removeFilter(key: string) {
    if (key === 'search') searchInput.value = '';
    if (key === 'action') filterAction.value = 'all';
    if (key === 'role') filterRole.value = 'all';
    if (key === 'from') filterFrom.value = '';
    if (key === 'to') filterTo.value = '';
}

function copyToClipboard(value: unknown) {
    try {
        const text =
            typeof value === 'string'
                ? value
                : JSON.stringify(value ?? '', null, 2);
        navigator.clipboard.writeText(text);
    } catch {
        // ignore
    }
}

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
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                    >
                        Activity Logs
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{
                            isScoped
                                ? 'Your activity history and recent actions.'
                                : 'Audit trail of system activities and user actions.'
                        }}
                    </p>
                </div>
                <div v-if="!isScoped" class="flex items-center gap-2">
                    <Button type="button" variant="outline" @click="exportCsv">
                        <Download class="size-4" />
                        Export CSV
                    </Button>
                </div>
            </div>

            <!-- Filters -->
            <div
                class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50"
            >
                <div class="min-w-[180px] flex-1">
                    <Label for="search" class="sr-only">Search</Label>
                    <Input
                        id="search"
                        v-model="searchInput"
                        type="search"
                        placeholder="Search logs..."
                        class="h-10"
                    />
                </div>
                <div class="w-[160px]">
                    <Label for="filter-action" class="sr-only">Action</Label>
                    <Select v-model="filterAction">
                        <SelectTrigger id="filter-action" class="h-10">
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
                <div v-if="!isScoped" class="w-[140px]">
                    <Label for="filter-role" class="sr-only">Role</Label>
                    <Select v-model="filterRole">
                        <SelectTrigger id="filter-role" class="h-10">
                            <SelectValue placeholder="All roles" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All roles</SelectItem>
                            <SelectItem
                                v-for="opt in roleOptions"
                                :key="opt.value"
                                :value="opt.value"
                            >
                                {{ opt.label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="w-[170px]">
                    <Label for="filter-from" class="sr-only">From</Label>
                    <Input
                        id="filter-from"
                        v-model="filterFrom"
                        type="datetime-local"
                        class="h-10"
                    />
                </div>
                <div class="w-[170px]">
                    <Label for="filter-to" class="sr-only">To</Label>
                    <Input
                        id="filter-to"
                        v-model="filterTo"
                        type="datetime-local"
                        class="h-10"
                    />
                </div>
                <Button type="button" variant="outline" @click="clearFilters">
                    Clear filters
                </Button>
            </div>

            <div v-if="activeFilters().length" class="flex flex-wrap gap-2">
                <button
                    v-for="chip in activeFilters()"
                    :key="chip.key"
                    type="button"
                    class="inline-flex items-center gap-2 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs text-gray-600 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-900 dark:text-gray-300 dark:hover:bg-neutral-800"
                    @click="removeFilter(chip.key)"
                >
                    <span class="font-medium">{{ chip.label }}:</span>
                    <span class="max-w-[240px] truncate">{{ chip.value }}</span>
                </button>
            </div>

            <!-- Table -->
            <div
                class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
            >
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[640px] border-collapse text-sm">
                        <thead
                            class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50"
                        >
                            <tr>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    User
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Action
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Description
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    IP Address
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Date &amp; Time
                                </th>
                                <th
                                    class="px-4 py-3 text-right font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-neutral-700"
                        >
                            <tr
                                v-for="log in logs.data"
                                :key="log.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="
                                            log.user_name ??
                                            `User ID: ${log.user_id}`
                                        "
                                        :avatar="log.avatar"
                                        :subtitle="`User ID: ${log.user_id}`"
                                        :user-id="log.user_id"
                                    />
                                </td>
                                <td class="px-4 py-3">
                                    <Badge variant="outline">{{
                                        log.action
                                    }}</Badge>
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ log.description ?? '—' }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ log.ip_address ?? '—' }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ formatDate(log.created_at) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="icon-sm"
                                        title="View"
                                        @click="openViewLog(log)"
                                    >
                                        <Eye class="size-4" />
                                    </Button>
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
        </div>

        <!-- View log modal -->
        <Dialog v-model:open="viewLogModalOpen">
            <DialogContent v-if="viewingLog" class="max-w-3xl">
                <DialogHeader>
                    <DialogTitle>Activity log</DialogTitle>
                    <DialogDescription class="sr-only">
                        View details of the selected activity log entry.
                    </DialogDescription>
                </DialogHeader>
                <div class="max-h-[60vh] space-y-4 overflow-y-auto p-1">
                    <dl class="grid grid-cols-1 gap-3 text-sm sm:grid-cols-2">
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                User
                            </dt>
                            <dd class="mt-0.5">
                                {{
                                    viewingLog.user_name ??
                                    `User ID: ${viewingLog.user_id}`
                                }}
                            </dd>
                        </div>
                        <div v-if="viewingLog.role">
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Role
                            </dt>
                            <dd class="mt-0.5">{{ viewingLog.role }}</dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Action
                            </dt>
                            <dd class="mt-0.5">
                                <Badge variant="outline">{{
                                    viewingLog.action
                                }}</Badge>
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Date &amp; time
                            </dt>
                            <dd class="mt-0.5">
                                {{ formatDate(viewingLog.created_at) }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                IP address
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingLog.ip_address ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                User agent
                            </dt>
                            <dd class="mt-0.5 break-words">
                                {{ viewingLog.user_agent ?? '—' }}
                            </dd>
                        </div>
                        <div
                            v-if="
                                viewingLog.subject_type || viewingLog.subject_id
                            "
                        >
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Subject
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingLog.subject_type ?? '—' }}
                                <span
                                    v-if="viewingLog.subject_id"
                                    class="text-gray-500 dark:text-gray-400"
                                >
                                    User ID: {{ viewingLog.subject_id }}
                                </span>
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Description
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingLog.description ?? '—' }}
                            </dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Metadata
                            </dt>
                            <dd class="mt-1">
                                <div class="flex justify-end">
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="icon-sm"
                                        title="Copy"
                                        @click="
                                            copyToClipboard(viewingLog.metadata)
                                        "
                                    >
                                        <Copy class="size-4" />
                                    </Button>
                                </div>
                                <pre
                                    class="mt-2 max-h-48 overflow-auto rounded-md border border-gray-200 bg-gray-50 p-3 text-xs text-gray-700 dark:border-neutral-700 dark:bg-neutral-900 dark:text-gray-300"
                                    >{{
                                        JSON.stringify(
                                            viewingLog.metadata,
                                            null,
                                            2,
                                        )
                                    }}</pre
                                >
                            </dd>
                        </div>
                    </dl>
                </div>
                <DialogFooter>
                    <Button @click="closeViewLog()">Close</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
