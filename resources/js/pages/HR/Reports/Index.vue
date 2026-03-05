<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type MonthData = { month: string; count: number };
type DeptData = { department: string; count: number };
type HeadcountData = { year: number; count: number };

const props = defineProps<{
    summary: {
        totalUsers: number;
        totalLeave?: number;
        totalTraining?: number;
        pendingLeave?: number;
    };
    hiresPerMonth?: MonthData[];
    departmentDistribution?: DeptData[];
    headcountTrend?: HeadcountData[];
    currentYear?: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports' },
];

// Bar chart helpers
const maxHires = computed(() => Math.max(...(props.hiresPerMonth?.map(m => m.count) ?? [1]), 1));
const maxHeadcount = computed(() => Math.max(...(props.headcountTrend?.map(h => h.count) ?? [1]), 1));
const maxDeptCount = computed(() => Math.max(...(props.departmentDistribution?.map(d => d.count) ?? [1]), 1));
const totalHires = computed(() => props.hiresPerMonth?.reduce((s, m) => s + m.count, 0) ?? 0);
</script>

<template>
    <Head title="Reports & Analytics" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Reports &amp; Analytics
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        HR workforce analytics and reporting dashboard.
                    </p>
                </div>
                <Link
                    :href="hr.reports.leave.url()"
                    class="inline-flex h-9 items-center rounded-md border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                >
                    Leave Reports →
                </Link>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">Total Employees</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ summary.totalUsers }}</p>
                    </CardContent>
                </Card>
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">New Hires This Year</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold text-emerald-600 dark:text-emerald-400">{{ totalHires }}</p>
                    </CardContent>
                </Card>
                <Card v-if="summary.totalLeave !== undefined" class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">Total Leave Records</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ summary.totalLeave }}</p>
                    </CardContent>
                </Card>
                <Card v-if="summary.totalTraining !== undefined" class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">Total Training Records</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ summary.totalTraining }}</p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <!-- Monthly Hires Bar Chart -->
                <Card v-if="hiresPerMonth" class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader>
                        <CardTitle class="text-base">Monthly Hires — {{ currentYear }}</CardTitle>
                        <p class="text-xs text-gray-500 dark:text-gray-400">New employees hired per month</p>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-end gap-2" style="height: 180px">
                            <div
                                v-for="m in hiresPerMonth"
                                :key="m.month"
                                class="flex flex-1 flex-col items-center justify-end"
                            >
                                <span class="mb-1 text-[10px] font-medium text-gray-600 dark:text-gray-300">
                                    {{ m.count > 0 ? m.count : '' }}
                                </span>
                                <div
                                    class="w-full rounded-t-sm bg-brand transition-all"
                                    :style="{ height: m.count > 0 ? `${(m.count / maxHires) * 140}px` : '4px', opacity: m.count > 0 ? 1 : 0.2 }"
                                />
                                <span class="mt-2 text-[10px] text-gray-400">{{ m.month }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Headcount Trend -->
                <Card v-if="headcountTrend" class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader>
                        <CardTitle class="text-base">Headcount Trend</CardTitle>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Employee count at year-end (last 5 years)</p>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-end gap-4" style="height: 180px">
                            <div
                                v-for="h in headcountTrend"
                                :key="h.year"
                                class="flex flex-1 flex-col items-center justify-end"
                            >
                                <span class="mb-1 text-xs font-semibold text-gray-700 dark:text-gray-200">
                                    {{ h.count }}
                                </span>
                                <div
                                    class="w-full rounded-t-sm bg-indigo-500 transition-all dark:bg-indigo-400"
                                    :style="{ height: h.count > 0 ? `${(h.count / maxHeadcount) * 140}px` : '4px' }"
                                />
                                <span class="mt-2 text-xs text-gray-500">{{ h.year }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Department Distribution -->
            <Card v-if="departmentDistribution && departmentDistribution.length > 0" class="border border-gray-200 dark:border-neutral-800">
                <CardHeader>
                    <CardTitle class="text-base">Department Distribution</CardTitle>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Number of employees per department/division</p>
                </CardHeader>
                <CardContent>
                    <div class="space-y-3">
                        <div v-for="dept in departmentDistribution" :key="dept.department" class="flex items-center gap-3">
                            <span class="w-40 shrink-0 truncate text-sm font-medium text-gray-700 dark:text-gray-300">{{ dept.department }}</span>
                            <div class="h-6 flex-1 overflow-hidden rounded-full bg-gray-100 dark:bg-neutral-800">
                                <div
                                    class="h-full rounded-full bg-brand transition-all"
                                    :style="{ width: `${(dept.count / maxDeptCount) * 100}%` }"
                                />
                            </div>
                            <span class="w-10 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">{{ dept.count }}</span>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
