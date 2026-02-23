<script setup lang="ts">
import { Head, Link, Form, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Pencil, Trash2 } from 'lucide-vue-next';
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

type TrainingItem = {
    id: number;
    employee_id: string;
    employee_name: string | null;
    title: string;
    provider: string | null;
    date_from: string;
    date_to: string | null;
    hours: number | null;
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
        filters?: { search?: string; status?: string };
    }>(),
    { filters: () => ({}) }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Learning & Development' },
];

const searchInput = ref(props.filters?.search ?? '');
const filterStatus = ref(props.filters?.status || 'all');


const addDialogOpen = ref(false);
const addEmployeeId = ref('');
const addTitle = ref('');
const addProvider = ref('');
const addDateFrom = ref('');
const addDateTo = ref('');
const addHours = ref('');
const addStatus = ref('pending');

const editDialogOpen = ref(false);
const editTraining = ref<TrainingItem | null>(null);
const editEmployeeId = ref('');
const editTitle = ref('');
const editProvider = ref('');
const editDateFrom = ref('');
const editDateTo = ref('');
const editHours = ref('');
const editStatus = ref('');

const statusOptionsEntries = Object.entries(props.statusOptions);

watch(
    () => [props.filters?.search, props.filters?.status],
    ([search, status]) => {
        searchInput.value = (search as string) ?? '';
        filterStatus.value = (status as string) || 'all';
    },
    { immediate: true }
);


let searchDebounce: ReturnType<typeof setTimeout> | null = null;
watch([searchInput, filterStatus], () => {
    if (searchDebounce) clearTimeout(searchDebounce);
    searchDebounce = setTimeout(() => {
        const query: Record<string, string> = {};
        if (searchInput.value) query.search = searchInput.value;
        if (filterStatus.value && filterStatus.value !== 'all') query.status = filterStatus.value;
        router.get(hr.training.index.url(), query, { preserveState: true });

    }, 300);
});

function openAdd() {
    addEmployeeId.value = '';
    addTitle.value = '';
    addProvider.value = '';
    addDateFrom.value = '';
    addDateTo.value = '';
    addHours.value = '';
    addStatus.value = 'pending';
    addDialogOpen.value = true;
}

function openEdit(t: TrainingItem) {
    editTraining.value = t;
    editEmployeeId.value = t.employee_id ?? '';
    editTitle.value = t.title ?? '';
    editProvider.value = t.provider ?? '';
    editDateFrom.value = t.date_from ? String(t.date_from).slice(0, 10) : '';
    editDateTo.value = t.date_to ? String(t.date_to).slice(0, 10) : '';
    editHours.value = t.hours != null ? String(t.hours) : '';
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
    router.get(hr.training.index.url());
}


function confirmDelete(message: string) {
    return window.confirm(message);
}
</script>

