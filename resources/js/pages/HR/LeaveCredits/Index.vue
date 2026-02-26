<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Eye } from 'lucide-vue-next';
import TableUserCell from '@/components/TableUserCell.vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type Adjustment = {
    id: number;
    amount: number | string;
    reason: string | null;
    created_at: string;
};

type CreditItem = {
    id: number;
    employee_id: number;
    leave_type: string;
    balance: number | string;
    employee?: { id: number; full_name?: string; first_name?: string; last_name?: string };
};

type CreditDetail = CreditItem & {
    adjustments?: Adjustment[];
};

type PaginatedData = {
    data: CreditItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = withDefaults(
    defineProps<{ credits: PaginatedData; creditDetail?: CreditDetail | null }>(),
    { creditDetail: null }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Leave Credits' },
];

function employeeName(item: CreditItem | CreditDetail): string {
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

function openView(item: CreditItem) {
    const params: Record<string, string> = { view: String(item.id) };
    if (props.credits.current_page > 1) params.page = String(props.credits.current_page);
    router.get(hr.leaveCredits.index.url(), params);
}

function closeViewModal() {
    const query: Record<string, string> = {};
    if (props.credits.current_page > 1) query.page = String(props.credits.current_page);
    router.get(hr.leaveCredits.index.url(), Object.keys(query).length ? query : undefined);
}
</script>

<template>
    <Head title="Leave Credits" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Leave Credits
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    View leave credit balances by employee.
                </p>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[400px] border-collapse text-sm">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                            <tr>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Employee
                                </th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Leave type
                                </th>
                                <th class="text-left font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Balance
                                </th>
                                <th class="text-right font-medium text-gray-700 dark:text-gray-300 px-4 py-3">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                            <tr
                                v-for="item in credits.data"
                                :key="item.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3">
                                    <TableUserCell
                                        :name="employeeName(item)"
                                        :subtitle="`#${item.employee_id}`"
                                    />
                                </td>
                                <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                    {{ item.leave_type ?? '—' }}
                                </td>
                                <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
                                    {{ item.balance ?? 0 }}
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <Button
                                        type="button"
                                        variant="ghost"
                                        size="icon"
                                        class="h-8 w-8 text-muted-foreground hover:text-primary"
                                        title="View"
                                        @click="openView(item)"
                                    >
                                        <Eye class="size-4" />
                                    </Button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="!credits.data.length"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No leave credits found.
                </div>
            </div>

            <div
                v-if="credits.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in credits.links" :key="i">
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
                            ? 'border-brand bg-brand text-white dark:border-brand-light dark:bg-brand-light dark:text-gray-900'
                            : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>

        <!-- View credit modal -->
        <Dialog
            :open="Boolean(creditDetail)"
            @update:open="(v) => !v && closeViewModal()"
        >
            <DialogContent :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Leave credit</DialogTitle>
                    <DialogDescription class="sr-only">
                        View leave credit details and adjustment history.
                    </DialogDescription>
                </DialogHeader>
                <template v-if="creditDetail">
                    <div class="max-h-[60vh] overflow-y-auto p-1 space-y-4">
                        <div class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                                <h2 class="text-sm font-medium text-gray-900 dark:text-gray-100">Summary</h2>
                            </div>
                            <dl class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Employee</dt>
                                    <dd class="mt-0.5 text-sm font-medium text-gray-900 dark:text-gray-100">{{ employeeName(creditDetail) }} (#{{ creditDetail.employee_id }})</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Leave type</dt>
                                    <dd class="mt-0.5 text-sm text-gray-700 dark:text-gray-300">{{ creditDetail.leave_type ?? '—' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-xs font-medium text-gray-500 dark:text-gray-400">Balance</dt>
                                    <dd class="mt-0.5 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ creditDetail.balance ?? 0 }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div v-if="creditDetail.adjustments?.length" class="rounded-lg border border-gray-200 dark:border-neutral-700 overflow-hidden">
                            <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/50">
                                <h2 class="text-sm font-medium text-gray-900 dark:text-gray-100">Adjustments</h2>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full border-collapse text-sm">
                                    <thead class="border-b border-gray-200 dark:border-neutral-700">
                                        <tr>
                                            <th class="text-left font-medium text-gray-600 dark:text-gray-400 px-4 py-2">Date</th>
                                            <th class="text-left font-medium text-gray-600 dark:text-gray-400 px-4 py-2">Amount</th>
                                            <th class="text-left font-medium text-gray-600 dark:text-gray-400 px-4 py-2">Reason</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                        <tr
                                            v-for="adj in creditDetail.adjustments"
                                            :key="adj.id"
                                        >
                                            <td class="px-4 py-2 text-gray-700 dark:text-gray-300">{{ formatDate(adj.created_at) }}</td>
                                            <td class="px-4 py-2 font-medium" :class="Number(adj.amount) >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400'">
                                                {{ Number(adj.amount) >= 0 ? '+' : '' }}{{ adj.amount }}
                                            </td>
                                            <td class="px-4 py-2 text-gray-600 dark:text-gray-400">{{ adj.reason ?? '—' }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div v-else class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-6 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-gray-400">
                            No adjustments recorded.
                        </div>
                    </div>
                    <DialogFooter>
                        <Button type="button" variant="outline" @click="closeViewModal">
                            Back to list
                        </Button>
                    </DialogFooter>
                </template>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
