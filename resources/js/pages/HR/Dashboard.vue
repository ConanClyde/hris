<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    Calendar,
    FileText,
    GraduationCap,
    TrendingUp,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from '@/components/ui/card';
import { useBroadcasting } from '@/composables/useBroadcasting';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type SexRow = { label: string; value: number };
type TrendPoint = { month: string; count: number };
type Recommendation = { title: string; detail: string; level: string };

const pageProps = defineProps<{
    totalUsers: number;
    pendingLeaveCount: number;
    pendingTrainingCount: number;
    pdsPendingCount: number;
    outToday?: Array<{
        id: number;
        employee_name: string;
        department: string;
        leave_type: string;
        avatar: string | null;
    }>;
    user?: { first_name: string } | null;
    totalEmployees?: number;
    sexDistribution?: SexRow[];
    unassigned?: {
        division?: number;
        subdivision?: number;
        section?: number;
    };
    miniLeaveTrend?: TrendPoint[];
    miniTrainingTrend?: TrendPoint[];
    recommendations?: Recommendation[];
}>();

const {
    leavesPendingCount,
    trainingsAssignedCount,
    pdsPendingCount: pdsPendingCountRealtime,
} = useBroadcasting();

if (leavesPendingCount.value === null) {
    leavesPendingCount.value = pageProps.pendingLeaveCount ?? 0;
}
if (trainingsAssignedCount.value === null) {
    trainingsAssignedCount.value = pageProps.pendingTrainingCount ?? 0;
}
if (pdsPendingCountRealtime.value === null) {
    pdsPendingCountRealtime.value = pageProps.pdsPendingCount ?? 0;
}

const pendingLeaveCountComputed = computed(
    () => leavesPendingCount.value ?? pageProps.pendingLeaveCount,
);

const pendingTrainingCountComputed = computed(
    () => trainingsAssignedCount.value ?? pageProps.pendingTrainingCount,
);

const pdsPendingCountComputed = computed(
    () => pdsPendingCountRealtime.value ?? pageProps.pdsPendingCount,
);

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Dashboard' }];

const maxSex = computed(() =>
    Math.max(...(pageProps.sexDistribution?.map((s) => s.value) ?? [1]), 1),
);
const maxLeaveTrend = computed(() =>
    Math.max(...(pageProps.miniLeaveTrend?.map((m) => m.count) ?? [1]), 1),
);
const maxTrainingTrend = computed(() =>
    Math.max(...(pageProps.miniTrainingTrend?.map((m) => m.count) ?? [1]), 1),
);
const unassignedTotal = computed(
    () =>
        (pageProps.unassigned?.division ?? 0) +
        (pageProps.unassigned?.subdivision ?? 0) +
        (pageProps.unassigned?.section ?? 0),
);

const quickActions = [
    {
        title: 'Leave Applications',
        href: hr.leaveApplications.index().url,
        icon: Calendar,
        description: 'Review and approve leave',
    },
    {
        title: 'Learning & Development',
        href: hr.training.index().url,
        icon: GraduationCap,
        description: 'Training management',
    },
    {
        title: 'PDS Management',
        href: hr.pds.index().url,
        icon: FileText,
        description: 'Personal data sheets',
    },
    {
        title: 'Reports & Analytics',
        href: hr.reports().url,
        icon: TrendingUp,
        description: 'Workforce insights',
    },
];

const getLevelVariant = (level: string) => {
    switch (level) {
        case 'warning':
            return 'destructive';
        case 'success':
            return 'default';
        default:
            return 'secondary';
    }
};

