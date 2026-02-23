<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import admin from '@/routes/admin';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: admin.dashboard().url },
    { title: 'Leave' },
];
</script>

<template>
    <Head title="Leave" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4">
            <div>
                <h1 class="text-xl font-semibold text-gray-900 dark:text-gray-100 tracking-tight">Leave</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Leave overview – managed by HR.</p>
            </div>

            <div class="rounded-md border border-gray-200 dark:border-neutral-800 bg-white dark:bg-neutral-900 p-6 shadow-sm">
                <p class="text-sm text-gray-600 dark:text-gray-400">Leave applications and credits are managed by the HR team.</p>
                <Link :href="hr.leaveApplications.index().url">
                    <Button variant="outline" class="mt-4">View Leave →</Button>
                </Link>
            </div>
        </div>
    </AppLayout>
</template>
