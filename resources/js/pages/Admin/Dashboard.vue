<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Users, BookOpen, FileText, DatabaseBackup } from 'lucide-vue-next';
import { computed } from 'vue';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import hr from '@/routes/hr';
import { useBroadcasting } from '@/composables/useBroadcasting';
import type { BreadcrumbItem } from '@/types';

type Props = {
    totalUsers?: number;
    pendingCount?: number;
    leavePendingCount?: number;
    usersByRole?: Record<string, number>;
    usersByStatus?: Record<string, number>;
    user?: { first_name?: string } | null;
};

const props = defineProps<Props>();

const { usersPendingCount, leavesPendingCount } = useBroadcasting();

if (usersPendingCount.value === null) {
    usersPendingCount.value = props.pendingCount ?? 0;
}
if (leavesPendingCount.value === null) {
    leavesPendingCount.value = props.leavePendingCount ?? 0;
}

const pendingCountComputed = computed(
    () => usersPendingCount.value ?? (props.pendingCount ?? 0),
);

const leavePendingCountComputed = computed(
    () => leavesPendingCount.value ?? (props.leavePendingCount ?? 0),
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: admin.dashboard().url },
];

const quickActions = [
    { title: 'Manage Users', href: admin.users().url, icon: Users },
    { title: 'Activity Logs', href: admin.activityLogs.index().url, icon: BookOpen },
    { title: 'Reports', href: admin.reports().url, icon: FileText },
    { title: 'Backup', href: admin.backup.index().url, icon: DatabaseBackup },
];
</script>

<template>
    <Head title="Admin Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Admin Dashboard
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Welcome, {{ user?.first_name ?? 'User' }}. Organization-wide overview and system metrics.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ totalUsers ?? '—' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Admin, HR &amp; Employee</p>
                    <Link
                        :href="admin.users().url"
                        class="mt-1 block text-xs text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80 cursor-pointer"
                    >
                        Manage Users →
                    </Link>
                </div>

                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending User Approvals</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ pendingCountComputed }}</p>
                    <div class="flex items-center gap-1.5 mt-2">
                        <span
                            v-if="(pendingCountComputed ?? 0) > 0"
                            class="text-sm font-medium text-amber-600 dark:text-amber-400"
                        >
                            Action required
                        </span>
                        <span v-else class="text-sm font-medium text-green-600 dark:text-green-400">
                            All clear
                        </span>
                    </div>
                    <Link
                        :href="admin.users.url(undefined, { query: { status: 'pending' } })"
                        class="mt-1 block text-xs text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80 cursor-pointer"
                    >
                        Review →
                    </Link>
                </div>

                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Pending Leave (org)</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ leavePendingCountComputed }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Managed by HR</p>
                    <Link
                        :href="hr.leaveApplications.index().url"
                        class="mt-1 block text-xs text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80 cursor-pointer"
                    >
                        View Leave →
                    </Link>
                </div>

                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Activity Logs</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">Audit</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">System audit trail</p>
                    <Link
                        :href="admin.activityLogs.index().url"
                        class="mt-1 block text-xs text-brand hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80 cursor-pointer"
                    >
                        View Logs →
                    </Link>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-neutral-700">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Quick Actions</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">Frequent tasks</p>
                    </div>
                    <div class="p-4 grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <Link
                            v-for="action in quickActions"
                            :key="action.title"
                            :href="action.href"
                            class="flex items-center gap-3 p-3 rounded-lg border border-gray-200 dark:border-neutral-700 hover:bg-gray-50 dark:hover:bg-neutral-800/60 transition-colors cursor-pointer"
                        >
                            <div class="w-10 h-10 rounded-md bg-brand/[0.08] flex items-center justify-center shrink-0">
                                <component :is="action.icon" class="w-5 h-5 text-brand" />
                            </div>
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ action.title }}</span>
                        </Link>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 overflow-hidden">
                    <div class="p-4 border-b border-gray-200 dark:border-neutral-700">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Users by Role</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-4">Admin, HR, and Employee distribution</p>
                    </div>
                    <div class="p-4 space-y-2">
                        <template v-if="usersByRole">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Admin: <span class="font-medium">{{ usersByRole['Admin'] ?? 0 }}</span>
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                HR: <span class="font-medium">{{ usersByRole['HR'] ?? 0 }}</span>
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Employee: <span class="font-medium">{{ usersByRole['Employee'] ?? 0 }}</span>
                            </p>
                        </template>
                        <p v-else class="text-sm text-gray-500 dark:text-gray-400">—</p>
                    </div>
                    <div class="p-4 border-t border-gray-200 dark:border-neutral-700">
                        <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">User Status</h2>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5 mb-2">Active, inactive</p>
                        <template v-if="usersByStatus">
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Active: <span class="font-medium">{{ usersByStatus['active'] ?? 0 }}</span>
                            </p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">
                                Inactive: <span class="font-medium">{{ usersByStatus['inactive'] ?? 0 }}</span>
                            </p>
                        </template>
                        <p v-else class="text-sm text-gray-500 dark:text-gray-400">—</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
