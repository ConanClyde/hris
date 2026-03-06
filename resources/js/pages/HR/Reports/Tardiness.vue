<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { AlertTriangle } from 'lucide-vue-next';
import { ref, watch } from 'vue';
import ListFiltersBar from '@/components/ListFiltersBar.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type Violator = {
    user_id: number;
    user_name: string;
    tardy: boolean;
    undertime: boolean;
    tardiness_counts: Record<number, number>;
    undertime_counts: Record<number, number>;
};

const props = defineProps<{
    violators: Violator[];
    year: number;
    semester: number;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: hr.reports().url },
    { title: 'Habitual Tardiness / Undertime' },
];

const yearSelect = ref(String(props.year));
const semesterSelect = ref(String(props.semester));

watch([yearSelect, semesterSelect], () => {
    router.get(
        hr.reports.tardiness.url(),
        {
            year: yearSelect.value,
            semester: semesterSelect.value,
        },
        { preserveState: true },
    );
});

function resetFilters() {
    yearSelect.value = String(props.year);
    semesterSelect.value = String(props.semester);
    router.get(
        hr.reports.tardiness.url(),
        {
            year: yearSelect.value,
            semester: semesterSelect.value,
        },
        { preserveState: true },
    );
}

function formatCounts(counts: Record<number, number>): string {
    const parts = Object.entries(counts)
        .filter(([, v]) => v > 0)
        .map(([k, v]) => `${k}:${v}`);

    return parts.length ? parts.join(', ') : '—';
}
</script>

<template>
    <Head title="Habitual Tardiness / Undertime Report" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div>
                <h1
                    class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                >
                    Habitual Tardiness / Undertime Report
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Employees with 10+ tardy or undertime days in each of 2
                    consecutive months (CSC MC 16).
                </p>
            </div>

            <ListFiltersBar>
                <div class="w-[110px]">
                    <Label class="sr-only">Year</Label>
                    <Input
                        v-model="yearSelect"
                        type="number"
                        min="2020"
                        max="2100"
                        class="h-10"
                    />
                </div>

                <div class="w-[220px]">
                    <Label class="sr-only">Semester</Label>
                    <Select v-model="semesterSelect">
                        <SelectTrigger class="h-10">
                            <SelectValue placeholder="Semester" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="1"
                                >Jan – Jun (Semester 1)</SelectItem
                            >
                            <SelectItem value="2"
                                >Jul – Dec (Semester 2)</SelectItem
                            >
                        </SelectContent>
                    </Select>
                </div>

                <Button
                    type="button"
                    variant="outline"
                    class="h-10"
                    @click="resetFilters"
                >
                    Reset
                </Button>
            </ListFiltersBar>

            <div class="flex items-center gap-2">
                <AlertTriangle
                    class="size-4 text-amber-600 dark:text-amber-400"
                />
                <h2
                    class="text-sm font-medium text-gray-700 dark:text-gray-300"
                >
                    Violators ({{ violators.length }})
                </h2>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-gray-200 dark:border-neutral-700"
            >
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[960px] border-collapse text-sm">
                        <thead
                            class="border-b border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50"
                        >
                            <tr>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Employee
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Habitually Tardy
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Habitually Undertime
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Tardiness (month:count)
                                </th>
                                <th
                                    class="px-4 py-3 text-left font-medium text-gray-700 dark:text-gray-300"
                                >
                                    Undertime (month:count)
                                </th>
                            </tr>
                        </thead>
                        <tbody
                            class="divide-y divide-gray-200 dark:divide-neutral-700"
                        >
                            <tr
                                v-for="v in violators"
                                :key="v.user_id"
                                class="hover:bg-gray-50 dark:hover:bg-neutral-800/50"
                            >
                                <td class="px-4 py-3 font-medium">
                                    {{ v.user_name }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ v.tardy ? 'Yes' : 'No' }}
                                </td>
                                <td class="px-4 py-3">
                                    {{ v.undertime ? 'Yes' : 'No' }}
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    {{ formatCounts(v.tardiness_counts) }}
                                </td>
                                <td class="px-4 py-3 text-xs">
                                    {{ formatCounts(v.undertime_counts) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    v-if="violators.length === 0"
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    No habitual tardiness or undertime violators for the
                    selected period.
                </div>
            </div>
        </div>
    </AppLayout>
</template>
