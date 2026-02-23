<script setup lang="ts">
import { Head, Link, Form } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
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

type PdsData = {
    id: number;
    employee_id: number;
    status: string;
    submitted_at: string | null;
    reviewed_at: string | null;
    created_at: string;
    employee?: { id: number; full_name?: string; first_name?: string; last_name?: string };
    personal?: PdsPersonal | null;
};

const props = defineProps<{ pds: PdsData }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'PDS Management', href: hr.pds.index.url() },
    { title: 'PDS Preview' },
];

function employeeName(): string {
    const e = props.pds.employee;
    if (!e) return `Employee #${props.pds.employee_id}`;
    return ((e as { full_name?: string }).full_name ?? [e.first_name, e.last_name].filter(Boolean).join(' ')) || `#${props.pds.employee_id}`;
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

const statusLabels: Record<string, string> = {
    draft: 'Draft',
    submitted: 'Submitted',
    approved: 'Approved',
    rejected: 'Rejected',
};
</script>

<template>
    <Head title="PDS Preview" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        PDS Preview
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ employeeName() }} — Personal Data Sheet
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <Link
                        :href="hr.pds.index.url()"
                        class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                    >
                        Back to list
                    </Link>
                    <Form
                        v-if="pds.status === 'submitted'"
                        :action="hr.pds.status.url()"
                        method="post"
                        class="inline"
                    >
                        <input type="hidden" name="pds_id" :value="pds.id" />
                        <input type="hidden" name="status" value="approved" />
                        <Button type="submit">Approve</Button>
                    </Form>
                    <Form
                        v-if="pds.status === 'submitted'"
                        :action="hr.pds.status.url()"
                        method="post"
                        class="inline"
                    >
                        <input type="hidden" name="pds_id" :value="pds.id" />
                        <input type="hidden" name="status" value="rejected" />
                        <Button type="submit" variant="destructive">Reject</Button>
                    </Form>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h2 class="text-sm font-medium text-gray-900 dark:text-gray-100">Summary</h2>
                </div>
                <dl class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-2">
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Employee</dt>
                        <dd class="mt-0.5 text-sm font-medium text-gray-900 dark:text-gray-100">{{ employeeName() }} (#{{ pds.employee_id }})</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-0.5">
                            <Badge :variant="statusVariant(pds.status)">
                                {{ statusLabels[pds.status] ?? pds.status }}
                            </Badge>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Submitted</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ formatDate(pds.submitted_at) }}</dd>
                    </div>
                    <div>
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Reviewed</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ formatDate(pds.reviewed_at) }}</dd>
                    </div>
                </dl>
            </div>

            <div v-if="pds.personal" class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                    <h2 class="text-sm font-medium text-gray-900 dark:text-gray-100">Personal information</h2>
                </div>
                <dl class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-2">
                    <div v-if="pds.personal.surname">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Name</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">
                            {{ [pds.personal.surname, pds.personal.first_name, pds.personal.middle_name, pds.personal.name_extension].filter(Boolean).join(', ') }}
                        </dd>
                    </div>
                    <div v-if="pds.personal.dob">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Date of birth</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ formatDate(String(pds.personal.dob)) }}</dd>
                    </div>
                    <div v-if="pds.personal.place_of_birth">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Place of birth</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pds.personal.place_of_birth }}</dd>
                    </div>
                    <div v-if="pds.personal.sex">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Sex</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pds.personal.sex }}</dd>
                    </div>
                    <div v-if="pds.personal.civil_status">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Civil status</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pds.personal.civil_status }}</dd>
                    </div>
                    <div v-if="pds.personal.email">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Email</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pds.personal.email }}</dd>
                    </div>
                    <div v-if="pds.personal.phone">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pds.personal.phone }}</dd>
                    </div>
                    <div v-if="pds.personal.mobile">
                        <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Mobile</dt>
                        <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ pds.personal.mobile }}</dd>
                    </div>
                </dl>
            </div>

            <div v-else class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-gray-400">
                No personal details recorded for this PDS.
            </div>
        </div>
    </AppLayout>
</template>