<template>
    <Head title="Training" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Learning & Development
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage training records and approvals.
                    </p>
                </div>
                <Button @click="openAdd">Add training</Button>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                <div class="min-w-[180px] flex-1">
                    <Label for="search" class="sr-only">Search</Label>
                    <Input
                        id="search"
                        v-model="searchInput"
                        type="search"
                        placeholder="Search title, provider..."
                        class="h-9"
                    />
                </div>
                <div class="w-[140px]">
                    <Label for="filter-status" class="sr-only">Status</Label>
                    <Select v-model="filterStatus">
                        <SelectTrigger id="filter-status" class="h-9">
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
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Employee</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Title</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Provider</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Date</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Hours</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Status</th>
                                <th class="text-right font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="t in trainings.data"
                                :key="t.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="t.employee_name || t.employee_id || '—'"
                                        :subtitle="String(t.employee_id)"
                                    />
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ t.title || '—' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ t.provider || '—' }}</td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ formatDate(t.date_from) }}
                                    <span v-if="t.date_to"> – {{ formatDate(t.date_to) }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">{{ t.hours ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <Badge :variant="statusVariant(t.status)">
                                        {{ statusOptions[t.status] ?? t.status }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            class="h-8 w-8 p-0"
                                            @click="openEdit(t)"
                                        >
                                            <span class="sr-only">Edit</span>
                                            <Pencil class="size-4" />
                                        </Button>
                                        <Form
                                            :action="hr.training.destroy.url(t.id)"
                                            method="post"
                                            class="inline"
                                            @submit="(e: Event) => !confirmDelete('Delete this training record?') && e.preventDefault()"
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
                    v-if="!trainings.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No training records found.
                    <button type="button" class="ml-1 text-[#013CFC] hover:underline dark:text-[#60C8FC]" @click="clearFilters">
                        Clear filters
                    </button>
                </div>
            </div>

            <div v-if="trainings.last_page > 1" class="flex flex-wrap items-center justify-center gap-2">
                <template v-for="(link, i) in trainings.links" :key="i">
                    <span
                        v-if="!link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-200 px-3 text-sm text-gray-400 dark:border-neutral-700"
                        v-html="link.label"
                    />
                    <Link
                        v-else
                        :href="link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-3 text-sm transition-colors"
                        :class="link.active ? 'border-[#013CFC] bg-[#013CFC] text-white dark:border-[#60C8FC] dark:bg-[#60C8FC] dark:text-gray-900' : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>

        <!-- Add dialog -->
        <Dialog v-model:open="addDialogOpen">
            <DialogContent :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Add training</DialogTitle>
                </DialogHeader>
                <Form v-bind="hr.training.store.form()" class="flex flex-col gap-4" @submit="addDialogOpen = false">
                    <input type="hidden" name="employee_id" :value="addEmployeeId" />
                    <input type="hidden" name="status" :value="addStatus" />
                    <div class="max-h-[60vh] overflow-y-auto p-1 space-y-4">
                    <div class="grid gap-2">
                        <Label for="add-employee">Employee</Label>
                        <Select v-model="addEmployeeId" required>
                            <SelectTrigger id="add-employee">
                                <SelectValue placeholder="Select employee" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="emp in employees" :key="emp.id" :value="emp.id">{{ emp.name }}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Label for="add-title">Title</Label>
                        <Input id="add-title" v-model="addTitle" name="title" type="text" required />
                    </div>
                    <div class="grid gap-2">
                        <Label for="add-provider">Provider (optional)</Label>
                        <Input id="add-provider" v-model="addProvider" name="provider" type="text" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="add-date_from">Start date</Label>
                        <Input id="add-date_from" v-model="addDateFrom" name="date_from" type="date" required />
                    </div>
                    <div class="grid gap-2">
                        <Label for="add-date_to">End date (optional)</Label>
                        <Input id="add-date_to" v-model="addDateTo" name="date_to" type="date" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="add-hours">Hours (optional)</Label>
                        <Input id="add-hours" v-model="addHours" name="hours" type="number" step="0.5" min="0" />
                    </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="addDialogOpen = false">Cancel</Button>
                        <Button type="submit">Create</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>

        <!-- Edit dialog -->
        <Dialog v-model:open="editDialogOpen">
            <DialogContent :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Edit training</DialogTitle>
                </DialogHeader>
                <Form
                    v-if="editTraining"
                    :action="hr.training.update.url(editTraining.id)"
                    method="post"
                    class="flex flex-col gap-4"
                    @submit="closeEdit()"
                >
                    <input type="hidden" name="_method" value="PUT" />
                    <input type="hidden" name="employee_id" :value="editEmployeeId" />
                    <input type="hidden" name="status" :value="editStatus" />
                    <div class="max-h-[60vh] overflow-y-auto p-1 space-y-4">
                    <div class="grid gap-2">
                        <Label for="edit-title">Title</Label>
                        <Input id="edit-title" v-model="editTitle" name="title" type="text" required />
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-provider">Provider (optional)</Label>
                        <Input id="edit-provider" v-model="editProvider" name="provider" type="text" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-status">Status</Label>
                        <Select v-model="editStatus">
                            <SelectTrigger id="edit-status">
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem v-for="[val, label] in statusOptionsEntries" :key="val" :value="val">{{ label }}</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-date_from">Start date</Label>
                        <Input id="edit-date_from" v-model="editDateFrom" name="date_from" type="date" required />
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-date_to">End date (optional)</Label>
                        <Input id="edit-date_to" v-model="editDateTo" name="date_to" type="date" />
                    </div>
                    <div class="grid gap-2">
                        <Label for="edit-hours">Hours (optional)</Label>
                        <Input id="edit-hours" v-model="editHours" name="hours" type="number" step="0.5" min="0" />
                    </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="closeEdit">Cancel</Button>
                        <Button type="submit">Save</Button>
                    </DialogFooter>
                </Form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
