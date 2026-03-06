<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type MonthData = { month: string; count: number };
type HeadcountData = { year: number; count: number };
type SexRow = { label: string; value: number };
type OrgSection = { id: number; name: string; count: number };
type OrgSubdivision = {
    id: number;
    name: string;
    count: number;
    sections: OrgSection[];
};
type OrgDivision = {
    id: number;
    name: string;
    count: number;
    subdivisions: OrgSubdivision[];
    sections: OrgSection[];
};
type Recommendation = { title: string; detail: string; level: string };

const props = defineProps<{
    summary: {
        totalUsers: number;
        totalEmployees?: number;
    };
    hiresPerMonth?: MonthData[];
    headcountTrend?: HeadcountData[];
    currentYear?: number;
    orgBreakdown?: OrgDivision[];
    unassigned?: {
        division?: number;
        subdivision?: number;
        section?: number;
    };
    sexDistribution?: SexRow[];
    leaveTrend?: MonthData[];
    trainingTrend?: MonthData[];
    leaveHeatmap?: Record<string, number>;
    trainingHeatmap?: Record<string, number>;
    recommendations?: Recommendation[];
    filters?: {
        from?: string | null;
        to?: string | null;
    };
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Reports' }];

// Bar chart helpers
const maxHires = computed(() =>
    Math.max(...(props.hiresPerMonth?.map((m) => m.count) ?? [1]), 1),
);
const maxHeadcount = computed(() =>
    Math.max(...(props.headcountTrend?.map((h) => h.count) ?? [1]), 1),
);
const totalHires = computed(
    () => props.hiresPerMonth?.reduce((s, m) => s + m.count, 0) ?? 0,
);
const maxSex = computed(() =>
    Math.max(...(props.sexDistribution?.map((s) => s.value) ?? [1]), 1),
);
const maxLeaveTrend = computed(() =>
    Math.max(...(props.leaveTrend?.map((m) => m.count) ?? [1]), 1),
);
const maxTrainingTrend = computed(() =>
    Math.max(...(props.trainingTrend?.map((m) => m.count) ?? [1]), 1),
);

const heatmapLevel = (count: number) => {
    if (count >= 5) return 4;
    if (count >= 3) return 3;
    if (count >= 2) return 2;
    if (count >= 1) return 1;
    return 0;
};

const heatmapEntries = (data?: Record<string, number>) => {
    if (!data)
        return [] as Array<{ date: string; count: number; level: number }>;
    return Object.entries(data)
        .map(([date, count]) => ({ date, count, level: heatmapLevel(count) }))
        .sort((a, b) => a.date.localeCompare(b.date));
};

const leaveHeatmapDays = computed(() => heatmapEntries(props.leaveHeatmap));
const trainingHeatmapDays = computed(() =>
    heatmapEntries(props.trainingHeatmap),
);

const fromInput = computed({
    get: () => props.filters?.from ?? '',
    set: (v: string) => {
        router.get(
            hr.reports().url,
            { from: v || undefined, to: props.filters?.to || undefined },
            { preserveState: true, replace: true },
        );
    },
});

const toInput = computed({
    get: () => props.filters?.to ?? '',
    set: (v: string) => {
        router.get(
            hr.reports().url,
            { from: props.filters?.from || undefined, to: v || undefined },
            { preserveState: true, replace: true },
        );
    },
});
</script>

<template>
    <Head title="Reports & Analytics" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1
                        class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                    >
                        Reports &amp; Analytics
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        HR workforce analytics and reporting dashboard.
                    </p>
                </div>
                <div class="flex gap-2">
                    <Link
                        :href="hr.reports.leave.url()"
                        class="inline-flex h-9 items-center rounded-md border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                    >
                        Leave Reports →
                    </Link>
                    <Link
                        :href="hr.reports.tardiness.url()"
                        class="inline-flex h-9 items-center rounded-md border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                    >
                        Tardiness Report →
                    </Link>
                    <Link
                        :href="hr.reports.attendance.url()"
                        class="inline-flex h-9 items-center rounded-md border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                    >
                        Attendance Report →
                    </Link>
                </div>
            </div>

            <div
                class="rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-800 dark:bg-neutral-900"
            >
                <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:max-w-xl">
                    <div>
                        <Label for="from" class="text-sm">From</Label>
                        <Input
                            id="from"
                            v-model="fromInput"
                            type="date"
                            class="mt-1 h-10"
                        />
                    </div>
                    <div>
                        <Label for="to" class="text-sm">To</Label>
                        <Input
                            id="to"
                            v-model="toInput"
                            type="date"
                            class="mt-1 h-10"
                        />
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                    Leaving both blank defaults to the current year.
                </p>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                            >Users</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p
                            class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{ summary.totalUsers }}
                        </p>
                    </CardContent>
                </Card>
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                            >Employees</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p
                            class="text-2xl font-bold text-emerald-600 dark:text-emerald-400"
                        >
                            {{ summary.totalEmployees ?? 0 }}
                        </p>
                    </CardContent>
                </Card>
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                            >New Hires (range)</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p
                            class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{ totalHires }}
                        </p>
                    </CardContent>
                </Card>
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle
                            class="text-sm font-normal text-gray-500 dark:text-gray-400"
                            >Unassigned (org)</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <p
                            class="text-2xl font-bold text-gray-900 dark:text-gray-100"
                        >
                            {{
                                (unassigned?.division ?? 0) +
                                (unassigned?.subdivision ?? 0) +
                                (unassigned?.section ?? 0)
                            }}
                        </p>
                    </CardContent>
                </Card>
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader>
                        <CardTitle class="text-base">Recommendations</CardTitle>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Data quality and workforce signals
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="recommendations && recommendations.length > 0"
                            class="space-y-3"
                        >
                            <div
                                v-for="(rec, idx) in recommendations"
                                :key="idx"
                                class="rounded-md border border-gray-200 p-3 dark:border-neutral-800"
                            >
                                <div
                                    class="flex items-center justify-between gap-3"
                                >
                                    <p
                                        class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                    >
                                        {{ rec.title }}
                                    </p>
                                    <span
                                        class="inline-flex rounded-sm px-2 py-0.5 text-xs font-medium"
                                        :class="{
                                            'bg-amber-50 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400':
                                                rec.level === 'warning',
                                            'bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400':
                                                rec.level === 'info',
                                            'bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400':
                                                rec.level === 'success',
                                        }"
                                    >
                                        {{ rec.level }}
                                    </span>
                                </div>
                                <p
                                    class="mt-1 text-sm text-gray-600 dark:text-gray-300"
                                >
                                    {{ rec.detail }}
                                </p>
                            </div>
                        </div>
                        <p
                            v-else
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            —
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader>
                        <CardTitle class="text-base"
                            >Sex distribution</CardTitle
                        >
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Based on PDS personal data
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="sexDistribution && sexDistribution.length > 0"
                            class="space-y-3"
                        >
                            <div
                                v-for="s in sexDistribution"
                                :key="s.label"
                                class="flex items-center gap-3"
                            >
                                <span
                                    class="w-24 shrink-0 text-sm font-medium text-gray-700 dark:text-gray-300"
                                    >{{ s.label }}</span
                                >
                                <div
                                    class="h-6 flex-1 overflow-hidden rounded-full bg-gray-100 dark:bg-neutral-800"
                                >
                                    <div
                                        class="h-full rounded-full bg-brand"
                                        :style="{
                                            width: `${(s.value / maxSex) * 100}%`,
                                        }"
                                    />
                                </div>
                                <span
                                    class="w-10 text-right text-sm font-semibold text-gray-900 dark:text-gray-100"
                                    >{{ s.value }}</span
                                >
                            </div>
                        </div>
                        <p
                            v-else
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            —
                        </p>
                    </CardContent>
                </Card>

                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader>
                        <CardTitle class="text-base">Org breakdown</CardTitle>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Counts per division / subdivision / section
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="orgBreakdown && orgBreakdown.length > 0"
                            class="space-y-4"
                        >
                            <div
                                v-for="div in orgBreakdown"
                                :key="div.id"
                                class="rounded-md border border-gray-200 p-3 dark:border-neutral-800"
                            >
                                <div class="flex items-center justify-between">
                                    <p
                                        class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                    >
                                        {{ div.name }}
                                    </p>
                                    <p
                                        class="text-sm font-semibold text-gray-900 dark:text-gray-100"
                                    >
                                        {{ div.count }}
                                    </p>
                                </div>
                                <div
                                    v-if="div.subdivisions?.length"
                                    class="mt-2 space-y-2"
                                >
                                    <div
                                        v-for="sub in div.subdivisions"
                                        :key="sub.id"
                                        class="pl-3"
                                    >
                                        <div
                                            class="flex items-center justify-between"
                                        >
                                            <p
                                                class="text-xs font-medium text-gray-700 dark:text-gray-300"
                                            >
                                                {{ sub.name }}
                                            </p>
                                            <p
                                                class="text-xs font-medium text-gray-700 dark:text-gray-300"
                                            >
                                                {{ sub.count }}
                                            </p>
                                        </div>
                                        <div
                                            v-if="sub.sections?.length"
                                            class="mt-1 space-y-1 pl-3"
                                        >
                                            <div
                                                v-for="sec in sub.sections"
                                                :key="sec.id"
                                                class="flex items-center justify-between"
                                            >
                                                <p
                                                    class="text-[11px] text-gray-600 dark:text-gray-400"
                                                >
                                                    {{ sec.name }}
                                                </p>
                                                <p
                                                    class="text-[11px] text-gray-600 dark:text-gray-400"
                                                >
                                                    {{ sec.count }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div
                                    v-if="div.sections?.length"
                                    class="mt-2 space-y-1 pl-3"
                                >
                                    <div
                                        v-for="sec in div.sections"
                                        :key="sec.id"
                                        class="flex items-center justify-between"
                                    >
                                        <p
                                            class="text-[11px] text-gray-600 dark:text-gray-400"
                                        >
                                            {{ sec.name }}
                                        </p>
                                        <p
                                            class="text-[11px] text-gray-600 dark:text-gray-400"
                                        >
                                            {{ sec.count }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <p
                            v-else
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            —
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader>
                        <CardTitle class="text-base"
                            >Leave trend (distinct employees)</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="leaveTrend"
                            class="flex items-end gap-2"
                            style="height: 180px"
                        >
                            <div
                                v-for="m in leaveTrend"
                                :key="m.month"
                                class="flex flex-1 flex-col items-center justify-end"
                            >
                                <span
                                    class="mb-1 text-[10px] font-medium text-gray-600 dark:text-gray-300"
                                >
                                    {{ m.count > 0 ? m.count : '' }}
                                </span>
                                <div
                                    class="w-full rounded-t-sm bg-amber-500 transition-all"
                                    :style="{
                                        height:
                                            m.count > 0
                                                ? `${(m.count / maxLeaveTrend) * 140}px`
                                                : '4px',
                                        opacity: m.count > 0 ? 1 : 0.2,
                                    }"
                                />
                                <span class="mt-2 text-[10px] text-gray-400">{{
                                    m.month
                                }}</span>
                            </div>
                        </div>
                        <p
                            v-else
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            —
                        </p>
                    </CardContent>
                </Card>

                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader>
                        <CardTitle class="text-base"
                            >Training trend (distinct employees)</CardTitle
                        >
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="trainingTrend"
                            class="flex items-end gap-2"
                            style="height: 180px"
                        >
                            <div
                                v-for="m in trainingTrend"
                                :key="m.month"
                                class="flex flex-1 flex-col items-center justify-end"
                            >
                                <span
                                    class="mb-1 text-[10px] font-medium text-gray-600 dark:text-gray-300"
                                >
                                    {{ m.count > 0 ? m.count : '' }}
                                </span>
                                <div
                                    class="w-full rounded-t-sm bg-sky-500 transition-all"
                                    :style="{
                                        height:
                                            m.count > 0
                                                ? `${(m.count / maxTrainingTrend) * 140}px`
                                                : '4px',
                                        opacity: m.count > 0 ? 1 : 0.2,
                                    }"
                                />
                                <span class="mt-2 text-[10px] text-gray-400">{{
                                    m.month
                                }}</span>
                            </div>
                        </div>
                        <p
                            v-else
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            —
                        </p>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader>
                        <CardTitle class="text-base">Leave heatmap</CardTitle>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Distinct employees on leave per day (range)
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="leaveHeatmapDays.length === 0"
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            —
                        </div>
                        <div v-else class="flex flex-wrap gap-1">
                            <div
                                v-for="d in leaveHeatmapDays"
                                :key="d.date"
                                class="h-3 w-3 rounded-sm"
                                :class="{
                                    'bg-gray-100 dark:bg-neutral-800':
                                        d.level === 0,
                                    'bg-emerald-200 dark:bg-emerald-900':
                                        d.level === 1,
                                    'bg-emerald-400 dark:bg-emerald-700':
                                        d.level === 2,
                                    'bg-emerald-500 dark:bg-emerald-600':
                                        d.level === 3,
                                    'bg-emerald-700 dark:bg-emerald-400':
                                        d.level === 4,
                                }"
                                :title="`${d.date}: ${d.count}`"
                            />
                        </div>
                    </CardContent>
                </Card>

                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader>
                        <CardTitle class="text-base"
                            >Training heatmap</CardTitle
                        >
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Distinct employees in training per day (range)
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="trainingHeatmapDays.length === 0"
                            class="text-sm text-gray-500 dark:text-gray-400"
                        >
                            —
                        </div>
                        <div v-else class="flex flex-wrap gap-1">
                            <div
                                v-for="d in trainingHeatmapDays"
                                :key="d.date"
                                class="h-3 w-3 rounded-sm"
                                :class="{
                                    'bg-gray-100 dark:bg-neutral-800':
                                        d.level === 0,
                                    'bg-indigo-200 dark:bg-indigo-900':
                                        d.level === 1,
                                    'bg-indigo-400 dark:bg-indigo-700':
                                        d.level === 2,
                                    'bg-indigo-500 dark:bg-indigo-600':
                                        d.level === 3,
                                    'bg-indigo-700 dark:bg-indigo-400':
                                        d.level === 4,
                                }"
                                :title="`${d.date}: ${d.count}`"
                            />
                        </div>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <!-- Monthly Hires Bar Chart -->
                <Card
                    v-if="hiresPerMonth"
                    class="border border-gray-200 dark:border-neutral-800"
                >
                    <CardHeader>
                        <CardTitle class="text-base"
                            >Monthly Hires — {{ currentYear }}</CardTitle
                        >
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            New employees hired per month
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-end gap-2" style="height: 180px">
                            <div
                                v-for="m in hiresPerMonth"
                                :key="m.month"
                                class="flex flex-1 flex-col items-center justify-end"
                            >
                                <span
                                    class="mb-1 text-[10px] font-medium text-gray-600 dark:text-gray-300"
                                >
                                    {{ m.count > 0 ? m.count : '' }}
                                </span>
                                <div
                                    class="w-full rounded-t-sm bg-brand transition-all"
                                    :style="{
                                        height:
                                            m.count > 0
                                                ? `${(m.count / maxHires) * 140}px`
                                                : '4px',
                                        opacity: m.count > 0 ? 1 : 0.2,
                                    }"
                                />
                                <span class="mt-2 text-[10px] text-gray-400">{{
                                    m.month
                                }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>

                <!-- Headcount Trend -->
                <Card
                    v-if="headcountTrend"
                    class="border border-gray-200 dark:border-neutral-800"
                >
                    <CardHeader>
                        <CardTitle class="text-base">Headcount Trend</CardTitle>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Employee count at year-end (last 5 years)
                        </p>
                    </CardHeader>
                    <CardContent>
                        <div class="flex items-end gap-4" style="height: 180px">
                            <div
                                v-for="h in headcountTrend"
                                :key="h.year"
                                class="flex flex-1 flex-col items-center justify-end"
                            >
                                <span
                                    class="mb-1 text-xs font-semibold text-gray-700 dark:text-gray-200"
                                >
                                    {{ h.count }}
                                </span>
                                <div
                                    class="w-full rounded-t-sm bg-indigo-500 transition-all dark:bg-indigo-400"
                                    :style="{
                                        height:
                                            h.count > 0
                                                ? `${(h.count / maxHeadcount) * 140}px`
                                                : '4px',
                                    }"
                                />
                                <span class="mt-2 text-xs text-gray-500">{{
                                    h.year
                                }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
