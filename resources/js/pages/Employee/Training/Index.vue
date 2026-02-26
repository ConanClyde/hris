<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { Eye, Pencil, Download } from 'lucide-vue-next';
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

type TrainingItem = {
    id: number;
    title: string;
    date_from: string;
    date_to?: string | null;
    hours?: number | string | null;
    type?: string | null;
    provider?: string | null;
    status: string;
    created_at: string;
};

type PaginatedData = {
    data: TrainingItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = withDefaults(
    defineProps<{
        trainings: PaginatedData;
        statusOptions: Record<string, string>;
        filters?: { type?: string; status?: string };
    }>(),
    { filters: () => ({}) }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Learning & Development' },
];

const page = usePage();
const { trainingsAssignedCount } = useBroadcasting();

if (trainingsAssignedCount.value === null) {
    const base = (page.props.auth?.counts || {}) as Record<string, any>;
    trainingsAssignedCount.value = typeof base.trainings_assigned === 'number' ? base.trainings_assigned : 0;
}

const assignedCountComputed = computed(() => trainingsAssignedCount.value ?? 0);

const filterStatus = ref(props.filters?.status ?? 'all');
const filterType = ref(props.filters?.type ?? '');

const viewModalOpen = ref(false);
const addModalOpen = ref(false);
const editModalOpen = ref(false);
const viewingTraining = ref<TrainingItem | null>(null);
const editingTraining = ref<TrainingItem | null>(null);

const addTitle = ref('');
const addDateFrom = ref('');
const addDateTo = ref('');
const addHours = ref('');
const addProvider = ref('');

const editTitle = ref('');
const editDateFrom = ref('');
const editDateTo = ref('');
const editHours = ref('');
const editProvider = ref('');

const statusOptionsEntries = computed(() => Object.entries(props.statusOptions));

watch(
    () => [props.filters?.status, props.filters?.type],
    ([status, type]) => {
        filterStatus.value = (status as string) || 'all';
        filterType.value = (type as string) ?? '';
    },
    { immediate: true }
);

watch([filterStatus, filterType], () => {
    const query: Record<string, string> = {};
    if (filterStatus.value && filterStatus.value !== 'all') query.status = filterStatus.value;
    if (filterType.value) query.type = filterType.value;
    router.get(employee.training.index.url(), query, { preserveState: true });
});

watch(editingTraining, (t) => {
    if (t) {
        editTitle.value = t.title ?? '';
        editDateFrom.value = t.date_from ? String(t.date_from).slice(0, 10) : '';
        editDateTo.value = t.date_to ? String(t.date_to).slice(0, 10) : '';
        editHours.value = t.hours != null ? String(t.hours) : '';
        editProvider.value = t.provider ?? '';
    }
}, { immediate: true });

function clearFilters() {
    filterStatus.value = 'all';
    filterType.value = '';
    router.get(employee.training.index.url());
}

function openView(t: TrainingItem) {
    viewingTraining.value = t;
    viewModalOpen.value = true;
}

function openAdd() {
    addTitle.value = '';
    addDateFrom.value = '';
    addDateTo.value = '';
    addHours.value = '';
    addProvider.value = '';
    addModalOpen.value = true;
}

function openEdit(t: TrainingItem) {
    editingTraining.value = t;
    editModalOpen.value = true;
}

function closeView() {
    viewModalOpen.value = false;
    viewingTraining.value = null;
}

function closeEdit() {
    editModalOpen.value = false;
    editingTraining.value = null;
}

function submitAdd(e: Event) {
    e.preventDefault();
    router.post(employee.training.store.url(), {
        title: addTitle.value,
        date_from: addDateFrom.value,
        date_to: addDateTo.value || null,
        hours: addHours.value ? Number(addHours.value) : null,
        provider: addProvider.value || null,
    }, { onSuccess: () => { addModalOpen.value = false; } });
}

function submitEdit(e: Event) {
    e.preventDefault();
    if (!editingTraining.value) return;
    router.put(employee.training.update.url(editingTraining.value.id), {
        title: editTitle.value,
        date_from: editDateFrom.value,
        date_to: editDateTo.value || null,
        hours: editHours.value ? Number(editHours.value) : null,
        provider: editProvider.value || null,
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

function inclusiveDates(t: TrainingItem) {
    const from = t.date_from ? formatDate(String(t.date_from).slice(0, 10)) : '—';
    const to = t.date_to ? formatDate(String(t.date_to).slice(0, 10)) : null;
    return to && to !== from ? `${from} – ${to}` : from;
}
</script>

<template>
    <Head title="Learning & Development" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Learning & Development
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Your training and development records.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" as-child>
                        <a :href="employee.training.export.url()" target="_blank" rel="noopener noreferrer">
                            <Download class="size-4 mr-1.5" />
                            Export CSV
                        </a>
                    </Button>
                    <Button @click="openAdd">Add Training</Button>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Assigned / Pending
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ assignedCountComputed }}
                        </p>
                        <p class="mt-2 text-sm font-medium text-amber-600 dark:text-amber-400">
                            In progress
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50">
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
                <Button type="button" variant="outline" @click="clearFilters">
                    Clear filters
                </Button>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[560px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Title</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Inclusive Dates</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Hours</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Type</th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">Provider</th>
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
                                <td class="px-4 py-3 font-medium">{{ t.title || '—' }}</td>
                                <td class="px-4 py-3 text-muted-foreground">{{ inclusiveDates(t) }}</td>
                                <td class="px-4 py-3">{{ t.hours ?? '—' }}</td>
                                <td class="px-4 py-3">{{ t.type ?? '—' }}</td>
                                <td class="px-4 py-3">{{ t.provider ?? '—' }}</td>
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
                                            title="View"
                                            @click="openView(t)"
                                        >
                                            <Eye class="size-4" />
                                        </Button>
                                        <Button
                                            v-if="t.status === 'pending'"
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            title="Edit"
                                            class="hover:text-primary"
                                            @click="openEdit(t)"
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
                    v-if="!trainings.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No training records yet.
                    <Button variant="link" class="ml-1 h-auto p-0" @click="openAdd">Add training</Button>
                </div>
            </div>

            <div
                v-if="trainings.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
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
                        :class="link.active ? 'border-primary bg-primary text-primary-foreground' : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>

        <!-- View modal -->
        <Dialog v-model:open="viewModalOpen">
            <DialogContent v-if="viewingTraining" :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>View Training</DialogTitle>
                    <DialogDescription class="sr-only">
                        View training record details.
                    </DialogDescription>
                </DialogHeader>
                <div class="max-h-[60vh] overflow-y-auto space-y-3 p-1">
                    <dl class="grid grid-cols-1 gap-2 text-sm">
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Title</dt>
                            <dd class="mt-0.5">{{ viewingTraining.title || '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Inclusive dates</dt>
                            <dd class="mt-0.5">{{ inclusiveDates(viewingTraining) }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Hours</dt>
                            <dd class="mt-0.5">{{ viewingTraining.hours ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Type</dt>
                            <dd class="mt-0.5">{{ viewingTraining.type ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Provider</dt>
                            <dd class="mt-0.5">{{ viewingTraining.provider ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium uppercase tracking-wider text-muted-foreground">Status</dt>
                            <dd class="mt-0.5">
                                <Badge :variant="statusVariant(viewingTraining.status)">
                                    {{ statusOptions[viewingTraining.status] ?? viewingTraining.status }}
                                </Badge>
                            </dd>
                        </div>
                    </dl>
                </div>
                <DialogFooter>
                    <Button type="button" variant="outline" @click="closeView()">Close</Button>
                    <Button v-if="viewingTraining.status === 'pending'" type="button" @click="openEdit(viewingTraining); closeView();">
                        Edit
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Add modal -->
        <Dialog v-model:open="addModalOpen">
            <DialogContent :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Add Training</DialogTitle>
                    <DialogDescription class="sr-only">
                        Add a new training record.
                    </DialogDescription>
                </DialogHeader>
                <form class="flex flex-col gap-4" @submit.prevent="submitAdd">
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <div class="grid gap-2">
                            <Label for="add-title">Title</Label>
                            <Input id="add-title" v-model="addTitle" required />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="grid gap-2">
                                <Label for="add-date_from">Start date</Label>
                                <Input id="add-date_from" v-model="addDateFrom" type="date" required />
                            </div>
                            <div class="grid gap-2">
                                <Label for="add-date_to">End date</Label>
                                <Input id="add-date_to" v-model="addDateTo" type="date" />
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-hours">Hours</Label>
                            <Input id="add-hours" v-model="addHours" type="number" step="0.5" min="0" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="add-provider">Provider (optional)</Label>
                            <Input id="add-provider" v-model="addProvider" />
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="addModalOpen = false">Cancel</Button>
                        <Button type="submit">Add</Button>
                    </DialogFooter>
                </form>
            </DialogContent>
        </Dialog>

        <!-- Edit modal -->
        <Dialog v-model:open="editModalOpen">
            <DialogContent v-if="editingTraining" :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Edit Training</DialogTitle>
                    <DialogDescription class="sr-only">
                        Edit training record details.
                    </DialogDescription>
                </DialogHeader>
                <form class="flex flex-col gap-4" @submit.prevent="submitEdit">
                    <div class="max-h-[60vh] overflow-y-auto space-y-4 p-1">
                        <div class="grid gap-2">
                            <Label for="edit-title">Title</Label>
                            <Input id="edit-title" v-model="editTitle" required />
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div class="grid gap-2">
                                <Label for="edit-date_from">Start date</Label>
                                <Input id="edit-date_from" v-model="editDateFrom" type="date" required />
                            </div>
                            <div class="grid gap-2">
                                <Label for="edit-date_to">End date</Label>
                                <Input id="edit-date_to" v-model="editDateTo" type="date" />
                            </div>
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-hours">Hours</Label>
                            <Input id="edit-hours" v-model="editHours" type="number" step="0.5" min="0" />
                        </div>
                        <div class="grid gap-2">
                            <Label for="edit-provider">Provider (optional)</Label>
                            <Input id="edit-provider" v-model="editProvider" />
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
