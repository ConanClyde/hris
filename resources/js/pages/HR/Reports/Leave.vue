<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
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
}>();

// Build heatmap grid: array of { date, count, level }
const heatmapDays = computed(() => {
    if (!props.heatmapData) return [];
    const year = props.year;
    const start = new Date(year, 0, 1);
    const end = new Date(year, 11, 31);
    const days: { date: string; count: number; level: number; dayOfWeek: number; weekIndex: number }[] = [];

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

type HeatmapDay = { date: string; count: number; level: number; dayOfWeek: number; weekIndex: number };

const heatmapWeeks = computed(() => {
    const weeks: HeatmapDay[][] = [];
    for (const day of heatmapDays.value) {
        if (!weeks[day.weekIndex]) weeks[day.weekIndex] = [];
        weeks[day.weekIndex].push(day);
    }
    return weeks;
});

const heatmapMonthLabels = computed(() => {
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    const labels: { label: string; weekIndex: number }[] = [];
    let lastMonth = -1;
    for (const day of heatmapDays.value) {
        const month = new Date(day.date).getMonth();
        if (month !== lastMonth) {
            labels.push({ label: months[month], weekIndex: day.weekIndex });
            lastMonth = month;
        }
    }
    return labels;
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: hr.reports.url() },
    { title: 'Leave' },
];

const yearInput = ref(String(props.year));

const exportUrl = computed(() => {
    const year = Number(yearInput.value || props.year);
    return hr.reports.leave.export.url({ query: { year: String(year) } });
});

function applyYear() {
    const year = yearInput.value.trim();
    if (!year) return;
    window.location.href = hr.reports.leave.url({ query: { year } });
}
</script>

<template>
    <Head title="Leave Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Leave Reports
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        CSC compliance reporting (utilization, forced leave, wellness).
                    </p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <div class="flex items-center gap-2">
                        <Label for="year" class="text-sm">Year</Label>
                        <Input id="year" v-model="yearInput" class="h-10 w-28" inputmode="numeric" />
                        <Button type="button" variant="outline" class="h-10" @click="applyYear">
                            Apply
                        </Button>
                    </div>
                    <a
                        :href="exportUrl"
                        target="_blank"
                        rel="noopener"
                        class="inline-flex h-10 items-center justify-center rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                    >
                        Export CSV
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">Employees tracked</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ employees.length }}
                        </p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">Forced leave non-compliant</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ forcedCompliance.filter(r => r.requires_forced && !r.compliant).length }}
                        </p>
                    </CardContent>
                </Card>
                <Card>
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">Wellness cap risk (&gt;= 5)</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ employees.filter(e => e.wellness_used >= 5).length }}
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Leave Pattern Heatmap -->
            <Card>
                <CardHeader>
                    <CardTitle>Leave Pattern Heatmap</CardTitle>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Daily leave density across the year — darker = more leaves</p>
                </CardHeader>
                <CardContent>
                    <div class="overflow-x-auto">
                        <div class="inline-block">
                            <!-- Month labels -->
                            <div class="mb-1 flex" style="padding-left: 28px;">
                                <div
                                    v-for="(ml, i) in heatmapMonthLabels"
                                    :key="i"
                                    class="text-[10px] text-gray-400"
                                    :style="{ position: 'absolute', left: `${28 + ml.weekIndex * 14}px` }"
                                >
                                    {{ ml.label }}
                                </div>
                            </div>
                            <!-- Heatmap grid -->
                            <div class="relative mt-5 flex gap-[2px]" style="padding-left: 28px;">
                                <!-- Day of week labels -->
                                <div class="absolute left-0 top-0 flex flex-col gap-[2px]">
                                    <div class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"></div>
                                    <div class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400">M</div>
                                    <div class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"></div>
                                    <div class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400">W</div>
                                    <div class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"></div>
                                    <div class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400">F</div>
                                    <div class="h-[12px] w-[24px] text-right text-[10px] leading-[12px] text-gray-400"></div>
                                </div>
                                <!-- Weeks -->
                                <div
                                    v-for="(week, wi) in heatmapWeeks"
                                    :key="wi"
                                    class="flex flex-col gap-[2px]"
                                >
                                    <div
                                        v-for="day in week"
                                        :key="day.date"
                                        class="h-[12px] w-[12px] rounded-[2px] transition-colors"
                                        :class="{
                                            'bg-gray-100 dark:bg-neutral-800': day.level === 0,
                                            'bg-emerald-200 dark:bg-emerald-900': day.level === 1,
                                            'bg-emerald-400 dark:bg-emerald-700': day.level === 2,
                                            'bg-emerald-500 dark:bg-emerald-600': day.level === 3,
                                            'bg-emerald-700 dark:bg-emerald-400': day.level === 4,
                                        }"
                                        :title="`${day.date}: ${day.count} leave(s)`"
                                    />
                                </div>
                            </div>
                            <!-- Legend -->
                            <div class="mt-3 flex items-center gap-1 text-[10px] text-gray-400" style="padding-left: 28px;">
                                <span>Less</span>
                                <div class="h-[12px] w-[12px] rounded-[2px] bg-gray-100 dark:bg-neutral-800"></div>
                                <div class="h-[12px] w-[12px] rounded-[2px] bg-emerald-200 dark:bg-emerald-900"></div>
                                <div class="h-[12px] w-[12px] rounded-[2px] bg-emerald-400 dark:bg-emerald-700"></div>
                                <div class="h-[12px] w-[12px] rounded-[2px] bg-emerald-500 dark:bg-emerald-600"></div>
                                <div class="h-[12px] w-[12px] rounded-[2px] bg-emerald-700 dark:bg-emerald-400"></div>
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
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[720px] border-collapse text-sm">
                            <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Employee</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">VL Balance</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Forced Used</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Requires Forced</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Compliant</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                <tr v-for="row in forcedCompliance" :key="row.employee_id">
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                        {{ row.employee_name }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ row.vl_balance.toFixed(2) }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ row.forced_used.toFixed(2) }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
                                        {{ row.requires_forced ? 'Yes' : 'No' }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">
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
                    <div class="overflow-x-auto">
                        <table class="w-full min-w-[720px] border-collapse text-sm">
                            <thead class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50">
                                <tr>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Employee</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Type</th>
                                    <th class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300">Used Days</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-neutral-700">
                                <tr v-for="row in usageRows" :key="String(row.employee_fk) + '-' + row.type">
                                    <td class="px-4 py-3 text-gray-900 dark:text-gray-100">
                                        {{ row.employee_name || row.employee_id }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ row.type }}</td>
                                    <td class="px-4 py-3 text-gray-700 dark:text-gray-300">{{ row.used_days.toFixed(2) }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
