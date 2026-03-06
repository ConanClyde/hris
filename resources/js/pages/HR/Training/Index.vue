<script setup lang="ts">
import { Head, Form, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { Eye, Pencil, Trash2, ChevronsUpDown, Check } from 'lucide-vue-next';
import { ref, watch, computed, onMounted } from 'vue';
import Pagination from '@/components/Pagination.vue';
import TableUserCell from '@/components/TableUserCell.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
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
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useBroadcasting } from '@/composables/useBroadcasting';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type TrainingItem = {
    id: number;
    employee_id: string;
    user_id?: number;
    avatar?: string | null;
    employee_name: string | null;
    title: string;
    provider: string | null;
    date_from: string;
    date_to: string | null;
    time_from: string | null;
    time_to: string | null;
    hours: number | null;
    type: string | null;
    category: string | null;
    fee: number | null;
    participants: string | null;
    status: string;
    created_at: string;
};

type EmployeeOption = { id: string; name: string };

type PaginatedData = {
    data: TrainingItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = withDefaults(
    defineProps<{
        trainings: PaginatedData;
        employees: EmployeeOption[];
        statusOptions: Record<string, string>;
        filters?: {
            search?: string;
            status?: string;
            type?: string;
            category?: string;
        };
    }>(),
    { filters: () => ({}) },
);

const trainings = ref(props.trainings);

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Learning & Development' }];

const page = usePage();
const { trainingsAssignedCount } = useBroadcasting();

if (trainingsAssignedCount.value === null) {
    const base = (page.props.auth?.counts || {}) as Record<string, any>;
    trainingsAssignedCount.value =
        typeof base.trainings_assigned === 'number'
            ? base.trainings_assigned
            : 0;
}

const assignedCountComputed = computed(() => trainingsAssignedCount.value ?? 0);

function safeListen(
    channelName: string,
    event: string,
    callback: (e: any) => void,
) {
    try {
        const echoAny = (window as any)?.Echo;
        if (!echoAny) return;
        const channel =
            echoAny.private?.(channelName) ?? echoAny.channel?.(channelName);
        channel?.listen?.(event, callback);
    } catch {
        // ignore
    }
}

type TrainingStatusUpdatedPayload = {
    id: number;
    employee_id: string;
    employee_name?: string | null;
    status: string;
    title: string;
    date_from: string;
    hours: number;
};

function matchesCurrentFilters(payload: TrainingStatusUpdatedPayload) {
    const search = (searchInput.value || '').trim().toLowerCase();
    const status = (filterStatus.value || '').trim();
    const type = (filterType.value || '').trim();
    const category = (filterCategory.value || '').trim();

    if (status && status !== 'all' && payload.status !== status) return false;
    if (type && !(payload as any).type) {
        // If payload doesn't include type/category, don't try to force-match.
    }
    if (category && !(payload as any).category) {
        // same
    }

    if (!search) return true;
    const hay = [payload.employee_name, payload.employee_id, payload.title]
        .filter(Boolean)
        .join(' ')
        .toLowerCase();
    return hay.includes(search);
}

function upsertOrRemoveFromTable(payload: TrainingStatusUpdatedPayload) {
    const table = trainings.value.data;

    const idx = table.findIndex((t: TrainingItem) => t.id === payload.id);
    const shouldInclude = matchesCurrentFilters(payload);

    if (!shouldInclude) {
        if (idx !== -1) table.splice(idx, 1);
        return;
    }

    const row: TrainingItem = {
        id: payload.id,
        employee_id: payload.employee_id,
        employee_name: payload.employee_name || payload.employee_id,
        title: payload.title,
        provider: null,
        date_from: payload.date_from,
        date_to: null,
        time_from: null,
        time_to: null,
        hours: payload.hours,
        type: null,
        category: null,
        fee: null,
        participants: null,
        status: payload.status,
        created_at: new Date().toISOString(),
    };

    if (idx === -1) {
        table.unshift(row);
        return;
    }

    table[idx] = {
        ...table[idx],
        ...row,
    };
}

onMounted(() => {
    safeListen(
        'training.management',
        '.TrainingStatusUpdated',
        (e: TrainingStatusUpdatedPayload) => {
            upsertOrRemoveFromTable(e);
        },
    );
});

const searchInput = ref(props.filters?.search ?? '');
const filterStatus = ref(props.filters?.status || 'all');
const filterType = ref(props.filters?.type ?? '');
const filterCategory = ref(props.filters?.category ?? '');

const viewDialogOpen = ref(false);
const viewingTraining = ref<TrainingItem | null>(null);
const addDialogOpen = ref(false);
const addEmployeeId = ref('');
const addTitle = ref('');
const addProvider = ref('');
const addDateFrom = ref('');
const addDateTo = ref('');
const addTimeFrom = ref('');
const addTimeTo = ref('');
const addHours = ref('');
const addType = ref('');
const addCategory = ref('');
const addFee = ref('');
const addParticipants = ref('');
const addStatus = ref('pending');

const addEmployeeSearch = ref('');
const addEmployeeDropdownOpen = ref(false);
const filteredEmployees = computed(() => {
    const q = addEmployeeSearch.value.trim().toLowerCase();
    if (!q) return props.employees;
    return props.employees.filter((emp) => emp.name.toLowerCase().includes(q));
});
const selectedEmployeeName = computed(() => {
    if (!addEmployeeId.value) return '';
    const found = props.employees.find((e) => e.id === addEmployeeId.value);
    return found?.name ?? '';
});
function selectEmployee(emp: EmployeeOption) {
    addEmployeeId.value = emp.id;
    addEmployeeSearch.value = '';
    addEmployeeDropdownOpen.value = false;
}

const editDialogOpen = ref(false);
const editTraining = ref<TrainingItem | null>(null);
const deleteModalOpen = ref(false);
const deletingTraining = ref<TrainingItem | null>(null);
const editEmployeeId = ref('');
const editTitle = ref('');
const editProvider = ref('');
const editDateFrom = ref('');
const editDateTo = ref('');
const editTimeFrom = ref('');
const editTimeTo = ref('');
const editHours = ref('');
const editType = ref('');
const editCategory = ref('');
const editFee = ref('');
const editParticipants = ref('');
const editStatus = ref('');

const statusOptionsEntries = Object.entries(props.statusOptions);

watch(
    () => [
        props.filters?.search,
        props.filters?.status,
        props.filters?.type,
        props.filters?.category,
    ],
    ([search, status, type, category]) => {
        searchInput.value = (search as string) ?? '';
        filterStatus.value = (status as string) || 'all';
        filterType.value = (type as string) ?? '';
        filterCategory.value = (category as string) ?? '';
    },
    { immediate: true },
);

let searchDebounce: ReturnType<typeof setTimeout> | null = null;
watch([searchInput, filterStatus, filterType, filterCategory], () => {
    if (searchDebounce) clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => {
        const query: Record<string, string> = {};
        if (searchInput.value) query.search = searchInput.value;
        if (filterStatus.value && filterStatus.value !== 'all')
            query.status = filterStatus.value;
        if (filterType.value) query.type = filterType.value;
        if (filterCategory.value) query.category = filterCategory.value;
        router.get(hr.training.index.url(), query, { preserveState: true });
    }, 300);
});

