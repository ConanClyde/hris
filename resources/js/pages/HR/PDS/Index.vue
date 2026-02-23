<script setup lang="ts">
import { Head, Link, Form, router } from '@inertiajs/vue3';
import { ref, computed, watch } from 'vue';
import { Eye, Check, X } from 'lucide-vue-next';
import {
    Dialog,
    DialogContent,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import TableUserCell from '@/components/TableUserCell.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type PdsPersonal = {
    first_name?: string;
    middle_name?: string;
    surname?: string;
    name_extension?: string;
    dob?: string;
    place_of_birth?: string;
    sex?: string;
    civil_status?: string;
    email?: string;
    phone?: string;
    mobile?: string;
    [key: string]: unknown;
};

type PdsItem = {
    id: number;
    employee_id: number;
    status: string;
    submitted_at: string | null;
    reviewed_at: string | null;
    created_at: string;
    employee?: { id: number; full_name?: string; first_name?: string; last_name?: string };
};

type PdsDetail = PdsItem & {
    personal?: PdsPersonal | null;
};

type PaginatedData = {
    data: PdsItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = withDefaults(
    defineProps<{
        pdsList: PaginatedData;
        statusOptions: Record<string, string>;
        filters?: { status?: string };
        pdsDetail?: PdsDetail | null;
    }>(),
    { filters: () => ({}), pdsDetail: null }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'PDS Management' },
];

const filterStatus = ref(props.filters?.status ?? 'all');

watch(
    () => props.filters?.status,
    (status) => {
        filterStatus.value = status ?? 'all';
    },
    { immediate: true }
);

watch(filterStatus, () => {
    const query: Record<string, string> = {};
    if (filterStatus.value && filterStatus.value !== 'all') query.status = filterStatus.value;
    router.get(hr.pds.index.url(), query, { preserveState: true });
});

function employeeName(item: PdsItem): string {
    const e = item.employee;
    if (!e) return `Employee #${item.employee_id}`;
    return ((e as { full_name?: string }).full_name ?? [e.first_name, e.last_name].filter(Boolean).join(' ')) || `#${item.employee_id}`;
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
    if (status === 'submitted') return 'secondary';
    return 'outline';
}

const statusOptionsEntries = computed(() => Object.entries(props.statusOptions));

function openPreview(item: PdsItem) {
    const query: Record<string, string> = {};
    if (props.filters?.status) query.status = props.filters.status;
    if (props.pdsList.current_page > 1) query.page = String(props.pdsList.current_page);
    query.preview_id = String(item.id);
    router.get(hr.pds.index.url(), query);
}

function closePreviewModal() {
    const query: Record<string, string> = {};
    if (props.filters?.status) query.status = props.filters.status;
    if (props.pdsList.current_page > 1) query.page = String(props.pdsList.current_page);
    router.get(hr.pds.index.url(), Object.keys(query).length ? query : undefined);
}

function pdsDetailEmployeeName(pds: PdsDetail): string {
    const e = pds.employee;
    if (!e) return `Employee #${pds.employee_id}`;
    return ((e as { full_name?: string }).full_name ?? [e.first_name, e.last_name].filter(Boolean).join(' ')) || `#${pds.employee_id}`;
}
</script>

