<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Calendar, Download, FileText, Users } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type Summary = {
    totalUsers: number;
    totalLeave: number;
    totalTraining: number;
    pendingLeave: number;
};

defineProps<{
    summary: Summary;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports' },
];

const reportLinks = [
    { title: 'Export Activity Logs', href: admin.activityLogs.export.url(), icon: Download, description: 'Download activity log CSV' },
    { title: 'View Users', href: admin.users().url, icon: Users, description: 'User management' },
    { title: 'View Leave', href: hr.leaveApplications.index().url, icon: Calendar, description: 'Leave applications' },
    { title: 'Activity Logs', href: admin.activityLogs.index().url, icon: FileText, description: 'Audit trail' },
];
</script>

<template>
    <Head title="Reports" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Reports &amp; Analytics
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Summary stats and export links.
                </p>
            </div>

            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Users</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ summary?.totalUsers ?? '—' }}</p>
                    <Link :href="admin.users().url" class="mt-1 block text-xs text-brand hover:underline dark:text-brand-light">View users →</Link>
                </div>
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Leave Applications</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ summary?.totalLeave ?? '—' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ summary?.pendingLeave ?? 0 }} pending</p>
                    <Link :href="hr.leaveApplications.index().url" class="mt-1 block text-xs text-brand hover:underline dark:text-brand-light">View leave →</Link>
                </div>
                <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 p-4">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Training Records</p>
                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mt-1">{{ summary?.totalTraining ?? '—' }}</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Org-wide</p>
                </div>
            </div>

            <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 overflow-hidden">
                <div class="border-b border-gray-200 dark:border-neutral-700 px-4 py-3">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">Report links</h2>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Exports and related pages</p>
                </div>
                <div class="grid grid-cols-1 gap-2 p-4 sm:grid-cols-2">
                    <Link
                        v-for="item in reportLinks"
                        :key="item.title"
                        :href="item.href"
                        class="flex items-center gap-3 rounded-lg border border-gray-200 dark:border-neutral-700 p-3 transition-colors hover:bg-gray-50 dark:hover:bg-neutral-800/60"
                    >
                        <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-md bg-primary/10">
                            <component :is="item.icon" class="size-5 text-primary" />
                        </div>
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ item.title }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ item.description }}</p>
                        </div>
                    </Link>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
