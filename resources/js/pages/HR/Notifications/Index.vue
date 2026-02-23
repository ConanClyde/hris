<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type NoticeItem = {
    id: number;
    title: string;
    message: string;
    type: string;
    is_active: boolean;
    expires_at: string | null;
    created_at: string;
};

type PaginatedData = {
    data: NoticeItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

defineProps<{ notices: PaginatedData }>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Notifications' },
];

function formatDate(value: string | null) {
    if (!value) return '—';
    try {
        return new Date(value).toLocaleDateString();
    } catch {
        return value;
    }
}
</script>

<template>
    <Head title="Notifications" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Notifications
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Global notices and updates.
                </p>
            </div>

            <div class="space-y-3">
                <div
                    v-for="n in notices.data"
                    :key="n.id"
                    class="rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-900"
                >
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <h3 class="font-medium text-gray-900 dark:text-gray-100">
                                {{ n.title }}
                            </h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">
                                {{ n.message }}
                            </p>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">
                                {{ formatDate(n.created_at) }}
                                <span v-if="n.expires_at"> · Expires {{ formatDate(n.expires_at) }}</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="!notices.data.length"
                class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-gray-400"
            >
                No notifications.
            </div>

            <div
                v-if="notices.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in notices.links" :key="i">
                    <span
                        v-if="!link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-200 px-3 text-sm text-gray-400 dark:border-neutral-700"
                        v-html="link.label"
                    />
                    <Link
                        v-else
                        :href="link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-3 text-sm transition-colors"
                        :class="link.active ? 'border-[#013CFC] bg-[#013CFC] text-white dark:border-[#60C8FC] dark:bg-[#60C8FC] dark:text-gray-900' : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
