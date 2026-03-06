<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import {
    ArrowRight,
    BarChart3,
    BookOpen,
    DatabaseBackup,
    FileText,
    TrendingUp,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
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
import admin from '@/routes/admin';
import type { BreadcrumbItem } from '@/types';

type SexRow = { label: string; value: number };
type Recommendation = { title: string; detail: string; level: string };

type Props = {
    totalUsers?: number;
    pendingCount?: number;
    leavePendingCount?: number;
    usersByRole?: Record<string, number>;
    usersByStatus?: Record<string, number>;
    user?: { first_name?: string } | null;
    totalEmployees?: number;
    sexDistribution?: SexRow[];
    unassigned?: {
        division?: number;
        subdivision?: number;
        section?: number;
    };
    recommendations?: Recommendation[];
};

const props = defineProps<Props>();

const maxSex = computed(() =>
    Math.max(...(props.sexDistribution?.map((s) => s.value) ?? [1]), 1),
);
const unassignedTotal = computed(
    () =>
        (props.unassigned?.division ?? 0) +
        (props.unassigned?.subdivision ?? 0) +
        (props.unassigned?.section ?? 0),
);

const { usersPendingCount, leavesPendingCount } = useBroadcasting();

if (usersPendingCount.value === null) {
    usersPendingCount.value = props.pendingCount ?? 0;
}
if (leavesPendingCount.value === null) {
    leavesPendingCount.value = props.leavePendingCount ?? 0;
}

const pendingCountComputed = computed(
    () => usersPendingCount.value ?? props.pendingCount ?? 0,
);

const leavePendingCountComputed = computed(
    () => leavesPendingCount.value ?? props.leavePendingCount ?? 0,
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: admin.dashboard().url },
];

const quickActions = [
    {
        title: 'Manage Users',
        href: admin.users().url,
        icon: Users,
        description: 'View and manage system users',
    },
    {
        title: 'Activity Logs',
        href: admin.activityLogs.index().url,
        icon: BookOpen,
        description: 'System audit trail',
    },
    {
        title: 'Reports',
        href: admin.reports().url,
        icon: FileText,
        description: 'Analytics and exports',
    },
    {
        title: 'Backup',
        href: admin.backup.index().url,
        icon: DatabaseBackup,
        description: 'Data backup management',
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
                    organization overview.
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
                            Total Users
                        </CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ totalUsers ?? '—' }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            System accounts
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
                            Pending Approvals
                        </CardTitle>
                        <TrendingUp class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ pendingCountComputed }}
                        </div>
                        <p class="text-xs text-muted-foreground">
                            {{
                                (pendingCountComputed ?? 0) > 0
                                    ? 'Action required'
                                    : 'All clear'
                            }}
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
                            Pending Leave
                        </CardTitle>
                        <FileText class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">
                            {{ leavePendingCountComputed }}
                        </div>
                        <p class="text-xs text-muted-foreground">HR managed</p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader
                        class="flex flex-row items-center justify-between pb-2"
                    >
                        <CardTitle
                            class="text-sm font-medium text-muted-foreground"
                        >
                            Total Employees
                        </CardTitle>
                        <BarChart3 class="h-4 w-4 text-muted-foreground" />
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
            </div>

            <!-- Secondary Grid -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-7">
                <!-- Quick Actions -->
                <Card class="col-span-4">
                    <CardHeader>
                        <CardTitle>Quick Actions</CardTitle>
                        <CardDescription
                            >Frequent administrative tasks</CardDescription
                        >
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

                <!-- User Distribution -->
                <Card class="col-span-3">
                    <CardHeader>
                        <CardTitle>User Distribution</CardTitle>
                        <CardDescription>By role and status</CardDescription>
                    </CardHeader>
                    <CardContent class="space-y-6">
                        <div class="space-y-2">
                            <p
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                By Role
                            </p>
                            <div v-if="usersByRole" class="space-y-1">
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-muted-foreground"
                                        >Admin</span
                                    >
                                    <span class="font-medium">{{
                                        usersByRole['Admin'] ?? 0
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-muted-foreground"
                                        >HR</span
                                    >
                                    <span class="font-medium">{{
                                        usersByRole['HR'] ?? 0
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-muted-foreground"
                                        >Employee</span
                                    >
                                    <span class="font-medium">{{
                                        usersByRole['Employee'] ?? 0
                                    }}</span>
                                </div>
                            </div>
                            <p v-else class="text-sm text-muted-foreground">
                                —
                            </p>
                        </div>

                        <div class="space-y-2">
                            <p
                                class="text-xs font-medium tracking-wider text-muted-foreground uppercase"
                            >
                                By Status
                            </p>
                            <div v-if="usersByStatus" class="space-y-1">
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-muted-foreground"
                                        >Active</span
                                    >
                                    <span class="font-medium">{{
                                        usersByStatus['active'] ?? 0
                                    }}</span>
                                </div>
                                <div
                                    class="flex items-center justify-between text-sm"
                                >
                                    <span class="text-muted-foreground"
                                        >Inactive</span
                                    >
                                    <span class="font-medium">{{
                                        usersByStatus['inactive'] ?? 0
                                    }}</span>
                                </div>
                            </div>
                            <p v-else class="text-sm text-muted-foreground">
                                —
                            </p>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- Analytics Section -->
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
                    <CardHeader
                        class="flex flex-row items-center justify-between"
                    >
                        <div>
                            <CardTitle>Recommendations</CardTitle>
                            <CardDescription
                                >Data quality signals</CardDescription
                            >
                        </div>
                        <Link :href="admin.reports().url">
                            <Button variant="ghost" size="sm">
                                View Analytics
                                <ArrowRight class="ml-2 h-4 w-4" />
                            </Button>
                        </Link>
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

            <!-- Unassigned Alert -->
            <Card
                v-if="unassignedTotal > 0"
                class="border-destructive/50 bg-destructive/5"
            >
                <CardHeader class="flex flex-row items-center gap-4">
                    <div
                        class="flex size-9 items-center justify-center rounded-full bg-destructive/10"
                    >
                        <TrendingUp class="size-5 text-destructive" />
                    </div>
                    <div class="flex-1">
                        <CardTitle class="text-base"
                            >Unassigned Employees</CardTitle
                        >
                        <CardDescription>
                            {{ unassignedTotal }} employees missing
                            organizational assignments
                        </CardDescription>
                    </div>
                    <Link :href="admin.users().url">
                        <Button variant="outline" size="sm">Review</Button>
                    </Link>
                </CardHeader>
            </Card>
        </div>
    </AppLayout>
</template>
