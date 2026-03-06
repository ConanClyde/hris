<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { Eye, FileText } from 'lucide-vue-next';
import { ref, computed, watch, onMounted } from 'vue';
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
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import { useBroadcasting } from '@/composables/useBroadcasting';
import AppLayout from '@/layouts/AppLayout.vue';
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
    user_id?: number;
    avatar?: string | null;
    employee?: {
        id: number;
        full_name?: string;
        first_name?: string;
        last_name?: string;
    };
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
        revisionList?: PaginatedData;
        statusOptions: Record<string, string>;
        filters?: { status?: string };
        pdsDetail?: PdsDetail | null;
    }>(),
    { filters: () => ({}), pdsDetail: null },
);

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

const breadcrumbs: BreadcrumbItem[] = [{ title: 'PDS Management' }];

const page = usePage();
const { pdsPendingCount } = useBroadcasting();

if (pdsPendingCount.value === null) {
    const base = (page.props.auth?.counts || {}) as Record<string, any>;
    pdsPendingCount.value =
        typeof base.pds_pending === 'number' ? base.pds_pending : 0;
}

const pendingCountComputed = computed(() => pdsPendingCount.value ?? 0);

const pdsList = ref(props.pdsList);
watch(
    () => props.pdsList,
    (next) => {
        pdsList.value = next;
    },
);

const statusOptions = computed<Record<string, string>>(() => ({
    ...props.statusOptions,
    under_review: props.statusOptions.under_review ?? 'Under Review',
}));

const filterStatus = ref(props.filters?.status ?? 'all');

const previewModalOpen = ref(false);
const previewLoading = ref(false);
const previewError = ref<string | null>(null);
const previewDetail = ref<PdsDetail | null>(null);

const rejectDialogOpen = ref(false);
const rejectTarget = ref<PdsItem | null>(null);
const rejectRemarks = ref('');
const rejectRemarksError = ref('');
const actionLoading = ref(false);

function openReject(item: PdsItem) {
    rejectTarget.value = item;
    rejectRemarks.value = '';
    rejectRemarksError.value = '';
    rejectDialogOpen.value = true;
}

function validateRejectRemarks(): boolean {
    const trimmed = rejectRemarks.value.trim();
    if (!trimmed) {
        rejectRemarksError.value = 'Rejection remarks are required.';
        return false;
    }
    if (trimmed.length < 10) {
        rejectRemarksError.value = 'Remarks must be at least 10 characters.';
        return false;
    }
    rejectRemarksError.value = '';
    return true;
}

function submitApprove(item: PdsItem) {
    actionLoading.value = true;
    router.post(
        hr.pds.status.url(),
        {
            pds_id: item.id,
            status: 'approved',
        },
        {
            onFinish: () => {
                actionLoading.value = false;
                previewModalOpen.value = false;
            },
        },
    );
}

function submitReject() {
    if (!validateRejectRemarks() || !rejectTarget.value) return;
    actionLoading.value = true;
    router.post(
        hr.pds.status.url(),
        {
            pds_id: rejectTarget.value.id,
            status: 'rejected',
            remarks: rejectRemarks.value.trim(),
        },
        {
            onFinish: () => {
                actionLoading.value = false;
                rejectDialogOpen.value = false;
                previewModalOpen.value = false;
            },
        },
    );
}

type PdsStatusUpdatedPayload = {
    id: number;
    employee_id: number;
    employee_name?: string | null;
    status: string;
};

function upsertOrRemoveFromTable(payload: PdsStatusUpdatedPayload) {
    const table = pdsList.value.data;
    const idx = table.findIndex((p: PdsItem) => p.id === payload.id);
    const statusFilter = (filterStatus.value || '').trim();
    const shouldInclude =
        !statusFilter ||
        statusFilter === 'all' ||
        payload.status === statusFilter;

    if (!shouldInclude) {
        if (idx !== -1) table.splice(idx, 1);
        return;
    }

    const row: PdsItem = {
        id: payload.id,
        employee_id: payload.employee_id,
        status: payload.status,
        submitted_at: null,
        reviewed_at: null,
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
        'pds.management',
        '.PdsStatusUpdated',
        (e: PdsStatusUpdatedPayload) => {
            upsertOrRemoveFromTable(e);
        },
    );
});

