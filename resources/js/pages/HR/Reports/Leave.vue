<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import ListFiltersBar from '@/components/ListFiltersBar.vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type UsageRow = {
    employee_fk: number | null;
    employee_id: string;
    employee_name: string | null;
    type: string;
    used_days: number;
};

type EmployeeSummary = {
    employee_id: number;
    employee_name: string;
    vl_balance: number;
    forced_used: number;
    wellness_used: number;
    wellness_balance: number;
};

type ForcedComplianceRow = {
    employee_id: number;
    employee_name: string;
    vl_balance: number;
    forced_used: number;
    requires_forced: boolean;
    compliant: boolean;
};

const props = defineProps<{
    year: number;
    types: string[];
    usageRows: UsageRow[];
    employees: EmployeeSummary[];
    forcedCompliance: ForcedComplianceRow[];
    heatmapData?: Record<string, number>;
    filters?: {
        from?: string | null;
        to?: string | null;
    };
}>();

// Build heatmap grid: array of { date, count, level }
const heatmapDays = computed(() => {
    if (!props.heatmapData) return [];
    const year = props.year;
    const start = new Date(year, 0, 1);
    const end = new Date(year, 11, 31);
    const days: {
        date: string;
        count: number;
        level: number;
        dayOfWeek: number;
        weekIndex: number;
    }[] = [];

    // Find first Sunday
    const current = new Date(start);
    let weekIndex = 0;

    while (current <= end) {
        const dateStr = current.toISOString().slice(0, 10);
        const count = props.heatmapData[dateStr] || 0;
        let level = 0;
        if (count >= 5) level = 4;
        else if (count >= 3) level = 3;
        else if (count >= 2) level = 2;
        else if (count >= 1) level = 1;

        days.push({
            date: dateStr,
            count,
            level,
            dayOfWeek: current.getDay(),
            weekIndex,
        });

        // Advance
        if (current.getDay() === 6) weekIndex++;
        current.setDate(current.getDate() + 1);
    }

    return days;
});

type HeatmapDay = {
    date: string;
    count: number;
    level: number;
    dayOfWeek: number;
    weekIndex: number;
};

const heatmapWeeks = computed(() => {
    const weeks: (HeatmapDay | null)[][] = [];
    for (const day of heatmapDays.value) {
        if (!weeks[day.weekIndex]) weeks[day.weekIndex] = Array(7).fill(null);
        weeks[day.weekIndex][day.dayOfWeek] = day;
    }

    return weeks;
});

const heatmapMonthLabels = computed(() => {
    const months = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec',
    ];
    const labels: { label: string; weekIndex: number }[] = [];
    const byDate = new Map<string, number>();
    for (const day of heatmapDays.value) {
        byDate.set(day.date, day.weekIndex);
    }

    for (let month = 0; month < 12; month++) {
        const monthStart = new Date(props.year, month, 1)
            .toISOString()
            .slice(0, 10);
        const weekIndex = byDate.get(monthStart);
        if (typeof weekIndex === 'number') {
            labels.push({ label: months[month], weekIndex });
        }
    }

    return labels;
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: hr.reports.url() },
    { title: 'Leave' },
];

const yearInput = ref(String(props.year));
const fromInput = ref<string>(
    (props.filters?.from as string | undefined) ?? '',
);
const toInput = ref<string>((props.filters?.to as string | undefined) ?? '');

const exportUrl = computed(() => {
    const year = Number(yearInput.value || props.year);
    return hr.reports.leave.export.url({
        query: {
            year: String(year),
            from: fromInput.value || undefined,
            to: toInput.value || undefined,
        },
    });
});

function applyYear() {
    const year = yearInput.value.trim();
    if (!year) return;
    window.location.href = hr.reports.leave.url({
        query: {
            year,
            from: fromInput.value || undefined,
            to: toInput.value || undefined,
        },
    });
}

function applyRange() {
    const year = yearInput.value.trim() || String(props.year);

    window.location.href = hr.reports.leave.url({
        query: {
            year,
            from: fromInput.value || undefined,
            to: toInput.value || undefined,
        },
    });
}
</script>

