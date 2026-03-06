<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type SourceDef = {
    label: string;
    columns: string[];
};

const props = defineProps<{
    sources: Record<string, SourceDef>;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Reports', href: hr.reports.url() },
    { title: 'Custom Report' },
];

const selectedSource = ref<string>(
    Object.keys(props.sources)[0] ?? 'employees',
);
const selectedColumns = ref<string[]>([]);

// When source changes, reset columns
function setSource(key: string) {
    selectedSource.value = key;
    selectedColumns.value = [];
}

function toggleColumn(col: string) {
    const idx = selectedColumns.value.indexOf(col);
    if (idx >= 0) {
        selectedColumns.value.splice(idx, 1);
    } else {
        selectedColumns.value.push(col);
    }
}

function selectAll() {
    const src = props.sources[selectedSource.value];
    if (src) {
        selectedColumns.value = [...src.columns];
    }
}

function deselectAll() {
    selectedColumns.value = [];
}

const currentSourceColumns = computed(
    () => props.sources[selectedSource.value]?.columns ?? [],
);
const canExport = computed(() => selectedColumns.value.length > 0);

function exportCsv() {
    if (!canExport.value) return;
    const params = new URLSearchParams();
    params.set('source', selectedSource.value);
    selectedColumns.value.forEach((c) => params.append('columns[]', c));
    window.open(`/hr/reports/custom/export?${params.toString()}`, '_blank');
}

function formatColumnName(col: string) {
    return col.replace(/_/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase());
}
</script>

<template>
    <Head title="Custom Report Builder" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-4xl space-y-6 p-4">
            <div>
                <h1
                    class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                >
                    Custom Report Builder
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Select a data source, choose columns, and generate a CSV
                    export.
                </p>
            </div>

            <!-- Step 1: Select Data Source -->
            <Card class="border border-gray-200 dark:border-neutral-800">
                <CardHeader>
                    <CardTitle class="text-base"
                        >1. Select Data Source</CardTitle
                    >
                </CardHeader>
                <CardContent>
                    <div class="flex flex-wrap gap-3">
                        <button
                            v-for="(source, key) in sources"
                            :key="key"
                            class="rounded-lg border-2 px-5 py-3 text-sm font-medium transition-all"
                            :class="
                                selectedSource === key
                                    ? 'border-brand bg-brand/5 text-brand dark:border-brand-light dark:text-brand-light'
                                    : 'border-gray-200 text-gray-600 hover:border-gray-300 dark:border-neutral-700 dark:text-gray-400 dark:hover:border-neutral-600'
                            "
                            @click="setSource(key as string)"
                        >
                            {{ source.label }}
                        </button>
                    </div>
                </CardContent>
            </Card>

            <!-- Step 2: Select Columns -->
            <Card class="border border-gray-200 dark:border-neutral-800">
                <CardHeader class="flex flex-row items-center justify-between">
                    <CardTitle class="text-base">2. Select Columns</CardTitle>
                    <div class="flex gap-2">
                        <button
                            class="text-xs text-brand hover:underline dark:text-brand-light"
                            @click="selectAll"
                        >
                            Select All
                        </button>
                        <button
                            class="text-xs text-gray-500 hover:underline dark:text-gray-400"
                            @click="deselectAll"
                        >
                            Clear
                        </button>
                    </div>
                </CardHeader>
                <CardContent>
                    <div
                        class="grid grid-cols-2 gap-2 sm:grid-cols-3 md:grid-cols-4"
                    >
                        <button
                            v-for="col in currentSourceColumns"
                            :key="col"
                            class="flex items-center gap-2 rounded-md border px-3 py-2 text-sm transition-all"
                            :class="
                                selectedColumns.includes(col)
                                    ? 'border-brand bg-brand/5 text-brand dark:border-brand-light dark:text-brand-light'
                                    : 'border-gray-200 text-gray-600 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-400 dark:hover:bg-neutral-800'
                            "
                            @click="toggleColumn(col)"
                        >
                            <div
                                class="flex size-4 shrink-0 items-center justify-center rounded border transition-colors"
                                :class="
                                    selectedColumns.includes(col)
                                        ? 'border-brand bg-brand text-white'
                                        : 'border-gray-300 dark:border-neutral-600'
                                "
                            >
                                <svg
                                    v-if="selectedColumns.includes(col)"
                                    class="size-3"
                                    viewBox="0 0 12 12"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="2"
                                >
                                    <path d="M2 6l3 3 5-5" />
                                </svg>
                            </div>
                            {{ formatColumnName(col) }}
                        </button>
                    </div>
                </CardContent>
            </Card>

            <!-- Step 3: Export -->
            <Card class="border border-gray-200 dark:border-neutral-800">
                <CardHeader>
                    <CardTitle class="text-base">3. Generate Report</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500 dark:text-gray-400">
                            <template v-if="canExport">
                                Ready to export
                                <strong>{{ selectedColumns.length }}</strong>
                                column(s) from
                                <strong>{{
                                    sources[selectedSource]?.label
                                }}</strong
                                >.
                            </template>
                            <template v-else>
                                Select at least one column to generate a report.
                            </template>
                        </p>
                        <Button :disabled="!canExport" @click="exportCsv">
                            Export CSV
                        </Button>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