watch(
    () => props.filters?.status,
    (status) => {
        filterStatus.value = status ?? 'all';
    },
    { immediate: true },
);

let filterDebounce: ReturnType<typeof setTimeout> | null = null;
watch(filterStatus, () => {
    if (filterDebounce) clearTimeout(filterDebounce);
    filterDebounce = setTimeout(() => {
        const query: Record<string, string> = {};
        if (filterStatus.value && filterStatus.value !== 'all')
            query.status = filterStatus.value;
        router.get(hr.pds.index.url(), query, { preserveState: true });
    }, 300);
});

function employeeName(item: PdsItem): string {
    const e = item.employee;
    const fallbackName = (item as any)?.employee_name;
    if (typeof fallbackName === 'string' && fallbackName.trim() !== '') {
        return fallbackName;
    }
    if (!e) return `Employee #${item.employee_id}`;
    return (
        ((e as { full_name?: string }).full_name ??
            [e.first_name, e.last_name].filter(Boolean).join(' ')) ||
        `#${item.employee_id}`
    );
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

const statusOptionsEntries = computed(() =>
    Object.entries(statusOptions.value),
);

async function openPreview(item: PdsItem) {
    previewDetail.value = item as unknown as PdsDetail;
    previewModalOpen.value = true;
    previewLoading.value = false;
    previewError.value = null;

    if ((item as unknown as PdsDetail)?.personal) {
        return;
    }

    previewLoading.value = true;

    try {
        const url = hr.pds.previewJson.url({
            query: { pds_id: String(item.id) },
        } as any);
        const res = await fetch(url, {
            headers: {
                Accept: 'application/json',
            },
        });

        if (!res.ok) {
            const body = (await res.json().catch(() => null)) as any;
            previewError.value =
                body?.message ??
                body?.errors?.pds_id?.[0] ??
                'Failed to load PDS preview.';
            return;
        }

        const data = (await res.json()) as { pdsDetail?: PdsDetail | null };
        previewDetail.value = data?.pdsDetail ?? null;
        if (!previewDetail.value) previewError.value = 'PDS preview not found.';
    } catch {
        previewError.value = 'Failed to load PDS preview.';
    } finally {
        previewLoading.value = false;
    }
}

function closePreviewModal() {
    previewModalOpen.value = false;
    previewLoading.value = false;
    previewError.value = null;
    previewDetail.value = null;
}

function pdsDetailEmployeeName(pds: PdsDetail): string {
    const e = pds.employee;
    if (!e) return `Employee #${pds.employee_id}`;
    return (
        ((e as { full_name?: string }).full_name ??
            [e.first_name, e.last_name].filter(Boolean).join(' ')) ||
        `#${pds.employee_id}`
    );
}

// Revision Request Logic
const revisionModalOpen = ref(false);
const selectedRevision = ref<any>(null);
const revisionRemarks = ref('');

function openRevisionPreview(rev: any) {
    selectedRevision.value = rev;
    revisionRemarks.value = '';
    revisionModalOpen.value = true;
}
</script>

<template>
    <Head title="PDS Management" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div>
                <h1
                    class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                >
                    PDS Management
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Review and manage employee Personal Data Sheets.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                        >
                            Pending Review
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p
                            class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                        >
                            {{ pendingCountComputed }}
                        </p>
                        <p
                            class="mt-2 text-sm font-medium text-amber-600 dark:text-amber-400"
                        >
                            Action required
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Status filter -->
            <div
                class="flex flex-wrap items-end gap-3 rounded-lg border border-gray-200 bg-gray-50/50 p-3 dark:border-neutral-700 dark:bg-neutral-800/50"
            >
                <div class="w-[160px]">
                    <label
                        for="filter-status"
                        class="mb-1 block text-xs font-medium text-gray-600 dark:text-gray-400"
                        >Status</label
                    >
                    <Select v-model="filterStatus">
                        <SelectTrigger id="filter-status" class="h-10">
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
                    @click="
                        filterStatus = 'all';
                        router.get(hr.pds.index.url());
                    "
                >
                    Clear filter
                </Button>
            </div>

            <!-- Pending Revisions Table -->
            <div v-if="revisionList?.data?.length" class="space-y-4">
                <h2
                    class="text-lg font-medium text-gray-900 dark:text-gray-100"
                >
                    Pending Revision Requests
                </h2>
                <div
                    class="overflow-hidden rounded-lg border border-amber-200 dark:border-amber-900/50"
                >
                    <div class="overflow-x-auto">
                        <table
                            class="w-full min-w-[520px] border-collapse text-sm"
                        >
                            <thead
                                class="border-b border-amber-200 bg-amber-50 dark:border-amber-900/50 dark:bg-amber-900/20"
                            >
                                <tr>
                                    <th
                                        class="px-4 py-3 text-left font-medium text-amber-800 dark:text-amber-200"
                                    >
                                        Employee
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left font-medium text-amber-800 dark:text-amber-200"
                                    >
                                        Requested On
                                    </th>
                                    <th
                                        class="px-4 py-3 text-right font-medium text-amber-800 dark:text-amber-200"
                                    >
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-amber-200 dark:divide-amber-900/50"
                            >
                                <tr
                                    v-for="rev in revisionList.data"
                                    :key="rev.id"
                                    class="hover:bg-amber-50/50 dark:hover:bg-amber-900/20"
                                >
                                    <td class="px-4 py-3">
                                        <TableUserCell
                                            :name="employeeName(rev as any)"
                                            :avatar="rev.avatar"
                                            :subtitle="
                                                rev.user_id
                                                    ? `User ID: ${rev.user_id}`
                                                    : null
                                            "
                                            :user-id="rev.user_id"
                                        />
                                    </td>
                                    <td
                                        class="px-4 py-3 text-amber-900 dark:text-amber-100"
                                    >
                                        {{ formatDate(rev.created_at) }}
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <div
                                            class="flex items-center justify-end gap-1"
                                        >
                                            <Button
                                                type="button"
                                                variant="outline"
                                                size="sm"
                                                @click="
                                                    openRevisionPreview(rev)
                                                "
                                                >Review Changes</Button
                                            >
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div
                class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
            >
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[520px] border-collapse text-sm">
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
                                    Status
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Submitted
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
                                v-for="item in pdsList.data"
                                :key="item.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="employeeName(item)"
                                        :avatar="item.avatar"
                                        :subtitle="
                                            item.user_id
                                                ? `User ID: ${item.user_id}`
                                                : null
                                        "
                                        :user-id="item.user_id"
                                    />
                                </td>
                                <td class="px-4 py-3">
                                    <Badge
                                        :variant="statusVariant(item.status)"
                                    >
                                        {{
                                            statusOptions[item.status] ??
                                            item.status
                                        }}
                                    </Badge>
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ formatDate(item.submitted_at) }}
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
                                            @click="openPreview(item)"
                                        >
                                            <Eye class="size-4" />
                                        </Button>
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
            <Pagination :meta="pdsList" />
        </div>

        <!-- Preview PDS modal -->
        <Dialog v-model:open="previewModalOpen">
            <DialogContent
                :show-close-button="true"
                class="w-[95vw] max-w-3xl sm:w-[90vw]"
                @interact-outside="(e) => previewLoading && e.preventDefault()"
            >
                <DialogHeader>
                    <DialogTitle>PDS Preview</DialogTitle>
                    <DialogDescription class="sr-only">
                        Preview of the selected employee Personal Data Sheet.
                    </DialogDescription>
                </DialogHeader>

                <div class="max-h-[75vh] space-y-4 overflow-y-auto p-2 sm:p-4">
                    <div
                        v-if="previewLoading"
                        class="py-10 text-center text-sm text-muted-foreground"
                    >
                        Loading...
                    </div>

                    <div
                        v-else-if="previewError"
                        class="rounded-lg border border-destructive/30 bg-destructive/5 p-3 text-sm text-destructive"
                    >
                        {{ previewError }}
                    </div>

                    <template v-else-if="previewDetail">
                        <div
                            class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
                        >
                            <div
                                class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/50"
                            >
                                <h2
                                    class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                >
                                    Summary
                                </h2>
                            </div>
                            <dl
                                class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-2"
                            >
                                <div>
                                    <dt
                                        class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                                    >
                                        Employee
                                    </dt>
                                    <dd
                                        class="mt-0.5 text-sm font-medium text-gray-900 dark:text-gray-100"
                                    >
                                        {{
                                            previewDetail.personal
                                                ? [
                                                      previewDetail.personal
                                                          .first_name,
                                                      previewDetail.personal
                                                          .middle_name,
                                                      previewDetail.personal
                                                          .surname,
                                                      previewDetail.personal
                                                          .name_extension,
                                                  ]
                                                      .filter(Boolean)
                                                      .join(' ')
                                                : pdsDetailEmployeeName(
                                                      previewDetail,
                                                  )
                                        }}
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
                                                statusVariant(
                                                    previewDetail.status,
                                                )
                                            "
                                        >
                                            {{
                                                statusOptions[
                                                    previewDetail.status
                                                ] ?? previewDetail.status
                                            }}
                                        </Badge>
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                                    >
                                        Submitted
                                    </dt>
                                    <dd class="mt-0.5 text-sm">
                                        {{
                                            formatDate(
                                                previewDetail.submitted_at,
                                            )
                                        }}
                                    </dd>
                                </div>
                                <div>
                                    <dt
                                        class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                                    >
                                        Reviewed
                                    </dt>
                                    <dd class="mt-0.5 text-sm">
                                        {{
                                            formatDate(
                                                previewDetail.reviewed_at,
                                            )
                                        }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div
                            v-if="previewDetail.personal"
                            class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
                        >
                            <div
                                class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/50"
                            >
                                <h2
                                    class="text-sm font-medium text-gray-900 dark:text-gray-100"
                                >
                                    Personal information
                                </h2>
                            </div>
                            <dl
                                class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-2"
                            >
                                <div v-if="previewDetail.personal.surname">
                                    <dt
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Name
                                    </dt>
                                    <dd
                                        class="mt-0.5 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        {{
                                            [
                                                previewDetail.personal
                                                    .first_name,
                                                previewDetail.personal
                                                    .middle_name,
                                                previewDetail.personal.surname,
                                                previewDetail.personal
                                                    .name_extension,
                                            ]
                                                .filter(Boolean)
                                                .join(' ')
                                        }}
                                    </dd>
                                </div>
                                <div v-if="previewDetail.personal.dob">
                                    <dt
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Date of birth
                                    </dt>
                                    <dd
                                        class="mt-0.5 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        {{
                                            formatDate(
                                                String(
                                                    previewDetail.personal.dob,
                                                ),
                                            )
                                        }}
                                    </dd>
                                </div>
                                <div
                                    v-if="previewDetail.personal.place_of_birth"
                                >
                                    <dt
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Place of birth
                                    </dt>
                                    <dd
                                        class="mt-0.5 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        {{
                                            previewDetail.personal
                                                .place_of_birth
                                        }}
                                    </dd>
                                </div>
                                <div v-if="previewDetail.personal.sex">
                                    <dt
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Sex
                                    </dt>
                                    <dd
                                        class="mt-0.5 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        {{ previewDetail.personal.sex }}
                                    </dd>
                                </div>
                                <div v-if="previewDetail.personal.civil_status">
                                    <dt
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Civil status
                                    </dt>
                                    <dd
                                        class="mt-0.5 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        {{
                                            previewDetail.personal.civil_status
                                        }}
                                    </dd>
                                </div>
                                <div v-if="previewDetail.personal.email">
                                    <dt
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Email
                                    </dt>
                                    <dd
                                        class="mt-0.5 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        {{ previewDetail.personal.email }}
                                    </dd>
                                </div>
                                <div v-if="previewDetail.personal.phone">
                                    <dt
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Phone
                                    </dt>
                                    <dd
                                        class="mt-0.5 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        {{ previewDetail.personal.phone }}
                                    </dd>
                                </div>
                                <div v-if="previewDetail.personal.mobile">
                                    <dt
                                        class="text-xs font-medium text-gray-500 dark:text-gray-400"
                                    >
                                        Mobile
                                    </dt>
                                    <dd
                                        class="mt-0.5 text-sm text-gray-700 dark:text-gray-300"
                                    >
                                        {{ previewDetail.personal.mobile }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                        <div
                            v-else
                            class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-gray-400"
                        >
                            No personal details recorded for this PDS.
                        </div>
                    </template>
                </div>

                <DialogFooter class="flex-wrap gap-2">
                    <Button
                        type="button"
                        variant="outline"
                        @click="closePreviewModal"
                    >
                        Close
                    </Button>

                    <a
                        v-if="
                            previewDetail && previewDetail.status === 'approved'
                        "
                        :href="hr.pds.export.url({ id: previewDetail.id })"
                        target="_blank"
                        class="inline-flex items-center gap-2 rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                    >
                        <FileText class="size-4 text-red-500" />
                        Export CS Form 212
                    </a>

                    <Button
                        v-if="
                            previewDetail &&
                            (previewDetail.status === 'submitted' ||
                                previewDetail.status === 'under_review')
                        "
                        type="button"
                        :disabled="actionLoading"
                        @click="submitApprove(previewDetail)"
                    >
                        {{ actionLoading ? 'Processing...' : 'Approve' }}
                    </Button>
                    <Button
                        v-if="
                            previewDetail &&
                            (previewDetail.status === 'submitted' ||
                                previewDetail.status === 'under_review')
                        "
                        type="button"
                        variant="destructive"
                        :disabled="actionLoading"
                        @click="openReject(previewDetail)"
                    >
                        Reject
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <Dialog v-model:open="rejectDialogOpen">
            <DialogContent class="sm:max-w-lg">
                <DialogHeader>
                    <DialogTitle>Reject PDS</DialogTitle>
                    <DialogDescription>
                        Please provide remarks so the employee knows what to
                        correct. This field is required.
                    </DialogDescription>
                </DialogHeader>

                <div v-if="rejectTarget" class="space-y-4">
                    <div class="space-y-1.5">
                        <label
                            for="reject-remarks"
                            class="text-sm font-medium text-gray-700 dark:text-gray-300"
                        >
                            Rejection remarks
                            <span class="text-red-500">*</span>
                        </label>
                        <Textarea
                            id="reject-remarks"
                            v-model="rejectRemarks"
                            placeholder="e.g. Please correct your address (missing barangay), missing signature, etc."
                            class="min-h-[120px]"
                            :class="{
                                'border-red-500 focus-visible:ring-red-500':
                                    rejectRemarksError,
                            }"
                            @input="rejectRemarksError = ''"
                        />
                        <p
                            v-if="rejectRemarksError"
                            class="text-xs text-red-500"
                        >
                            {{ rejectRemarksError }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ rejectRemarks.trim().length }} / 10 minimum
                            characters
                        </p>
                    </div>

                    <DialogFooter>
                        <Button
                            type="button"
                            variant="outline"
                            :disabled="actionLoading"
                            @click="rejectDialogOpen = false"
                            >Cancel</Button
                        >
                        <Button
                            type="button"
                            variant="destructive"
                            :disabled="actionLoading"
                            @click="submitReject"
                        >
                            {{
                                actionLoading
                                    ? 'Processing...'
                                    : 'Confirm Rejection'
                            }}
                        </Button>
                    </DialogFooter>
                </div>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