const pendingItems = [
    {
        type: 'Leave' as const,
        name: 'Pending leave requests',
        href: hr.leaveApplications.index().url,
    },
    {
        type: 'Training' as const,
        name: 'Training approvals',
        href: hr.training.index().url,
    },
    { type: 'PDS' as const, name: 'PDS submissions', href: hr.pds.index().url },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex flex-col gap-6 p-4">
            <!-- Header -->
            <div class="flex flex-col gap-2">
                <h1 class="text-3xl font-bold tracking-tight">Dashboard</h1>
                <p class="text-muted-foreground">
                    Welcome back, {{ user?.first_name ?? 'User' }}. Here's your
                    HR overview.
                </p>
            </div>

            <!-- Primary Metrics -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                        >
                            Pending Leave
                        </CardTitle>
                        <Calendar class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ pendingLeaveCountComputed }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            Awaiting review
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                        >
                            Training Requests
                        </CardTitle>
                        <GraduationCap class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ pendingTrainingCountComputed }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            Pending approval
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                        >
                            PDS Reviews
                        </CardTitle>
                        <FileText class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ pdsPendingCountComputed }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            Submitted for review
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                        >
                            Total Users
                        </CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ totalUsers }}</div>
                        <p class="text-xs text-muted-foreground">
                            Active records
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Secondary Grid -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <!-- Quick Actions -->
                <Card class="col-span-4">
                    <CardHeader>
                        <CardTitle>Quick Actions</CardTitle>
                        <CardDescription>Frequent HR tasks</CardDescription>
                    </CardHeader>
                    <CardContent class="grid gap-4 sm:grid-cols-2">
                        <Link
                            v-for="action in quickActions"
                            :key="action.title"
                            :href="action.href"
                            class="flex items-start gap-4 rounded-lg border p-4 transition-colors hover:bg-muted/50"
                        >
                            <div
                                class="flex size-9 items-center justify-center rounded-lg bg-primary/10"
                            >
                                <component
                                    :is="action.icon"
                                    class="size-5 text-primary"
                                />
                            </div>
                            <div class="flex-1">
                                <p class="text-sm font-medium">
                                    {{ action.title }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    {{ action.description }}
                                </p>
                            </div>
                            <ArrowRight class="size-4 text-muted-foreground" />
                        </Link>
                    </CardContent>
                </Card>

                <!-- Pending Items -->
                <Card class="col-span-3">
                    <CardHeader
                        class="flex flex-row items-center justify-between"
                    >
                        <div>
                            <CardTitle>Pending Items</CardTitle>
                            <CardDescription
                                >Awaiting your action</CardDescription
                            >
                        </div>
                        <Link :href="hr.leaveApplications.index().url">
                            <Button variant="ghost" size="sm">View all</Button>
                        </Link>
                    </CardHeader>
                    <CardContent class="space-y-2">
                        <Link
                            v-for="item in pendingItems"
                            :key="item.type"
                            :href="item.href"
                            class="flex items-center justify-between rounded-lg border p-3 transition-colors hover:bg-muted/50"
                        >
                            <div class="flex items-center gap-3">
                                <div
                                    class="flex size-9 items-center justify-center rounded-md"
                                    :class="{
                                        'bg-amber-500/10':
                                            item.type === 'Leave',
                                        'bg-primary/10':
                                            item.type === 'Training',
                                        'bg-green-500/10': item.type === 'PDS',
                                    }"
                                >
                                    <Calendar
                                        v-if="item.type === 'Leave'"
                                        class="size-4 text-amber-600"
                                    />
                                    <GraduationCap
                                        v-else-if="item.type === 'Training'"
                                        class="size-4 text-primary"
                                    />
                                    <FileText
                                        v-else
                                        class="size-4 text-green-600"
                                    />
                                </div>
                                <span class="text-sm font-medium">{{
                                    item.name
                                }}</span>
                            </div>
                            <Badge variant="secondary">Pending</Badge>
                        </Link>
                    </CardContent>
                </Card>
            </div>

            <!-- Who's Out Today -->
            <Card>
                <CardHeader>
                    <CardTitle>Who's Out Today</CardTitle>
                    <CardDescription
                        >Employees currently on approved leave</CardDescription
                    >
                </CardHeader>
                <CardContent>
                    <div
                        v-if="outToday && outToday.length > 0"
                        class="divide-y"
                    >
                        <div
                            v-for="person in outToday"
                            :key="person.id"
                            class="flex items-center gap-4 py-3 first:pt-0 last:pb-0"
                        >
                            <Avatar class="h-10 w-10">
                                <AvatarImage
                                    v-if="person.avatar"
                                    :src="`/storage/${person.avatar}`"
                                />
                                <AvatarFallback>{{
                                    person.employee_name
                                        .substring(0, 2)
                                        .toUpperCase()
                                }}</AvatarFallback>
                            </Avatar>
                            <div class="flex-1">
                                <p class="text-sm font-medium">
                                    {{ person.employee_name }}
                                </p>
                                <p class="text-xs text-muted-foreground">
                                    {{ person.department }} ·
                                    {{ person.leave_type }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex flex-col items-center justify-center py-8 text-center"
                    >
                        <div
                            class="flex size-12 items-center justify-center rounded-full bg-green-500/10"
                        >
                            <Calendar class="size-6 text-green-600" />
                        </div>
                        <h3 class="mt-4 text-sm font-medium">
                            Everyone is in the office today!
                        </h3>
                        <p class="text-xs text-muted-foreground">
                            Fully staffed and ready to go.
                        </p>
                    </div>
                </CardContent>
            </Card>

            <!-- Workforce Analytics -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                        >
                            Total Employees
                        </CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ totalEmployees ?? '—' }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            Active workforce
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                        >
                            Unassigned
                        </CardTitle>
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ unassignedTotal }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            {{
                                unassignedTotal > 0
                                    ? 'Action needed'
                                    : 'All assigned'
                            }}
                        </p>
                    </CardContent>
                </Card>

                <Card class="flex flex-col">
                    <CardHeader class="flex-1">
                        <CardTitle>Analytics</CardTitle>
                        <CardDescription>Workforce insights</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <Link :href="hr.reports().url">
                            <Button variant="outline" class="w-full">
                                View Full Analytics
                                <ArrowRight class="ml-2 h-4 w-4" />
                            </Button>
                        </Link>
                    </CardContent>
                </Card>
            </div>

            <!-- Trends & Distribution -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <!-- Sex Distribution -->
                <Card class="col-span-3">
                    <CardHeader>
                        <CardTitle>Sex Distribution</CardTitle>
                        <CardDescription
                            >Based on PDS personal data</CardDescription
                        >
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="sexDistribution && sexDistribution.length > 0"
                            class="space-y-4"
                        >
                            <div
                                v-for="s in sexDistribution"
                                :key="s.label"
                                class="space-y-2"
                            >
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="font-medium">{{
                                        s.label
                                    }}</span>
                                    <span class="text-muted-foreground">{{
                                        s.value
                                    }}</span>
                                </div>
                                <div
                                    class="h-2 w-full overflow-hidden rounded-full bg-secondary"
                                >
                                    <div
                                        class="h-full rounded-full bg-primary transition-all"
                                        :style="{
                                            width: `${(s.value / maxSex) * 100}%`,
                                        }"
                                    />
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted-foreground">—</p>
                    </CardContent>
                </Card>

                <!-- Recommendations -->
                <Card class="col-span-4">
                    <CardHeader>
                        <CardTitle>Recommendations</CardTitle>
                        <CardDescription>Data quality signals</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="recommendations && recommendations.length > 0"
                            class="space-y-4"
                        >
                            <div
                                v-for="(rec, idx) in recommendations"
                                :key="idx"
                                class="flex items-start gap-4 rounded-lg border p-4"
                            >
                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-medium">
                                            {{ rec.title }}
                                        </p>
                                        <Badge
                                            :variant="
                                                getLevelVariant(rec.level)
                                            "
                                            >{{ rec.level }}</Badge
                                        >
                                    </div>
                                    <p class="text-xs text-muted-foreground">
                                        {{ rec.detail }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted-foreground">—</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Mini Trends -->
            <div class="grid gap-4 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Leave Trend</CardTitle>
                        <CardDescription>Last 6 months</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="miniLeaveTrend && miniLeaveTrend.length > 0"
                            class="flex items-end gap-3"
                            style="height: 120px"
                        >
                            <div
                                v-for="m in miniLeaveTrend"
                                :key="m.month"
                                class="flex flex-1 flex-col items-center justify-end gap-2"
                            >
                                <span
                                    v-if="m.count > 0"
                                    class="text-xs font-medium text-muted-foreground"
                                    >{{ m.count }}</span
                                >
                                <div
                                    class="w-full rounded-t-md bg-amber-500 transition-all"
                                    :style="{
                                        height:
                                            m.count > 0
                                                ? `${Math.max((m.count / maxLeaveTrend) * 80, 4)}px`
                                                : '4px',
                                        opacity: m.count > 0 ? 1 : 0.3,
                                    }"
                                />
                                <span class="text-xs text-muted-foreground">{{
                                    m.month
                                }}</span>
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted-foreground">—</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Training Trend</CardTitle>
                        <CardDescription>Last 6 months</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div
                            v-if="
                                miniTrainingTrend &&
                                miniTrainingTrend.length > 0
                            "
                            class="flex items-end gap-3"
                            style="height: 120px"
                        >
                            <div
                                v-for="m in miniTrainingTrend"
                                :key="m.month"
                                class="flex flex-1 flex-col items-center justify-end gap-2"
                            >
                                <span
                                    v-if="m.count > 0"
                                    class="text-xs font-medium text-muted-foreground"
                                    >{{ m.count }}</span
                                >
                                <div
                                    class="w-full rounded-t-md bg-sky-500 transition-all"
                                    :style="{
                                        height:
                                            m.count > 0
                                                ? `${Math.max((m.count / maxTrainingTrend) * 80, 4)}px`
                                                : '4px',
                                        opacity: m.count > 0 ? 1 : 0.3,
                                    }"
                                />
                                <span class="text-xs text-muted-foreground">{{
                                    m.month
                                }}</span>
                            </div>
                        </div>
                        <p v-else class="text-sm text-muted-foreground">—</p>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