<template>
    <Head title="Leave Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div>
                    <h1
                        class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                    >
                        Leave Reports
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        CSC compliance reporting (utilization, forced leave,
                        wellness).
                    </p>
                </div>
            </div>

            <ListFiltersBar>
                <div class="w-[110px]">
                    <Label for="year" class="sr-only">Year</Label>
                    <Input
                        id="year"
                        v-model="yearInput"
                        class="h-10"
                        inputmode="numeric"
                    />
                </div>
                <Button
                    type="button"
                    variant="outline"
                    class="h-10"
                    @click="applyYear"
                >
                    Apply
                </Button>

                <div class="w-[170px]">
                    <Label for="from" class="sr-only">From</Label>
                    <Input
                        id="from"
                        v-model="fromInput"
                        type="date"
                        class="h-10"
                    />
                </div>
                <div class="w-[170px]">
                    <Label for="to" class="sr-only">To</Label>
                    <Input id="to" v-model="toInput" type="date" class="h-10" />
                </div>
                <Button
                    type="button"
                    variant="outline"
                    class="h-10"
                    @click="applyRange"
                >
                    Apply range
                </Button>

                <Button as-child type="button" class="h-10">
                    <a :href="exportUrl" target="_blank" rel="noopener"
                        >Export CSV</a
                    >
                </Button>
            </ListFiltersBar>

            <p class="-mt-2 text-xs text-gray-500 dark:text-gray-400">
                Export includes approved leave applications for the selected
                year (or selected range).
            </p>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                            >Employees tracked</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p
                            class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                        >
                            {{ employees.length }}
                        </p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                            >Forced leave non-compliant</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p
                            class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                        >
                            {{
                                forcedCompliance.filter(
                                    (r) => r.requires_forced && !r.compliant,
                                ).length
                            }}
                        </p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                            >Wellness cap risk (&gt;= 5)</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p
                            class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                        >
                            {{
                                employees.filter((e) => e.wellness_used >= 5)
                                    .length
                            }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Leave Pattern Heatmap -->
            <Card>
                <CardHeader>
                    <CardTitle>Leave Pattern Heatmap</CardTitle>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        Distinct employees on leave per day — darker = more
                        people out
                    </p>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="heatmapDays.length === 0"
                        class="rounded-lg border border-dashed border-gray-200 p-6 text-sm text-gray-500 dark:border-neutral-700 dark:text-gray-400"
                    >
                        No heatmap data available for {{ year }}.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <div class="relative inline-block">
                            <!-- Month labels -->
                            <div class="mb-1 h-4" style="padding-left: 28px">
                                <div
                                    v-for="(ml, i) in heatmapMonthLabels"
                                    :key="i"
                                    class="absolute top-0 text-[10px] text-gray-400"
                                    :style="{
                                        left: `${28 + ml.weekIndex * 14}px`,
                                    }"
                                >
                                    {{ ml.label }}
                                </div>
                            </div>
                            <!-- Heatmap grid -->
                            <div
                                class="relative mt-2 flex gap-[2px]"
                                style="padding-left: 28px"
                            >
                                <!-- Day of week labels -->
                                <div
                                    class="absolute top-0 left-0 flex flex-col gap-[2px]"
                                >
                                    <div
                                        class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"
                                    ></div>
                                    <div
                                        class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"
                                    >
                                        M
                                    </div>
                                    <div
                                        class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"
                                    ></div>
                                    <div
                                        class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"
                                    >
                                        W
                                    </div>
                                    <div
                                        class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"
                                    ></div>
                                    <div
                                        class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"
                                    >
                                        F
                                    </div>
                                    <div
                                        class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"
                                    ></div>
                                </div>
                                <!-- Weeks -->
                                <div
                                    v-for="(week, wi) in heatmapWeeks"
                                    :key="wi"
                                    class="flex flex-col gap-[2px]"
                                >
                                    <div
                                        v-for="(day, di) in week"
                                        :key="day?.date ?? `empty-${wi}-${di}`"
                                        class="h-[12px] w-[12px] rounded-[2px] transition-colors"
                                        :class="{
                                            'bg-gray-100 dark:bg-neutral-800':
                                                !day || day.level === 0,
                                            'bg-emerald-200 dark:bg-emerald-900':
                                                !!day && day.level === 1,
                                            'bg-emerald-400 dark:bg-emerald-700':
                                                !!day && day.level === 2,
                                            'bg-emerald-500 dark:bg-emerald-600':
                                                !!day && day.level === 3,
                                            'bg-emerald-700 dark:bg-emerald-400':
                                                !!day && day.level === 4,
                                        }"
                                        :title="
                                            day
                                                ? `${day.date}: ${day.count} employee(s) on leave`
                                                : ''
                                        "
                                    />
                                </div>
                            </div>
                            <!-- Legend -->
                            <div
                                class="mt-3 flex items-center gap-1 text-[10px] text-gray-400"
                                style="padding-left: 28px"
                            >
                                <span>Less</span>
                                <div
                                    class="h-[12px] w-[12px] rounded-[2px] bg-gray-100 dark:bg-neutral-800"
                                ></div>
                                <div
                                    class="h-[12px] w-[12px] rounded-[2px] bg-emerald-200 dark:bg-emerald-900"
                                ></div>
                                <div
                                    class="h-[12px] w-[12px] rounded-[2px] bg-emerald-400 dark:bg-emerald-700"
                                ></div>
                                <div
                                    class="h-[12px] w-[12px] rounded-[2px] bg-emerald-500 dark:bg-emerald-600"
                                ></div>
                                <div
                                    class="h-[12px] w-[12px] rounded-[2px] bg-emerald-700 dark:bg-emerald-400"
                                ></div>
                                <span>More</span>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Forced leave compliance</CardTitle>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="forcedCompliance.length === 0"
                        class="rounded-lg border border-dashed border-gray-200 p-6 text-sm text-gray-500 dark:border-neutral-700 dark:text-gray-400"
                    >
                        No forced leave compliance records for {{ year }}.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <table
                            class="w-full min-w-[720px] border-collapse text-sm"
                        >
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
                                        VL Balance
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                    >
                                        Forced Used
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                    >
                                        Requires Forced
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                    >
                                        Compliant
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-200 dark:divide-neutral-700"
                            >
                                <tr
                                    v-for="row in forcedCompliance"
                                    :key="row.employee_id"
                                >
                                    <td
                                        class="px-4 py-3 text-gray-900 dark:text-gray-100"
                                    >
                                        {{ row.employee_name }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-gray-700 dark:text-gray-300"
                                    >
                                        {{ row.vl_balance.toFixed(2) }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-gray-700 dark:text-gray-300"
                                    >
                                        {{ row.forced_used.toFixed(2) }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-gray-700 dark:text-gray-300"
                                    >
                                        {{ row.requires_forced ? 'Yes' : 'No' }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-gray-700 dark:text-gray-300"
                                    >
                                        {{ row.compliant ? 'Yes' : 'No' }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>

            <Card>
                <CardHeader>
                    <CardTitle>Utilization (approved)</CardTitle>
                </CardHeader>
                <CardContent>
                    <div
                        v-if="usageRows.length === 0"
                        class="rounded-lg border border-dashed border-gray-200 p-6 text-sm text-gray-500 dark:border-neutral-700 dark:text-gray-400"
                    >
                        No approved leave utilization records for {{ year }}.
                    </div>
                    <div v-else class="overflow-x-auto">
                        <table
                            class="w-full min-w-[720px] border-collapse text-sm"
                        >
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
                                        Type
                                    </th>
                                    <th
                                        class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                    >
                                        Used Days
                                    </th>
                                </tr>
                            </thead>
                            <tbody
                                class="divide-y divide-gray-200 dark:divide-neutral-700"
                            >
                                <tr
                                    v-for="row in usageRows"
                                    :key="
                                        String(row.employee_fk) + '-' + row.type
                                    "
                                >
                                    <td
                                        class="px-4 py-3 text-gray-900 dark:text-gray-100"
                                    >
                                        {{
                                            row.employee_name || row.employee_id
                                        }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-gray-700 dark:text-gray-300"
                                    >
                                        {{ row.type }}
                                    </td>
                                    <td
                                        class="px-4 py-3 text-gray-700 dark:text-gray-300"
                                    >
                                        {{ row.used_days.toFixed(2) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
