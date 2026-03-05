<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { BarChart3, BookOpen, Calendar, FileText } from 'lucide-vue-next';
import { computed } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { useBroadcasting } from '@/composables/useBroadcasting';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

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
}>();

const { leavesPendingCount, trainingsAssignedCount, pdsPendingCount: pdsPendingCountRealtime } = useBroadcasting();

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

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard' }
];

const quickActions = [
    { title: 'Leave Applications', href: hr.leaveApplications.index().url, icon: Calendar },
    { title: 'Learning & Development', href: hr.training.index().url, icon: BookOpen },
    { title: 'PDS Management', href: hr.pds.index().url, icon: FileText },
    { title: 'Reports & Analytics', href: hr.reports().url, icon: BarChart3 },
];

const pendingItems = [
    { type: 'Leave' as const, name: 'Pending leave requests', href: hr.leaveApplications.index().url },
    { type: 'Training' as const, name: 'Training approvals', href: hr.training.index().url },
    { type: 'PDS' as const, name: 'PDS submissions', href: hr.pds.index().url },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Dashboard
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Welcome, {{ user?.first_name ?? 'User' }}. Overview of HR operations and pending actions.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Pending Leave Applications
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ pendingLeaveCountComputed }}
                        </p>
                        <p class="mt-2 text-sm font-medium text-amber-600 dark:text-amber-400">
                            Review required
                        </p>
                        <Link
                            :href="hr.leaveApplications.index().url"
                            class="mt-2 block text-xs text-gray-500 dark:text-gray-400 cursor-pointer transition-colors text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80"
                        >
                            View all →
                        </Link>
                    </CardContent>
                </Card>
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Training Pending Approval
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ pendingTrainingCountComputed }}
                        </p>
                        <p class="mt-2 text-sm font-medium text-amber-600 dark:text-amber-400">
                            Action required
                        </p>
                        <Link
                            :href="hr.training.index().url"
                            class="mt-2 block text-xs text-gray-500 dark:text-gray-400 cursor-pointer transition-colors text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80"
                        >
                            View all →
                        </Link>
                    </CardContent>
                </Card>
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            PDS Awaiting Review
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ pdsPendingCountComputed }}
                        </p>
                        <p class="mt-2 text-sm font-medium text-green-600 dark:text-green-400">
                            On track
                        </p>
                        <Link
                            :href="hr.pds.index().url"
                            class="mt-2 block text-xs text-gray-500 dark:text-gray-400 cursor-pointer transition-colors text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80"
                        >
                            PDS Management →
                        </Link>
                    </CardContent>
                </Card>
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="pb-2">
                        <CardTitle class="text-sm font-normal text-gray-500 dark:text-gray-400">
                            Total Users
                        </CardTitle>
                    </CardHeader>
                    <CardContent>
                        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                            {{ totalUsers }}
                        </p>
                        <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400">
                            Active records
                        </p>
                        <Link
                            :href="hr.users.index.url()"
                            class="mt-2 block text-xs text-gray-500 dark:text-gray-400 cursor-pointer transition-colors text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80"
                        >
                            View list →
                        </Link>
                    </CardContent>
                </Card>
            </div>

            <div class="grid grid-cols-1 gap-4 lg:grid-cols-2">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="border-b border-gray-200 dark:border-neutral-800">
                        <CardTitle class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            Quick Actions
                        </CardTitle>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Frequent HR tasks
                        </p>
                    </CardHeader>
                    <CardContent class="grid grid-cols-1 gap-3 p-4 sm:grid-cols-2">
                        <Link
                            v-for="action in quickActions"
                            :key="action.title"
                            :href="action.href"
                            class="flex cursor-pointer items-center gap-3 rounded-md border border-gray-200 p-3 transition-colors hover:bg-gray-50 dark:border-neutral-800 dark:hover:bg-neutral-800/60"
                        >
                            <div class="flex size-10 shrink-0 items-center justify-center rounded-md bg-brand/[0.08]">
                                <component :is="action.icon" class="size-5 text-brand" />
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ action.title }}
                            </span>
                        </Link>
                    </CardContent>
                </Card>
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="flex flex-row items-center justify-between border-b border-gray-200 dark:border-neutral-800">
                        <div>
                            <CardTitle class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Pending Items
                            </CardTitle>
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                Awaiting your action
                            </p>
                        </div>
                        <Link
                            :href="hr.leaveApplications.index().url"
                            class="text-sm font-medium text-brand cursor-pointer transition-colors hover:text-brand-dark dark:text-brand-light"
                        >
                            View all
                        </Link>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div class="divide-y divide-gray-200 dark:divide-neutral-800">
                            <Link
                                v-for="item in pendingItems"
                                :key="item.type"
                                :href="item.href"
                                class="flex cursor-pointer items-center gap-3 px-4 py-3 transition-colors hover:bg-gray-50 dark:hover:bg-neutral-800/60"
                            >
                                <div
                                    class="flex size-9 shrink-0 items-center justify-center rounded-md"
                                    :class="{
                                        'bg-amber-50 dark:bg-amber-900/30': item.type === 'Leave',
                                        'bg-brand/[0.08]': item.type === 'Training',
                                        'bg-green-50 dark:bg-green-900/30': item.type === 'PDS',
                                    }"
                                >
                                    <Calendar
                                        v-if="item.type === 'Leave'"
                                        class="size-4 text-amber-600 dark:text-amber-400"
                                    />
                                    <BookOpen
                                        v-else-if="item.type === 'Training'"
                                        class="size-4 text-brand"
                                    />
                                    <FileText
                                        v-else
                                        class="size-4 text-green-600 dark:text-green-400"
                                    />
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ item.name }}
                                    </p>
                                </div>
                                <span class="inline-flex rounded-sm bg-amber-50 px-2 py-0.5 text-xs font-medium text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                    Pending
                                </span>
                            </Link>
                        </div>
                    </CardContent>
                </Card>
            </div>

            <!-- "Who's Out Today" Widget -->
            <div class="mt-6">
                <Card class="border border-gray-200 dark:border-neutral-800">
                    <CardHeader class="border-b border-gray-200 dark:border-neutral-800">
                        <CardTitle class="text-base font-semibold text-gray-900 dark:text-gray-100">
                            Who's Out Today
                        </CardTitle>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            Employees currently on approved leave
                        </p>
                    </CardHeader>
                    <CardContent class="p-0">
                        <div v-if="outToday && outToday.length > 0" class="divide-y divide-gray-200 dark:divide-neutral-800">
                            <div
                                v-for="person in outToday"
                                :key="person.id"
                                class="flex items-center gap-3 px-4 py-3"
                            >
                                <div class="flex size-10 items-center justify-center overflow-hidden rounded-full bg-primary/10 text-primary">
                                    <img v-if="person.avatar" :src="`/storage/${person.avatar}`" alt="Avatar" class="h-full w-full object-cover">
                                    <span v-else class="text-xs font-semibold uppercase">{{ person.employee_name.substring(0, 2) }}</span>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ person.employee_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ person.department }} &bull; {{ person.leave_type }}</p>
                                </div>
                            </div>
                        </div>
                        <div v-else class="flex flex-col items-center justify-center p-8 text-center">
                            <div class="flex size-12 items-center justify-center rounded-full bg-green-50 text-green-600 dark:bg-green-900/30 dark:text-green-400">
                                <Calendar class="size-6" />
                            </div>
                            <h3 class="mt-4 text-sm font-medium text-gray-900 dark:text-gray-100">Everyone is in the office today!</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Fully staffed and ready to go.</p>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