function openAdd() {
    addEmployeeId.value = '';
    addTitle.value = '';
    addProvider.value = '';
    addDateFrom.value = '';
    addDateTo.value = '';
    addTimeFrom.value = '';
    addTimeTo.value = '';
    addHours.value = '';
    addType.value = '';
    addCategory.value = '';
    addFee.value = '';
    addParticipants.value = '';
    addStatus.value = 'pending';
    addEmployeeSearch.value = '';
    addDialogOpen.value = true;
}

function openView(t: TrainingItem) {
    viewingTraining.value = t;
    viewDialogOpen.value = true;
}
function closeView() {
    viewDialogOpen.value = false;
    viewingTraining.value = null;
}

function openEdit(t: TrainingItem) {
    editTraining.value = t;
    editEmployeeId.value = t.employee_id ?? '';
    editTitle.value = t.title ?? '';
    editProvider.value = t.provider ?? '';
    editDateFrom.value = t.date_from ? String(t.date_from).slice(0, 10) : '';
    editDateTo.value = t.date_to ? String(t.date_to).slice(0, 10) : '';
    editTimeFrom.value = t.time_from ?? '';
    editTimeTo.value = t.time_to ?? '';
    editHours.value = t.hours != null ? String(t.hours) : '';
    editType.value = t.type ?? '';
    editCategory.value = t.category ?? '';
    editFee.value = t.fee != null ? String(t.fee) : '';
    editParticipants.value = t.participants ?? '';
    editStatus.value = t.status ?? 'pending';
    editDialogOpen.value = true;
}

