<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { Eye, Pencil } from 'lucide-vue-next';
import { ref, watch, computed } from 'vue';
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
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { useBroadcasting } from '@/composables/useBroadcasting';
import AppLayout from '@/layouts/AppLayout.vue';
import employee from '@/routes/employee';
import type { BreadcrumbItem } from '@/types';

type Application = {
    id: number;
    type: string;
    date_from: string;
    date_to?: string | null;
    total_days: number;
    reason: string | null;
    status: string;
    created_at: string;
    attachments?: unknown;
};

type PaginatedData = {
    data: Application[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = withDefaults(
    defineProps<{
        applications: PaginatedData;
        types: string[];
        statusOptions: Record<string, string>;
        filters?: { type?: string; status?: string };
    }>(),
    { filters: () => ({}) }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Leave Applications' },
];

const page = usePage();
const { leavesPendingCount } = useBroadcasting();

if (leavesPendingCount.value === null) {
    const base = (page.props.auth?.counts || {}) as Record<string, any>;
    leavesPendingCount.value = typeof base.leaves_pending === 'number' ? base.leaves_pending : 0;
}

const pendingCountComputed = computed(() => leavesPendingCount.value ?? 0);

const filterType = ref(props.filters?.type ?? 'all');
const filterStatus = ref(props.filters?.status ?? 'all');

const viewModalOpen = ref(false);
const addModalOpen = ref(false);
const editModalOpen = ref(false);
const viewingApp = ref<Application | null>(null);
const editingApp = ref<Application | null>(null);

const addType = ref('');
const addDateFrom = ref('');
const addTotalDays = ref('');
const addReason = ref('');

const editType = ref('');
const editDateFrom = ref('');
const editTotalDays = ref('');
const editReason = ref('');

const statusOptionsEntries = computed(() => Object.entries(props.statusOptions));

watch(
    () => [props.filters?.type, props.filters?.status],
    ([type, status]) => {
        filterType.value = (type as string) || 'all';
        filterStatus.value = (status as string) || 'all';
    },
    { immediate: true }
);

watch([filterType, filterStatus], () => {
    const query: Record<string, string> = {};
    if (filterType.value && filterType.value !== 'all') query.type = filterType.value;
    if (filterStatus.value && filterStatus.value !== 'all') query.status = filterStatus.value;
    router.get(employee.leaveApplications.index.url(), query, { preserveState: true });
});

watch(editingApp, (app) => {
    if (app) {
        editType.value = app.type ?? '';
        editDateFrom.value = app.date_from ? String(app.date_from).slice(0, 10) : '';
        editTotalDays.value = String(app.total_days ?? '');
        editReason.value = app.reason ?? '';
    }
}, { immediate: true });

function clearFilters() {
    filterType.value = 'all';
    filterStatus.value = 'all';
    router.get(employee.leaveApplications.index.url());
}

function openView(app: Application) {
    viewingApp.value = app;
    viewModalOpen.value = true;
}

function openAdd() {
    addType.value = '';
    addDateFrom.value = '';
    addTotalDays.value = '';
    addReason.value = '';
    addModalOpen.value = true;
}

function openEdit(app: Application) {
    editingApp.value = app;
    editModalOpen.value = true;
}

function closeView() {
    viewModalOpen.value = false;
    viewingApp.value = null;
}

function closeEdit() {
    editModalOpen.value = false;
    editingApp.value = null;
}

function submitAdd(e: Event) {
    e.preventDefault();
    router.post(employee.leaveApplications.store.url(), {
        type: addType.value,
        date_from: addDateFrom.value,
        total_days: Number(addTotalDays.value) || 0.5,
        reason: addReason.value || null,
    }, { onSuccess: () => { addModalOpen.value = false; } });
}

function submitEdit(e: Event) {
    e.preventDefault();
    if (!editingApp.value) return;
    router.put(employee.leaveApplications.update.url(editingApp.value.id), {
        type: editType.value,
        date_from: editDateFrom.value,
        total_days: Number(editTotalDays.value) || 0.5,
        reason: editReason.value || null,
    }, { onSuccess: () => closeEdit() });
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

function inclusiveDates(app: Application) {
    const from = app.date_from ? formatDate(String(app.date_from).slice(0, 10)) : '—';
    const to = app.date_to ? formatDate(String(app.date_to).slice(0, 10)) : null;
    return to && to !== from ? `${from} – ${to}` : from;
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
                        View and manage your leave applications.
                    </p>
                </div>
                <Button @click="openAdd">Apply for Leave</Button>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Pending Applications
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ pendingCountComputed }}
                        </p>
                        <p class="mt-2 text-sm font-medium text-amber-600 dark:text-amber-400">
                            In review
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                <div class="w-[140px]">
                    <Label for="filter-type" class="sr-only">Type</Label>
                    <Select v-model="filterType">
                        <SelectTrigger id="filter-type" class="h-10">
                            <SelectValue placeholder="Type" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All types</SelectItem>
                            <SelectItem v-for="t in types" :key="t" :value="t">{{ t }}</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="w-[140px]">
                    <Label for="filter-status" class="sr-only">Status</Label>
                    <Select v-model="filterStatus">
                        <SelectTrigger id="filter-status" class="h-10">
                            <SelectValue placeholder="Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All</SelectItem>
                            <SelectItem v-for="[val, label] in statusOptionsEntries" :key="val" :value="val">
                                {{ label }}
                            </SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <Button type="button" variant="outline" @click="clearFilters">
                    Clear filters
                </Button>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Type</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Inclusive Dates</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Days</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Status</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Submitted</th>
                                <th class="text-right font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="app in applications.data"
                                :key="app.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3 font-medium">{{ app.type || '—' }}</td>
                                <td class="px-4 py-3 text-muted-foreground">{{ inclusiveDates(app) }}</td>
                                <td class="px-4 py-3">{{ app.total_days ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <Badge :variant="statusVariant(app.status)">
                                        {{ statusOptions[app.status] ?? app.status }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-muted-foreground">{{ formatDate(app.created_at) }}</td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            title="View"
                                            @click="openView(app)"
                                        >
                                            <Eye class="size-4" />
                                        </Button>
                                        <Button
                                            v-if="app.status === 'pending'"
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            title="Edit"
                                            class="hover:text-primary"
                                            @click="openEdit(app)"
                                        >
                                            <Pencil class="size-4" />
                                        </Button>
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
                    No leave applications yet.
                    <Button variant="link" class="ml-1 h-auto p-0" @click="openAdd">Apply for leave</Button>
                </div>
            </div>

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
                        :class="link.active ? 'border-primary bg-primary text-primary-foreground' : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>

        <!-- View modal -->
        <Dialog v-model:open="viewModalOpen">
            <DialogContent v-if="viewingApp" :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>View Leave Application</DialogTitle>
                    <DialogDescription class="sr-only">
                        View details of your leave application.
                    </DialogDescription>
                </DialogHeader>
                <div class="max-h-[60vh] overflow-y-auto space-y-3 p-1">
                    <dl class="grid grid-cols-1 gap-2 text-sm">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Type</dt>
                            <dd class="mt-0.5">{{ viewingApp.type || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Inclusive dates</dt>
                            <dd class="mt-0.5">{{ inclusiveDates(viewingApp) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Total days</dt>
                            <dd class="mt-0.5">{{ viewingApp.total_days ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Status</dt>
                            <dd class="mt-0.5">
                                <Badge :variant="statusVariant(viewingApp.status)">
                                    {{ statusOptions[viewingApp.status] ?? viewingApp.status }}
                                </Badge>
                            </dd>
                        </div>
                        <div v-if="viewingApp.reason">
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Reason</dt>
                            <dd class="mt-0.5 whitespace-pre-wrap">{{ viewingApp.reason }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Submitted</dt>
                            <dd class="mt-0.5">{{ formatDate(viewingApp.created_at) }}</dd>
                        </div>
                    </dl>
                </div>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeView()">Close</Button>
                    <Button v-if="viewingApp.status === 'pending'" type="button" @click="openEdit(viewingApp); closeView();">
                        Edit
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Add modal -->
        <Dialog v-model:open="addModalOpen">
            <DialogContent :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Apply for Leave</DialogTitle>
                    <DialogDescription class="sr-only">
                        Submit a new leave application.
                    </DialogDescription>
                </DialogHeader>
                <form class="flex flex-col gap-4" @submit.prevent="submitAdd">
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <div class="grid gap-2">
                            <Label for="add-type">Leave type</Label>
                            <Select v-model="addType" required>
                                <SelectTrigger id="add-type">
                                    <SelectValue placeholder="Select type" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="t in types" :key="t" :value="t">{{ t }}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-date_from">Start date</Label>
                            <Input id="add-date_from" v-model="addDateFrom" type="date" required />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-total_days">Total days</Label>
                            <Input id="add-total_days" v-model="addTotalDays" type="number" step="0.5" min="0.5" required />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-reason">Reason (optional)</Label>
                            <textarea
                                id="add-reason"
                                v-model="addReason"
                                rows="3"
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="addModalOpen = false">Cancel</Button>
                        <Button type="submit">Submit</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Edit modal -->
        <Dialog v-model:open="editModalOpen">
            <DialogContent v-if="editingApp" :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Edit Leave Application</DialogTitle>
                    <DialogDescription class="sr-only">
                        Edit your leave application details.
                    </DialogDescription>
                </DialogHeader>
                <form class="flex flex-col gap-4" @submit.prevent="submitEdit">
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <div class="grid gap-2">
                            <Label for="edit-type">Leave type</Label>
                            <Select v-model="editType" required>
                                <SelectTrigger id="edit-type">
                                    <SelectValue />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem v-for="t in types" :key="t" :value="t">{{ t }}</SelectItem>
                                </SelectContent>
                            </Select>
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-date_from">Start date</Label>
                            <Input id="edit-date_from" v-model="editDateFrom" type="date" required />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-total_days">Total days</Label>
                            <Input id="edit-total_days" v-model="editTotalDays" type="number" step="0.5" min="0.5" required />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-reason">Reason (optional)</Label>
                            <textarea
                                id="edit-reason"
                                v-model="editReason"
                                rows="3"
                                class="flex min-h-[80px] w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring"
                            />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="closeEdit()">Cancel</Button>
                        <Button type="submit">Save changes</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
