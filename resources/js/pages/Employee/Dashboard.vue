<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { FileText, BookOpen, Calendar, ClipboardList } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import employee from '@/routes/employee';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        leaveCount?: number;
        trainingCount?: number;
        pdsStatus?: string | null;
        user?: { first_name?: string } | null;
    }>(),
    { leaveCount: 0, trainingCount: 0, pdsStatus: null, user: null }
);

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard' }
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
                    Welcome back{{ user?.first_name ? `, ${user.first_name}` : '' }}. Here’s an overview of your activity.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <FileText class="size-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Leave Applications</p>
                            <p class="text-2xl font-semibold">{{ leaveCount }}</p>
                        </div>
                    </div>
                    <Button variant="link" class="mt-3 h-auto p-0 text-sm" as-child>
                        <Link :href="employee.leaveApplications.index.url()">View leave</Link>
                    </Button>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <BookOpen class="size-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Training records</p>
                            <p class="text-2xl font-semibold">{{ trainingCount }}</p>
                        </div>
                    </div>
                    <Button variant="link" class="mt-3 h-auto p-0 text-sm" as-child>
                        <Link :href="employee.training.index.url()">View training</Link>
                    </Button>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <ClipboardList class="size-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">PDS status</p>
                            <p class="text-lg font-semibold capitalize">{{ pdsStatus || '—' }}</p>
                        </div>
                    </div>
                    <Button variant="link" class="mt-3 h-auto p-0 text-sm" as-child>
                        <Link :href="employee.pds.index.url()">Open PDS</Link>
                    </Button>
                </div>
                <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-800/50">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 text-primary">
                            <Calendar class="size-5" />
                        </div>
                        <div>
                            <p class="text-sm font-medium text-muted-foreground">Calendar</p>
                            <p class="text-lg font-semibold">View events</p>
                        </div>
                    </div>
                    <Button variant="link" class="mt-3 h-auto p-0 text-sm" as-child>
                        <Link :href="employee.calendar.url()">Open calendar</Link>
                    </Button>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
