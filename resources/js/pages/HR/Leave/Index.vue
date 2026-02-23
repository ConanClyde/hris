<script setup lang="ts">
import { Head, Link, Form, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { Eye, Pencil, Trash2 } from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
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
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type Application = {
    id: number;
    employee_id: string;
    employee_name: string;
    type: string;
    date_from: string;
    total_days: number;
    reason: string | null;
    status: string;
    created_at: string;
    attachments?: unknown;
};

type EmployeeOption = { id: string; name: string };

type PaginatedData = {
    data: Application[];
    current_page: number;
    last_page: number;
    per_page: number;
    total: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = withDefaults(
    defineProps<{
        applications: PaginatedData;
        employees: EmployeeOption[];
        types: string[];
        statusOptions: Record<string, string>;
        filters?: { search?: string; type?: string; status?: string };
    }>(),
    { filters: () => ({}) }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Leave Applications' },
];

const searchInput = ref(props.filters?.search ?? '');
const filterType = ref(props.filters?.type ?? '');
const filterStatus = ref(props.filters?.status ?? '');

const viewDialogOpen = ref(false);
const viewApplication = ref<Application | null>(null);

const addDialogOpen = ref(false);
const addEmployeeId = ref('');
const addType = ref('');
const addDateFrom = ref('');
const addTotalDays = ref('');
const addReason = ref('');

const editDialogOpen = ref(false);
const editApplication = ref<Application | null>(null);
const editStatus = ref('');
const editType = ref('');
const editDateFrom = ref('');
const editTotalDays = ref('');
const editReason = ref('');

const statusOptionsEntries = computed(() => Object.entries(props.statusOptions));

const exportUrl = computed(() => {
    const q: Record<string, string> = {};
    if (props.filters?.search) q.search = props.filters.search;
    if (props.filters?.type) q.type = props.filters.type;
    if (props.filters?.status) q.status = props.filters.status;
    return hr.leaveApplications.export.url(Object.keys(q).length ? { query: q } : undefined);
});

let searchDebounce: ReturnType<typeof setTimeout> | null = null;
watch(
    () => [props.filters?.search, props.filters?.type, props.filters?.status],
    ([search, type, status]) => {
        searchInput.value = (search as string) ?? '';
        filterType.value = (type as string) ?? '';
        filterStatus.value = (status as string) ?? '';
    },
    { immediate: true }
);

watch([searchInput, filterType, filterStatus], () => {
    if (searchDebounce) clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => {
        const query: Record<string, string> = {};
        if (searchInput.value) query.search = searchInput.value;
        if (filterType.value) query.type = filterType.value;
        if (filterStatus.value) query.status = filterStatus.value;
        router.get(hr.leaveApplications.index.url(), query, { preserveState: true });
    }, 300);
});

function clearFilters() {
    searchInput.value = '';
    filterType.value = '';
    filterStatus.value = '';
    router.get(hr.leaveApplications.index.url());
}

function openView(app: Application) {
    viewApplication.value = app;
    viewDialogOpen.value = true;
}

function openAdd() {
    addEmployeeId.value = '';
    addType.value = '';
    addDateFrom.value = '';
    addTotalDays.value = '';
    addReason.value = '';
    addDialogOpen.value = true;
}

function openEdit(app: Application) {
    editApplication.value = app;
    editStatus.value = app.status;
    editType.value = app.type;
    editDateFrom.value = app.date_from ? String(app.date_from).slice(0, 10) : '';
    editTotalDays.value = String(app.total_days ?? '');
    editReason.value = app.reason ?? '';
    editDialogOpen.value = true;
}

function closeView() {
    viewDialogOpen.value = false;
    viewApplication.value = null;
}

function closeEdit() {
    editDialogOpen.value = false;
    editApplication.value = null;
}

function statusVariant(status: string) {
    if (status === 'approved') return 'default';
    if (status === 'rejected') return 'destructive';
    return 'secondary';
}

function formatDate(value: string | null) {
    if (!value) return '—';
    try {
        return new Date(value).toLocaleDateString();
    } catch {
        return value;
    }
}

function confirmDelete(message: string) {
    return window.confirm(message);
}
</script>

<template>
    <Head title="Leave Applications" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Leave Applications
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage employee leave requests.
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Link
                        :href="exportUrl"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                    >
                        Export CSV
                    </Link>
                    <Button
                        type="button"
                        class="inline-flex items-center gap-2"
                        @click="openAdd"
                    >
                        Apply for Leave
                    </Button>
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
                        placeholder="Search by name, type, reason..."
                        class="h-9"
                    />
                </div>
                <div class="w-[140px]">
                    <Label for="filter-type" class="sr-only">Type</Label>
                    <Select v-model="filterType">
                        <SelectTrigger id="filter-type" class="h-9">
                            <SelectValue placeholder="Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="t in types"
                                :key="t"
                                :value="t"
                            >
                                {{ t }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="w-[140px]">
                    <Label for="filter-status" class="sr-only">Status</Label>
                    <Select v-model="filterStatus">
                        <SelectTrigger id="filter-status" class="h-9">
                            <SelectValue placeholder="Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem
                                v-for="[val, label] in statusOptionsEntries"
                                :key="val"
                                :value="val"
                            >
                                {{ label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    class="h-9"
                    @click="clearFilters"
                >
                    Clear filters
                </Button>
            </div>

            <!-- Table -->
            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[720px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Employee
                                </th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Type
                                </th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Start Date
                                </th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Total Days
                                </th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Attachment
                                </th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Status
                                </th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Submitted
                                </th>
                                <th class="text-right font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="app in applications.data"
                                :key="app.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="app.employee_name || app.employee_id || '—'"
                                        :subtitle="String(app.employee_id)"
                                    />
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ app.type || '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ formatDate(app.date_from) }}
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ app.total_days ?? '—' }}
                                </td>
                                <td class="px-4 py-3 text-gray-500 dark:text-gray-400">
                                    {{ app.attachments && Array.isArray(app.attachments) && app.attachments.length ? 'Yes' : '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="statusVariant(app.status)">
                                        {{ statusOptions[app.status] ?? app.status }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ formatDate(app.created_at) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            class="h-8 w-8 p-0"
                                            @click="openView(app)"
                                        >
                                            <span class="sr-only">View</span>
                                            <Eye class="size-4" />
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            class="h-8 w-8 p-0"
                                            @click="openEdit(app)"
                                        >
                                            <span class="sr-only">Edit</span>
                                            <Pencil class="size-4" />
                                        </Button>
                                        <Form
                                            :action="hr.leaveApplications.destroy.url(app.id)"
                                            method="post"
                                            class="inline"
                                            @submit="(e: Event) => !confirmDelete('Delete this leave application?') && e.preventDefault()"
                                        >
                                            <input type="hidden" name="_method" value="DELETE" />
                                            <Button
                                                type="submit"
                                                variant="ghost"
                                                size="icon-sm"
                                                class="h-8 w-8 p-0 text-red-600 hover:text-red-700 dark:text-red-400"
                                            >
                                                <span class="sr-only">Delete</span>
                                                <Trash2 class="size-4" />
                                            </Button>
                                        </Form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="!applications.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No leave applications found.
                    <button
                        type="button"
                        class="ml-1 text-[#013CFC] hover:underline dark:text-[#60C8FC]"
                        @click="clearFilters"
                    >
                        Clear filters
                    </button>
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="applications.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in applications.links" :key="i">
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

        <!-- View Dialog -->
        <Dialog v-model:open="viewDialogOpen">
            <DialogContent :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Leave application details</DialogTitle>
                </DialogHeader>
                <template v-if="viewApplication">
                    <div class="max-h-[60vh] overflow-y-auto pr-1">
                        <dl class="grid grid-cols-1 gap-2 text-sm">
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Employee</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ viewApplication.employee_name }} ({{ viewApplication.employee_id }})</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Type</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ viewApplication.type }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Start date</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ formatDate(viewApplication.date_from) }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Total days</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ viewApplication.total_days }}</dd>
                        </div>
                        <div>
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Status</dt>
                            <dd>
                                <Badge :variant="statusVariant(viewApplication.status)">
                                    {{ statusOptions[viewApplication.status] ?? viewApplication.status }}
                                </Badge>
                            </dd>
                        </div>
                        <div v-if="viewApplication.reason">
                            <dt class="font-medium text-gray-500 dark:text-gray-400">Reason</dt>
                            <dd class="text-gray-900 dark:text-gray-100">{{ viewApplication.reason }}</dd>
                        </div>
                    </dl>
                    </div>
                    <DialogFooter v-if="viewApplication.status === 'pending'" class="gap-2 sm:gap-0">
                        <Form
                            :action="hr.leaveApplications.update.url(viewApplication.id)"
                            method="post"
                            class="inline"
                            :data="{
                                _method: 'PUT',
                                employee_id: viewApplication.employee_id,
                                status: 'approved',
                                type: viewApplication.type,
                                date_from: viewApplication.date_from,
                                total_days: viewApplication.total_days,
                                reason: viewApplication.reason ?? '',
                            }"
                        >
                            <Button type="submit">Approve</Button>
                        </Form>
                        <Form
                            :action="hr.leaveApplications.update.url(viewApplication.id)"
                            method="post"
                            class="inline"
                            :data="{
                                _method: 'PUT',
                                employee_id: viewApplication.employee_id,
                                status: 'rejected',
                                type: viewApplication.type,
                                date_from: viewApplication.date_from,
                                total_days: viewApplication.total_days,
                                reason: viewApplication.reason ?? '',
                            }"
                        >
                            <Button type="submit" variant="destructive">Reject</Button>
                        </Form>
                    </DialogFooter>
                </template>
            </DialogContent>
        </Dialog>

        <!-- Add Leave Dialog -->
        <Dialog v-model:open="addDialogOpen">
            <DialogContent :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Apply for leave</DialogTitle>
                </DialogHeader>
                <Form
                    v-bind="hr.leaveApplications.store.form()"
                    class="flex flex-col gap-4"
                    @submit="addDialogOpen = false"
                >
                    <input type="hidden" name="employee_id" :value="addEmployeeId" />
                    <input type="hidden" name="type" :value="addType" />
                    <div class="max-h-[60vh] overflow-y-auto p-1 space-y-4">
                    <div class="grid gap-2">
                        <Label for="add-employee">Employee</Label>
                        <Select v-model="addEmployeeId" required>
                            <SelectTrigger id="add-employee">
                                <SelectValue placeholder="Select employee" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="emp in employees"
                                    :key="emp.id"
                                    :value="emp.id"
                                >
                                    {{ emp.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Label for="add-type">Leave type</Label>
                        <Select v-model="addType" required>
                            <SelectTrigger id="add-type">
                                <SelectValue placeholder="Select type" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="t in types"
                                    :key="t"
                                    :value="t"
                                >
                                    {{ t }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Label for="add-date_from">Start date</Label>
                        <Input
                            id="add-date_from"
                            v-model="addDateFrom"
                            name="date_from"
                            type="date"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="add-total_days">Total days</Label>
                        <Input
                            id="add-total_days"
                            v-model="addTotalDays"
                            name="total_days"
                            type="number"
                            step="0.5"
                            min="0.5"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="add-reason">Reason (optional)</Label>
                        <Input
                            id="add-reason"
                            v-model="addReason"
                            name="reason"
                            type="text"
                        />
                    </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="addDialogOpen = false">
                            Cancel
                        </Button>
                        <Button type="submit">Submit</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>

        <!-- Edit Leave Dialog -->
        <Dialog v-model:open="editDialogOpen">
            <DialogContent :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Edit leave application</DialogTitle>
                </DialogHeader>
                <Form
                    v-if="editApplication"
                    :action="hr.leaveApplications.update.url(editApplication.id)"
                    method="post"
                    class="flex flex-col gap-4"
                    @submit="closeEdit()"
                >
                    <input type="hidden" name="_method" value="PUT" />
                    <input type="hidden" name="employee_id" :value="editApplication.employee_id" />
                    <input type="hidden" name="status" :value="editStatus" />
                    <input type="hidden" name="type" :value="editType" />
                    <div class="max-h-[60vh] overflow-y-auto p-1 space-y-4">
                    <div class="grid gap-2">
                        <Label>Employee</Label>
                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ editApplication.employee_name }} ({{ editApplication.employee_id }})</p>
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-status">Status</Label>
                        <Select v-model="editStatus">
                            <SelectTrigger id="edit-status">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="[val, label] in statusOptionsEntries"
                                    :key="val"
                                    :value="val"
                                >
                                    {{ label }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-type">Type</Label>
                        <Select v-model="editType">
                            <SelectTrigger id="edit-type">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="t in types"
                                    :key="t"
                                    :value="t"
                                >
                                    {{ t }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-date_from">Start date</Label>
                        <Input
                            id="edit-date_from"
                            v-model="editDateFrom"
                            name="date_from"
                            type="date"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-total_days">Total days</Label>
                        <Input
                            id="edit-total_days"
                            v-model="editTotalDays"
                            name="total_days"
                            type="number"
                            step="0.5"
                            min="0.5"
                            required
                        />
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-reason">Reason (optional)</Label>
                        <Input
                            id="edit-reason"
                            v-model="editReason"
                            name="reason"
                            type="text"
                        />
                    </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="closeEdit">
                            Cancel
                        </Button>
                        <Button type="submit">Save</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
