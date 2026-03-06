<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type ChecklistItem = {
    id: number;
    title: string;
    category: string;
    is_completed: boolean;
    completed_at: string | null;
};

type EmployeeOnboarding = {
    id: number;
    name: string;
    date_hired: string | null;
    total: number;
    completed: number;
    progress: number;
    checklist: ChecklistItem[];
};

defineProps<{
    employees: EmployeeOnboarding[];
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Onboarding' }];

const expandedId = ref<number | null>(null);

function toggleExpand(id: number) {
    expandedId.value = expandedId.value === id ? null : id;
}

function initChecklist(employeeId: number) {
    router.post(`/hr/onboarding/${employeeId}/init`);
}

function toggleItem(itemId: number) {
    router.post(`/hr/onboarding/toggle/${itemId}`);
}

const categoryColors: Record<string, string> = {
    documents:
        'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
    hr: 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
    it: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
    general: 'bg-gray-100 text-gray-700 dark:bg-neutral-800 dark:text-gray-400',
};
</script>

<template>
    <Head title="Onboarding" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-5xl space-y-6 p-4">
            <div>
                <h1
                    class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                >
                    New Hire Onboarding
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Track onboarding progress for each employee with
                    standardized checklists.
                </p>
            </div>

            <div class="space-y-3">
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
                            <div
                                class="flex size-10 items-center justify-center rounded-full bg-primary/10 text-primary"
                            >
                                <span class="text-xs font-semibold uppercase">{{
                                    emp.name.substring(0, 2)
                                }}</span>
                            </div>
                            <div>
                                <p
                                    class="font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ emp.name }}
                                </p>
                                <p
                                    v-if="emp.date_hired"
                                    class="text-xs text-gray-500 dark:text-gray-400"
                                >
                                    Hired: {{ emp.date_hired }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <div
                                v-if="emp.total > 0"
                                class="flex items-center gap-2"
                            >
                                <div
                                    class="h-2 w-24 overflow-hidden rounded-full bg-gray-200 dark:bg-neutral-700"
                                >
                                    <div
                                        class="h-full rounded-full bg-emerald-500 transition-all"
                                        :style="{ width: `${emp.progress}%` }"
                                    />
                                </div>
                                <span class="text-xs text-gray-500"
                                    >{{ emp.completed }}/{{ emp.total }}</span
                                >
                            </div>
                            <Button
                                v-else
                                variant="outline"
                                size="sm"
                                @click.stop="initChecklist(emp.id)"
                            >
                                Initialize
                            </Button>
                        </div>
                    </div>

                    <div
                        v-if="expandedId === emp.id && emp.checklist.length > 0"
                        class="border-t border-gray-200 p-4 dark:border-neutral-700"
                    >
                        <div class="space-y-2">
                            <div
                                v-for="item in emp.checklist"
                                :key="item.id"
                                class="flex items-center gap-3 rounded-md px-3 py-2 transition-colors hover:bg-gray-50 dark:hover:bg-neutral-800/40"
                            >
                                <button
                                    class="flex size-5 shrink-0 items-center justify-center rounded border transition-colors"
                                    :class="
                                        item.is_completed
                                            ? 'border-emerald-500 bg-emerald-500 text-white'
                                            : 'border-gray-300 dark:border-neutral-600'
                                    "
                                    @click="toggleItem(item.id)"
                                >
                                    <svg
                                        v-if="item.is_completed"
                                        class="size-3"
                                        viewBox="0 0 12 12"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path d="M2 6l3 3 5-5" />
                                    </svg>
                                </button>
                                <span
                                    class="flex-1 text-sm"
                                    :class="
                                        item.is_completed
                                            ? 'text-gray-400 line-through'
                                            : 'text-gray-900 dark:text-gray-100'
                                    "
                                >
                                    {{ item.title }}
                                </span>
                                <span
                                    class="inline-flex rounded-full px-2 py-0.5 text-[10px] font-medium capitalize"
                                    :class="
                                        categoryColors[item.category] ??
                                        categoryColors.general
                                    "
                                >
                                    {{ item.category }}
                                </span>
                                <span
                                    v-if="item.completed_at"
                                    class="text-[10px] text-gray-400"
                                    >{{ item.completed_at }}</span
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