function closeEdit() {
    editDialogOpen.value = false;
    editTraining.value = null;
}

function formatDate(value: string | null) {
    if (!value) return '—';
    try {
        return new Date(value).toLocaleDateString();
    } catch {
        return value;
    }
}

function statusVariant(status: string) {
    if (status === 'approved') return 'default';
    if (status === 'rejected') return 'destructive';
    return 'secondary';
}

function clearFilters() {
    searchInput.value = '';
    filterStatus.value = 'all';
    filterType.value = '';
    filterCategory.value = '';
    router.get(hr.training.index.url());
}

function openDeleteTraining(t: TrainingItem) {
    deletingTraining.value = t;
    deleteModalOpen.value = true;
}
function closeDeleteTraining() {
    deleteModalOpen.value = false;
    deletingTraining.value = null;
}
function confirmDeleteTraining() {
    if (!deletingTraining.value) return;
    router.delete(hr.training.destroy.url(deletingTraining.value.id), {
        onSuccess: () => closeDeleteTraining(),
    });
}
</script>

<template>
    <Head title="Training" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                    >
                        Learning & Development
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage training records and approvals.
                    </p>
                </div>
                <Button @click="openAdd">Add training</Button>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                        >
                            Assigned / Pending Review
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p
                            class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                        >
                            {{ assignedCountComputed }}
                        </p>
                        <p
                            class="mt-2 text-sm font-medium text-amber-600 dark:text-amber-400"
                        >
                            Action required
                        </p>
                    </CardContent>
                </Card>
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
                        placeholder="Search title, provider..."
                        class="h-10"
                    />
                </div>
                <div class="w-[140px]">
                    <Label for="filter-status" class="sr-only">Status</Label>
                    <Select v-model="filterStatus">
                        <SelectTrigger id="filter-status" class="h-10">
                            <SelectValue placeholder="Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Statuses</SelectItem>
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
                <div class="min-w-[120px]">
                    <Label for="filter-type" class="sr-only">Type</Label>
                    <Input
                        id="filter-type"
                        v-model="filterType"
                        type="text"
                        placeholder="Type"
                        class="h-10"
                    />
                </div>
                <div class="min-w-[120px]">
                    <Label for="filter-category" class="sr-only"
                        >Category</Label
                    >
                    <Input
                        id="filter-category"
                        v-model="filterCategory"
                        type="text"
                        placeholder="Category"
                        class="h-10"
                    />
                </div>
                <Button type="button" variant="outline" @click="clearFilters">
                    Clear filters
                </Button>
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
                                    Employee
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Title
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Provider
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Type
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Category
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Date
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Hours
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Status
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
                                v-for="t in trainings.data"
                                :key="t.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="
                                            t.employee_name ||
                                            t.employee_id ||
                                            '—'
                                        "
                                        :avatar="t.avatar"
                                        :subtitle="String(t.employee_id)"
                                        :user-id="t.user_id"
                                    />
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-700 dark:text-gray-300"
                                >
                                    {{ t.title || '—' }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ t.provider || '—' }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ t.type || '—' }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ t.category || '—' }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ formatDate(t.date_from) }}
                                    <span v-if="t.date_to">
                                        – {{ formatDate(t.date_to) }}</span
                                    >
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ t.hours ?? '—' }}
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="statusVariant(t.status)">
                                        {{
                                            statusOptions[t.status] ?? t.status
                                        }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div
                                        class="flex items-center justify-end gap-1"
                                    >
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            title="View"
                                            @click="openView(t)"
                                        >
                                            <Eye class="size-4" />
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            title="Edit"
                                            class="hover:text-primary"
                                            @click="openEdit(t)"
                                        >
                                            <Pencil class="size-4" />
                                        </Button>
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            class="text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300"
                                            title="Delete"
                                            @click="openDeleteTraining(t)"
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
                    v-if="!trainings.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No training records found.
                    <button
                        type="button"
                        class="ml-1 text-brand hover:underline dark:text-brand-light"
                        @click="clearFilters"
                    >
                        Clear filters
                    </button>
                </div>
            </div>

            <Pagination :meta="trainings" />
        </div>

        <!-- View training dialog -->
        <Dialog v-model:open="viewDialogOpen">
            <DialogContent v-if="viewingTraining" class="max-w-3xl">
                <DialogHeader>
                    <DialogTitle>View training</DialogTitle>
                    <DialogDescription class="sr-only">
                        View training record details.
                    </DialogDescription>
                </DialogHeader>
                <div class="max-h-[80vh] space-y-4 overflow-y-auto p-1">
                    <dl class="grid grid-cols-1 gap-3 text-sm">
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Employee
                            </dt>
                            <dd class="mt-0.5">
                                {{
                                    viewingTraining.employee_name ||
                                    viewingTraining.employee_id ||
                                    '—'
                                }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Title
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingTraining.title || '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Provider
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingTraining.provider || '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Inclusive dates
                            </dt>
                            <dd class="mt-0.5">
                                {{ formatDate(viewingTraining.date_from)
                                }}{{
                                    viewingTraining.date_to
                                        ? ` – ${formatDate(viewingTraining.date_to)}`
                                        : ''
                                }}
                                <span
                                    v-if="
                                        viewingTraining.time_from ||
                                        viewingTraining.time_to
                                    "
                                >
                                    (
                                    {{ viewingTraining.time_from ?? '—' }}
                                    -
                                    {{ viewingTraining.time_to ?? '—' }}
                                    )
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Hours
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingTraining.hours ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Type
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingTraining.type ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Category
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingTraining.category ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Fee
                            </dt>
                            <dd class="mt-0.5">
                                {{ viewingTraining.fee ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Participants
                            </dt>
                            <dd class="mt-0.5 whitespace-pre-wrap">
                                {{ viewingTraining.participants ?? '—' }}
                            </dd>
                        </div>
                        <div>
                            <dt
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                Status
                            </dt>
                            <dd class="mt-0.5">
                                <Badge
                                    :variant="
                                        statusVariant(viewingTraining.status)
                                    "
                                    >{{
                                        statusOptions[viewingTraining.status] ??
                                        viewingTraining.status
                                    }}</Badge
                                >
                            </dd>
                        </div>
                    </dl>
                </div>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeView()"
                        >Close</Button
                    >
                    <Button
                        type="button"
                        @click="
                            closeView();
                            openEdit(viewingTraining!);
                        "
                        >Edit</Button
                    >
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Add dialog -->
        <Dialog v-model:open="addDialogOpen">
            <DialogContent :show-close-button="true" class="max-w-3xl">
                <DialogHeader>
                    <DialogTitle>Add training</DialogTitle>
                    <DialogDescription class="sr-only">
                        Add a new training record for an employee.
                    </DialogDescription>
                </DialogHeader>
                <Form
                    v-bind="hr.training.store.form()"
                    class="flex flex-col gap-4"
                    @submit="addDialogOpen = false"
                >
                    <input
                        type="hidden"
                        name="employee_id"
                        :value="addEmployeeId"
                    />
                    <input type="hidden" name="status" :value="addStatus" />
                    <div class="max-h-[80vh] space-y-4 overflow-y-auto p-1">
                        <div class="grid gap-2">
                            <Label for="add-employee">Employee</Label>
                            <Popover v-model:open="addEmployeeDropdownOpen">
                                <PopoverTrigger as-child>
                                    <Button
                                        id="add-employee"
                                        variant="outline"
                                        role="combobox"
                                        :aria-expanded="addEmployeeDropdownOpen"
                                        class="w-full justify-between font-normal"
                                    >
                                        <span
                                            :class="{
                                                'text-muted-foreground':
                                                    !addEmployeeId,
                                            }"
                                        >
                                            {{
                                                selectedEmployeeName ||
                                                'Select employee...'
                                            }}
                                        </span>
                                        <ChevronsUpDown
                                            class="ml-2 h-4 w-4 shrink-0 opacity-50"
                                        />
                                    </Button>
                                </PopoverTrigger>
                                <PopoverContent
                                    class="p-0"
                                    align="start"
                                    :style="{
                                        minWidth:
                                            'var(--reka-popper-anchor-width)',
                                    }"
                                >
                                    <div class="p-2">
                                        <Input
                                            v-model="addEmployeeSearch"
                                            type="search"
                                            placeholder="Search employees..."
                                            class="h-9"
                                        />
                                    </div>
                                    <div class="max-h-60 overflow-y-auto">
                                        <div
                                            v-if="
                                                filteredEmployees.length === 0
                                            "
                                            class="px-4 py-6 text-center text-sm text-muted-foreground"
                                        >
                                            No employees found.
                                        </div>
                                        <button
                                            v-for="emp in filteredEmployees"
                                            :key="emp.id"
                                            type="button"
                                            class="flex w-full items-center gap-2 px-3 py-2 text-sm transition-colors hover:bg-accent hover:text-accent-foreground"
                                            @click="selectEmployee(emp)"
                                        >
                                            <Check
                                                class="h-4 w-4 shrink-0"
                                                :class="
                                                    addEmployeeId === emp.id
                                                        ? 'opacity-100'
                                                        : 'opacity-0'
                                                "
                                            />
                                            <span class="truncate">{{
                                                emp.name
                                            }}</span>
                                        </button>
                                    </div>
                                </PopoverContent>
                            </Popover>
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-title">Title</Label>
                            <Input
                                id="add-title"
                                v-model="addTitle"
                                name="title"
                                type="text"
                                required
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-provider"
                                >Provider (optional)</Label
                            >
                            <Input
                                id="add-provider"
                                v-model="addProvider"
                                name="provider"
                                type="text"
                            />
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
                            <Label for="add-date_to">End date (optional)</Label>
                            <Input
                                id="add-date_to"
                                v-model="addDateTo"
                                name="date_to"
                                type="date"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-time_from"
                                >Start time (optional)</Label
                            >
                            <Input
                                id="add-time_from"
                                v-model="addTimeFrom"
                                name="time_from"
                                type="time"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-time_to">End time (optional)</Label>
                            <Input
                                id="add-time_to"
                                v-model="addTimeTo"
                                name="time_to"
                                type="time"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-hours">Hours (optional)</Label>
                            <Input
                                id="add-hours"
                                v-model="addHours"
                                name="hours"
                                type="number"
                                step="0.5"
                                min="0"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-type">Type (optional)</Label>
                            <Input
                                id="add-type"
                                v-model="addType"
                                name="type"
                                type="text"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-category"
                                >Category (optional)</Label
                            >
                            <Input
                                id="add-category"
                                v-model="addCategory"
                                name="category"
                                type="text"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-fee">Fee (optional)</Label>
                            <Input
                                id="add-fee"
                                v-model="addFee"
                                name="fee"
                                type="number"
                                step="0.01"
                                min="0"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-participants"
                                >Participants (optional)</Label
                            >
                            <Input
                                id="add-participants"
                                v-model="addParticipants"
                                name="participants"
                                type="text"
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            @click="addDialogOpen = false"
                            >Cancel</Button
                        >
                        <Button type="submit">Create</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>

        <!-- Edit dialog -->
        <Dialog v-model:open="editDialogOpen">
            <DialogContent :show-close-button="true" class="max-w-3xl">
                <DialogHeader>
                    <DialogTitle>Edit training</DialogTitle>
                    <DialogDescription class="sr-only">
                        Edit an existing training record.
                    </DialogDescription>
                </DialogHeader>
                <Form
                    v-if="editTraining"
                    :action="hr.training.update.url(editTraining.id)"
                    method="post"
                    class="flex flex-col gap-4"
                    @submit="closeEdit()"
                >
                    <input type="hidden" name="_method" value="PUT" />
                    <input
                        type="hidden"
                        name="employee_id"
                        :value="editEmployeeId"
                    />
                    <input type="hidden" name="status" :value="editStatus" />
                    <div class="max-h-[80vh] space-y-4 overflow-y-auto p-1">
                        <div class="grid gap-2">
                            <Label for="edit-title">Title</Label>
                            <Input
                                id="edit-title"
                                v-model="editTitle"
                                name="title"
                                type="text"
                                required
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-provider"
                                >Provider (optional)</Label
                            >
                            <Input
                                id="edit-provider"
                                v-model="editProvider"
                                name="provider"
                                type="text"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-status">Status</Label>
                            <Select v-model="editStatus">
                                <SelectTrigger id="edit-status">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem
                                        v-for="[
                                            val,
                                            label,
                                        ] in statusOptionsEntries"
                                        :key="val"
                                        :value="val"
                                        >{{ label }}</SelectItem
                                    >
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
                            <Label for="edit-date_to"
                                >End date (optional)</Label
                            >
                            <Input
                                id="edit-date_to"
                                v-model="editDateTo"
                                name="date_to"
                                type="date"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-time_from"
                                >Start time (optional)</Label
                            >
                            <Input
                                id="edit-time_from"
                                v-model="editTimeFrom"
                                name="time_from"
                                type="time"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-time_to"
                                >End time (optional)</Label
                            >
                            <Input
                                id="edit-time_to"
                                v-model="editTimeTo"
                                name="time_to"
                                type="time"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-hours">Hours (optional)</Label>
                            <Input
                                id="edit-hours"
                                v-model="editHours"
                                name="hours"
                                type="number"
                                step="0.5"
                                min="0"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-type">Type (optional)</Label>
                            <Input
                                id="edit-type"
                                v-model="editType"
                                name="type"
                                type="text"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-category"
                                >Category (optional)</Label
                            >
                            <Input
                                id="edit-category"
                                v-model="editCategory"
                                name="category"
                                type="text"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-fee">Fee (optional)</Label>
                            <Input
                                id="edit-fee"
                                v-model="editFee"
                                name="fee"
                                type="number"
                                step="0.01"
                                min="0"
                            />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-participants"
                                >Participants (optional)</Label
                            >
                            <Input
                                id="edit-participants"
                                v-model="editParticipants"
                                name="participants"
                                type="text"
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            @click="closeEdit"
                            >Cancel</Button
                        >
                        <Button type="submit">Save</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>

        <!-- Delete Training Modal -->
        <Dialog
            v-model:open="deleteModalOpen"
            @update:open="(v: boolean) => !v && closeDeleteTraining()"
        >
            <DialogContent
                v-if="deletingTraining"
                :show-close-button="true"
                class="max-w-md"
            >
                <DialogHeader>
                    <DialogTitle>Delete Training Record</DialogTitle>
                    <DialogDescription class="sr-only">
                        Confirm deletion of the training record.
                    </DialogDescription>
                    <p class="mt-0.5 text-sm text-muted-foreground">
                        Are you sure you want to delete
                        <strong>{{ deletingTraining.title }}</strong
                        >? This action cannot be undone.
                    </p>
                </DialogHeader>
                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeDeleteTraining"
                        >Cancel</Button
                    >
                    <Button
                        type="button"
                        variant="destructive"
                        @click="confirmDeleteTraining"
                        >Delete</Button
                    >
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
