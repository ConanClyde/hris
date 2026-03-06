<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type Adjustment = {
    id: number;
    amount: number | string;
    reason: string | null;
    created_at: string;
};

type CreditData = {
    id: number;
    employee_id: number;
    leave_type: string;
    balance: number | string;
    employee?: {
        id: number;
        full_name?: string;
        first_name?: string;
        last_name?: string;
    };
    adjustments?: Adjustment[];
};

const props = defineProps<{ credit: CreditData }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Leave Credits', href: hr.leaveCredits.index.url() },
    { title: 'Leave credit detail' },
];

function employeeName(): string {
    const e = props.credit.employee;
    if (!e) return `Employee #${props.credit.employee_id}`;
    return (
        ((e as { full_name?: string }).full_name ??
            [e.first_name, e.last_name].filter(Boolean).join(' ')) ||
        `#${props.credit.employee_id}`
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
</script>

<template>
    <Head title="Leave Credit" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                    >
                        Leave credit
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        {{ employeeName() }} — {{ credit.leave_type }}
                    </p>
                </div>
                <Button as-child variant="outline">
                    <Link :href="hr.leaveCredits.index.url()"
                        >Back to list</Link
                    >
                </Button>
            </div>

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
                <dl class="grid grid-cols-1 gap-3 px-4 py-4 sm:grid-cols-2">
                    <div>
                        <dt
                            class="text-xs font-medium text-gray-500 dark:text-gray-400"
                        >
                            Employee
                        </dt>
                        <dd
                            class="mt-0.5 text-sm font-medium text-gray-900 dark:text-gray-100"
                        >
                            {{ employeeName() }} (#{{ credit.employee_id }})
                        </dd>
                    </div>
                    <div>
                        <dt
                            class="text-xs font-medium text-gray-500 dark:text-gray-400"
                        >
                            Leave type
                        </dt>
                        <dd
                            class="mt-0.5 text-sm text-gray-700 dark:text-gray-300"
                        >
                            {{ credit.leave_type ?? '—' }}
                        </dd>
                    </div>
                    <div>
                        <dt
                            class="text-xs font-medium text-gray-500 dark:text-gray-400"
                        >
                            Balance
                        </dt>
                        <dd
                            class="mt-0.5 text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >
                            {{ credit.balance ?? 0 }}
                        </dd>
                    </div>
                </dl>
            </div>

            <div
                v-if="credit.adjustments?.length"
                class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
            >
                <div
                    class="border-b border-gray-200 bg-gray-50 px-4 py-3 dark:border-neutral-700 dark:bg-neutral-800/50"
                >
                    <h2
                        class="text-sm font-medium text-gray-900 dark:text-gray-100"
                    >
                        Adjustments
                    </h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse text-sm">
                        <thead
                            class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50"
                        >
                            <tr>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Date
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Amount
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Reason
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-neutral-700"
                        >
                            <tr
                                v-for="adj in credit.adjustments"
                                :key="adj.id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td
                                    class="px-4 py-3 text-gray-700 dark:text-gray-300"
                                >
                                    {{ formatDate(adj.created_at) }}
                                </td>
                                <td
                                    class="px-4 py-3 font-medium"
                                    :class="
                                        Number(adj.amount) >= 0
                                            ? 'text-green-600 dark:text-green-400'
                                            : 'text-red-600 dark:text-red-400'
                                    "
                                >
                                    {{ Number(adj.amount) >= 0 ? '+' : ''
                                    }}{{ adj.amount }}
                                </td>
                                <td
                                    class="px-4 py-3 text-gray-600 dark:text-gray-400"
                                >
                                    {{ adj.reason ?? '—' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div
                v-else
                class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-8 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-gray-400"
            >
                No adjustments recorded.
            </div>
        </div>
    </AppLayout>
</template>
