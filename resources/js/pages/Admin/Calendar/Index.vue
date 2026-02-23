<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type HolidayItem = {
    id: number;
    name: string;
    date: string;
    type: string;
};

const props = withDefaults(
    defineProps<{
        holidays?: HolidayItem[];
        filters?: { category?: string; status?: string };
    }>(),
    { holidays: () => [], filters: () => ({}) }
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Calendar',
    },
];

const filterCategory = ref(props.filters?.category ?? 'all');
const filterStatus = ref(props.filters?.status ?? 'all');

watch(
    () => [props.filters?.category, props.filters?.status],
    ([category, status]) => {
        filterCategory.value = (category as string) ?? 'all';
        filterStatus.value = (status as string) ?? 'all';
    },
    { immediate: true }
);

watch([filterCategory, filterStatus], () => {
    const query: Record<string, string> = {};
    if (filterCategory.value && filterCategory.value !== 'all') query.category = filterCategory.value;
    if (filterStatus.value && filterStatus.value !== 'all') query.status = filterStatus.value;
    router.get(window.location.pathname, query, { preserveState: true });
});

function formatDate(value: string) {
    try {
        return new Date(value).toLocaleDateString();
    } catch {
        return value;
    }
}
</script>

<template>
    <Head title="Calendar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Calendar
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Manage events and schedules
                    </p>
                </div>

                <div class="flex flex-wrap items-end gap-3">
                    <Button type="button">Add Holiday</Button>
                    <div class="w-[140px]">
                        <Label for="filter-category" class="sr-only">Category</Label>
                        <Select v-model="filterCategory">
                            <SelectTrigger id="filter-category" class="h-9">
                                <SelectValue placeholder="All Events" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All Events</SelectItem>
                                <SelectItem value="leave">Leaves Only</SelectItem>
                                <SelectItem value="training">Training Only</SelectItem>
                                <SelectItem value="holiday">Holidays Only</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                    <div class="w-[140px]">
                        <Label for="filter-status" class="sr-only">Status</Label>
                        <Select v-model="filterStatus">
                            <SelectTrigger id="filter-status" class="h-9">
                                <SelectValue placeholder="All Status" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All Status</SelectItem>
                                <SelectItem value="approved">Approved</SelectItem>
                                <SelectItem value="pending">Pending</SelectItem>
                            </SelectContent>
                        </Select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                <div class="lg:col-span-8">
                    <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 overflow-hidden h-[750px] flex flex-col">
                        <div class="p-4 border-b border-gray-200 dark:border-neutral-700">
                            <h2 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Calendar View</h2>
                        </div>
                        <div class="flex-1 p-4">
                            <div class="h-full rounded border border-dashed border-gray-200 dark:border-neutral-700 bg-gray-50 dark:bg-neutral-950 flex items-center justify-center">
                                <span class="text-sm text-gray-500 dark:text-gray-400">Calendar component placeholder</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4">
                    <div class="rounded-lg border border-gray-200 dark:border-neutral-700 bg-white dark:bg-neutral-900 overflow-hidden h-[750px] flex flex-col">
                        <div class="p-4 border-b border-gray-200 dark:border-neutral-700">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100 uppercase tracking-tight">Custom Holidays</h3>
                        </div>
                        <div class="flex-1 p-4 overflow-y-auto space-y-3">
                            <div
                                v-for="holiday in holidays"
                                :key="holiday.id"
                                class="p-3 rounded border border-gray-100 dark:border-neutral-700 bg-white dark:bg-neutral-800"
                            >
                                <div class="flex items-center justify-between">
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ holiday.name }}</span>
                                    <Badge variant="outline" size="sm">{{ holiday.type }}</Badge>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ formatDate(holiday.date) }}
                                </div>
                            </div>
                            <div
                                v-if="!holidays.length"
                                class="text-center text-sm text-gray-500 dark:text-gray-400 py-8"
                            >
                                No custom holidays found.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
