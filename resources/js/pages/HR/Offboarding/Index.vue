<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type ClearanceItem = {
    id: number;
    department: string;
    title: string;
    status: 'pending' | 'cleared' | 'flagged';
    remarks: string | null;
    cleared_at: string | null;
};

type EmployeeOffboarding = {
    id: number;
    name: string;
    total: number;
    cleared: number;
    progress: number;
    clearances: ClearanceItem[];
};

const props = defineProps<{
    employees: EmployeeOffboarding[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Offboarding' },
];

const expandedId = ref<number | null>(null);

function toggleExpand(id: number) {
    expandedId.value = expandedId.value === id ? null : id;
}

function updateClearance(clearanceId: number, status: string) {
    router.post(`/hr/offboarding/clearance/${clearanceId}`, { status, remarks: '' });
}

const statusClasses: Record<string, string> = {
    pending: 'bg-amber-50 text-amber-700 ring-amber-600/20 dark:bg-amber-900/30 dark:text-amber-400',
    cleared: 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-400',
    flagged: 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/30 dark:text-red-400',
};
</script>

<template>
    <Head title="Offboarding" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-5xl space-y-6 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Offboarding &amp; Clearance
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Manage employee clearance processes during resignations and separations.
                </p>
            </div>

            <div v-if="employees.length === 0" class="flex flex-col items-center py-12 text-center">
                <p class="text-sm text-gray-500 dark:text-gray-400">No active offboarding processes.</p>
                <p class="mt-1 text-xs text-gray-400">Initialize clearance from an employee's profile when needed.</p>
            </div>

            <div v-else class="space-y-3">
                <div
                    v-for="emp in employees"
                    :key="emp.id"
                    class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
                >
                    <div
                        class="flex cursor-pointer items-center justify-between p-4 transition-colors hover:bg-gray-50 dark:hover:bg-neutral-800/60"
                        @click="toggleExpand(emp.id)"
                    >
                        <div class="flex items-center gap-4">
                            <div class="flex size-10 items-center justify-center rounded-full bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-400">
                                <span class="text-xs font-semibold uppercase">{{ emp.name.substring(0, 2) }}</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ emp.name }}</p>
                                <p class="text-xs text-gray-500">{{ emp.cleared }}/{{ emp.total }} departments cleared</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-24 overflow-hidden rounded-full bg-gray-200 dark:bg-neutral-700">
                                <div
                                    class="h-full rounded-full transition-all"
                                    :class="emp.progress === 100 ? 'bg-emerald-500' : 'bg-amber-500'"
                                    :style="{ width: `${emp.progress}%` }"
                                />
                            </div>
                            <span
                                class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-semibold ring-1 ring-inset"
                                :class="emp.progress === 100 ? statusClasses.cleared : statusClasses.pending"
                            >
                                {{ emp.progress === 100 ? 'Complete' : 'In Progress' }}
                            </span>
                        </div>
                    </div>

                    <div v-if="expandedId === emp.id" class="border-t border-gray-200 dark:border-neutral-700">
                        <div class="divide-y divide-gray-100 dark:divide-neutral-800">
                            <div
                                v-for="c in emp.clearances"
                                :key="c.id"
                                class="flex items-center justify-between px-4 py-3"
                            >
                                <div class="min-w-0 flex-1">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ c.title }}</p>
                                    <p class="text-xs text-gray-500">{{ c.department }}</p>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span
                                        class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium capitalize ring-1 ring-inset"
                                        :class="statusClasses[c.status]"
                                    >
                                        {{ c.status }}
                                    </span>
                                    <div v-if="c.status !== 'cleared'" class="flex gap-1">
                                        <Button size="sm" variant="outline" class="h-7 text-xs" @click.stop="updateClearance(c.id, 'cleared')">
                                            Clear
                                        </Button>
                                        <Button v-if="c.status !== 'flagged'" size="sm" variant="outline" class="h-7 text-xs text-red-600" @click.stop="updateClearance(c.id, 'flagged')">
                                            Flag
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
