<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Calendar, Download, FileText, Users } from 'lucide-vue-next';
import { computed, ref } from 'vue';
import MetricCard from '@/components/dashboard/MetricCard.vue';
import SectionCard from '@/components/dashboard/SectionCard.vue';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type Summary = {
    totalUsers: number;
    totalEmployees?: number;
};

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
    summary: Summary;
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

const maxHires = computed(() =>
    Math.max(
        ...((props.hiresPerMonth as MonthData[] | undefined)?.map(
            (m) => m.count,
        ) ?? [1]),
        1,
    ),
);
const maxHeadcount = computed(() =>
    Math.max(
        ...((props.headcountTrend as HeadcountData[] | undefined)?.map(
            (h) => h.count,
        ) ?? [1]),
        1,
    ),
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

const fromInput = ref<string>(
    (props.filters?.from as string | undefined) ?? '',
);
const toInput = ref<string>((props.filters?.to as string | undefined) ?? '');

function applyFilters() {
    router.get(
        admin.reports().url,
        {
            from: fromInput.value || undefined,
            to: toInput.value || undefined,
        },
        { preserveState: true, replace: true },
    );
}

const exportAnalyticsUrl = computed(() =>
    admin.reports.export.analytics.url({
        query: {
            from: fromInput.value || undefined,
            to: toInput.value || undefined,
        },
    }),
);

const reportLinks = [
    {
        title: 'Export Activity Logs',
        href: admin.activityLogs.export.url(),
        icon: Download,
        description: 'Download activity log CSV',
    },
    {
        title: 'View Users',
        href: admin.users().url,
        icon: Users,
        description: 'User management',
    },
    {
        title: 'View Leave',
        href: hr.leaveApplications.index().url,
        icon: Calendar,
        description: 'Leave applications',
    },
    {
        title: 'Activity Logs',
        href: admin.activityLogs.index().url,
        icon: FileText,
        description: 'Audit trail',
    },
];
</script>

<template>
    <Head title="Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-4">
            <div>
                <h1
                    class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                >
                    Reports &amp; Analytics
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Summary stats and export links.
                </p>
            </div>

            <SectionCard
                title="Filters"
                subtitle="Optional date range for analytics"
                content-class="p-4"
            >
                <div
                    class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between"
                >
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
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
                    <div class="flex items-center gap-2">
                        <button
                            type="button"
                            class="inline-flex h-10 items-center rounded-md border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                            @click="applyFilters"
                        >
                            Apply
                        </button>
                        <a
                            :href="exportAnalyticsUrl"
                            class="inline-flex h-10 items-center rounded-md border border-gray-200 bg-white px-4 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:text-gray-200 dark:hover:bg-neutral-700"
                            target="_blank"
                            rel="noopener"
                        >
                            Export analytics CSV
                        </a>
                    </div>
                </div>
            </SectionCard>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <MetricCard
                    title="Total Users"
                    :value="summary?.totalUsers ?? '—'"
                    :href="admin.users().url"
                    link-text="View users →"
                    :icon="Users"
                />
                <MetricCard
                    title="Employees"
                    :value="summary?.totalEmployees ?? 0"
                    helper-text="From employees table"
                    :icon="Users"
                />
                <MetricCard
                    title="New Hires (range)"
                    :value="totalHires"
                    helper-text="Based on date_hired"
                    :icon="FileText"
                />
                <MetricCard
                    title="Unassigned (org)"
                    :value="
                        (unassigned?.division ?? 0) +
                        (unassigned?.subdivision ?? 0) +
                        (unassigned?.section ?? 0)
                    "
                    helper-text="Missing division/subdivision/section"
                    :icon="Calendar"
                />
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <SectionCard
                    title="Sex distribution"
                    subtitle="Based on PDS personal data"
                    content-class="p-4"
                >
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
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">
                        —
                    </p>
                </SectionCard>

                <SectionCard
                    title="Org breakdown"
                    subtitle="Counts per division / subdivision / section"
                    content-class="p-4"
                >
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
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">
                        —
                    </p>
                </SectionCard>
            </div>

            <SectionCard
                title="Recommendations"
                subtitle="Data quality and workforce signals"
                content-class="p-4"
            >
                <div
                    v-if="recommendations && recommendations.length > 0"
                    class="space-y-3"
                >
                    <div
                        v-for="(rec, idx) in recommendations"
                        :key="idx"
                        class="rounded-md border border-gray-200 p-3 dark:border-neutral-800"
                    >
                        <div class="flex items-center justify-between gap-3">
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
                <p v-else class="text-sm text-gray-500 dark:text-gray-400">—</p>
            </SectionCard>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <SectionCard
                    title="Leave trend (distinct employees)"
                    subtitle="Per month"
                    content-class="p-4"
                >
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
                                >{{ m.count > 0 ? m.count : '' }}</span
                            >
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
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">
                        —
                    </p>
                </SectionCard>

                <SectionCard
                    title="Training trend (distinct employees)"
                    subtitle="Per month"
                    content-class="p-4"
                >
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
                                >{{ m.count > 0 ? m.count : '' }}</span
                            >
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
                    <p v-else class="text-sm text-gray-500 dark:text-gray-400">
                        —
                    </p>
                </SectionCard>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <SectionCard
                    title="Leave heatmap"
                    subtitle="Distinct employees on leave per day"
                    content-class="p-4"
                >
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
                </SectionCard>

                <SectionCard
                    title="Training heatmap"
                    subtitle="Distinct employees in training per day"
                    content-class="p-4"
                >
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
                </SectionCard>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <SectionCard
                    v-if="hiresPerMonth"
                    title="Monthly Hires"
                    :subtitle="`New employees hired per month${currentYear ? ` — ${currentYear}` : ''}`"
                    content-class="p-4"
                >
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
                </SectionCard>

                <SectionCard
                    v-if="headcountTrend"
                    title="Headcount Trend"
                    subtitle="Employee count at year-end (last 5 years)"
                    content-class="p-4"
                >
                    <div class="flex items-end gap-4" style="height: 180px">
                        <div
                            v-for="h in headcountTrend"
                            :key="h.year"
                            class="flex flex-1 flex-col items-center justify-end"
                        >
                            <span
                                class="mb-1 text-xs font-semibold text-gray-700 dark:text-gray-200"
                                >{{ h.count }}</span
                            >
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
                </SectionCard>
            </div>

            <SectionCard
                title="Report links"
                subtitle="Exports and related pages"
                content-class="p-4"
            >
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                    <Link
                        v-for="item in reportLinks"
                        :key="item.title"
                        :href="item.href"
                        class="flex items-center gap-3 rounded-lg border border-gray-200 p-3 transition-colors hover:bg-gray-50 dark:border-neutral-800 dark:hover:bg-neutral-800/60"
                    >
                        <div
                            class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary/10"
                        >
                            <component
                                :is="item.icon"
                                class="size-5 text-primary"
                            />
                        </div>
                        <div class="min-w-0">
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-gray-100"
                            >
                                {{ item.title }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                {{ item.description }}
                            </p>
                        </div>
                    </Link>
                </div>
            </SectionCard>
        </div>
    </AppLayout>
</template>