<template>
    <Head title="PDS Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    PDS Management
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Review and manage employee Personal Data Sheets.
                </p>
            </div>

            <!-- Status filter -->
            <div class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                <div class="w-[160px]">
                    <label for="filter-status" class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400">Status</label>
                    <Select v-model="filterStatus">
                        <SelectTrigger id="filter-status" class="h-9">
                            <SelectValue placeholder="All statuses" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All statuses</SelectItem>
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
                    v-if="filterStatus && filterStatus !== 'all'"
                    type="button"
                    variant="outline"
                    size="sm"
                    class="h-9"
                    @click="filterStatus = 'all'; router.get(hr.pds.index.url())"
                >
                    Clear filter
                </Button>
            </div>

            <!-- Table -->
            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Employee
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
                                v-for="item in pdsList.data"
                                :key="item.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="employeeName(item)"
                                        :subtitle="`#${item.employee_id}`"
                                    />
                                </td>
                                <td class="px-4 py-3">
                                    <Badge :variant="statusVariant(item.status)">
                                        {{ statusOptions[item.status] ?? item.status }}
                                    </Badge>
                                </td>
                                <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                    {{ formatDate(item.submitted_at) }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <div class="flex items-center justify-end gap-1">
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon-sm"
                                            class="h-8 w-8 p-0"
                                            @click="openPreview(item)"
                                        >
                                            <span class="sr-only">Preview</span>
                                            <Eye class="size-4" />
                                        </Button>
                                        <Form
                                            v-if="item.status === 'submitted'"
                                            :action="hr.pds.status.url()"
                                            method="post"
                                            class="inline-flex gap-1"
                                        >
                                            <input type="hidden" name="pds_id" :value="item.id" />
                                            <input type="hidden" name="status" value="approved" />
                                            <Button
                                                type="submit"
                                                variant="ghost"
                                                size="icon-sm"
                                                class="h-8 w-8 p-0 text-emerald-600 hover:text-emerald-700"
                                            >
                                                <span class="sr-only">Approve</span>
                                                <Check class="size-4" />
                                            </Button>
                                        </Form>
                                        <Form
                                            v-if="item.status === 'submitted'"
                                            :action="hr.pds.status.url()"
                                            method="post"
                                            class="inline"
                                        >
                                            <input type="hidden" name="pds_id" :value="item.id" />
                                            <input type="hidden" name="status" value="rejected" />
                                            <Button
                                                type="submit"
                                                variant="ghost"
                                                size="icon-sm"
                                                class="h-8 w-8 p-0 text-red-600 hover:text-red-700"
                                            >
                                                <span class="sr-only">Reject</span>
                                                <X class="size-4" />
                                            </Button>
                                        </Form>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="!pdsList.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No PDS records found.
                </div>
            </div>

            <!-- Pagination -->
            <div
                v-if="pdsList.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in pdsList.links" :key="i">
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

        <!-- Preview PDS modal -->
        <Dialog
            :open="Boolean(pdsDetail)"
            @update:open="(v) => !v && closePreviewModal()"
        >
            <DialogContent :show-close-button="true" class="sm:max-w-lg max-h-[90vh] flex flex-col">
                <DialogHeader>
                    <DialogTitle>PDS Preview</DialogTitle>
                </DialogHeader>
                <template v-if="pdsDetail">
                    <div class="max-h-[60vh] overflow-y-auto p-1 space-y-4">
                        <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                                <h2 class="text-sm font-medium text-gray-900 dark:text-gray-100">Summary</h2>
                            </div>
                            <dl class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Employee</dt>
                                    <dd class="mt-0.5 text-sm font-medium text-gray-900 dark:text-gray-100">{{ pdsDetailEmployeeName(pdsDetail) }} (#{{ pdsDetail.employee_id }})</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd class="mt-0.5">
                                        <Badge :variant="statusVariant(pdsDetail.status)">
                                            {{ statusOptions[pdsDetail.status] ?? pdsDetail.status }}
                                        </Badge>
                                    </dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Submitted</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ formatDate(pdsDetail.submitted_at) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Reviewed</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ formatDate(pdsDetail.reviewed_at) }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div v-if="pdsDetail.personal" class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                                <h2 class="text-sm font-medium text-gray-900 dark:text-gray-100">Personal information</h2>
                            </div>
                            <dl class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-2">
                                <div v-if="pdsDetail.personal.surname">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">
                                        {{ [pdsDetail.personal.surname, pdsDetail.personal.first_name, pdsDetail.personal.middle_name, pdsDetail.personal.name_extension].filter(Boolean).join(', ') }}
                                    </dd>
                                </div>
                                <div v-if="pdsDetail.personal.dob">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Date of birth</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ formatDate(String(pdsDetail.personal.dob)) }}</dd>
                                </div>
                                <div v-if="pdsDetail.personal.place_of_birth">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Place of birth</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pdsDetail.personal.place_of_birth }}</dd>
                                </div>
                                <div v-if="pdsDetail.personal.sex">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Sex</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pdsDetail.personal.sex }}</dd>
                                </div>
                                <div v-if="pdsDetail.personal.civil_status">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Civil status</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pdsDetail.personal.civil_status }}</dd>
                                </div>
                                <div v-if="pdsDetail.personal.email">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pdsDetail.personal.email }}</dd>
                                </div>
                                <div v-if="pdsDetail.personal.phone">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pdsDetail.personal.phone }}</dd>
                                </div>
                                <div v-if="pdsDetail.personal.mobile">
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Mobile</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pdsDetail.personal.mobile }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div v-else class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-gray-400">
                            No personal details recorded for this PDS.
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="closePreviewModal">
                            Back to list
                        </Button>
                        <Form
                            v-if="pdsDetail.status === 'submitted'"
                            :action="hr.pds.status.url()"
                            method="post"
                            class="inline"
                        >
                            <input type="hidden" name="pds_id" :value="pdsDetail.id" />
                            <input type="hidden" name="status" value="approved" />
                            <Button type="submit">Approve</Button>
                        </Form>
                        <Form
                            v-if="pdsDetail.status === 'submitted'"
                            :action="hr.pds.status.url()"
                            method="post"
                            class="inline"
                        >
                            <input type="hidden" name="pds_id" :value="pdsDetail.id" />
                            <input type="hidden" name="status" value="rejected" />
                            <Button type="submit" variant="destructive">Reject</Button>
                        </Form>
                    </DialogFooter>
                </template>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
