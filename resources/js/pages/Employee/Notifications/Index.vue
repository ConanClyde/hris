<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { AlertCircle, AlertTriangle, Bell, CheckCircle, Info } from 'lucide-vue-next';
import AppLayout from '@/layouts/AppLayout.vue';
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
        return new Date(value).toLocaleString(undefined, {
            dateStyle: 'short',
            timeStyle: 'short',
        });
    } catch {
        return value;
    }
}

function noticeIcon(type: string) {
    if (type === 'success') return CheckCircle;
    if (type === 'warning') return AlertTriangle;
    if (type === 'danger') return AlertCircle;
    return Info;
}

function noticeColor(type: string) {
    if (type === 'success') return 'text-emerald-600 dark:text-emerald-400';
    if (type === 'warning') return 'text-amber-600 dark:text-amber-400';
    if (type === 'danger') return 'text-red-600 dark:text-red-400';
    return 'text-primary';
}
</script>

<template>
    <Head title="Notifications" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div>
                <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                    Notifications
                </h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                    Notices and updates for you.
                </p>
            </div>

            <div class="space-y-3">
                <div
                    v-for="n in notices.data"
                    :key="n.id"
                    class="flex gap-3 rounded-lg border border-gray-200 bg-white p-4 dark:border-neutral-700 dark:bg-neutral-800/50"
                >
                    <div
                        class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg"
                        :class="noticeColor(n.type)"
                    >
                        <component :is="noticeIcon(n.type)" class="size-5" />
                    </div>
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

            <div
                v-if="!notices.data.length"
                class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-gray-400"
            >
                <Bell class="mx-auto size-10 text-gray-400 dark:text-gray-500" />
                <p class="mt-2">No notifications yet.</p>
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
                        :class="link.active ? 'border-primary bg-primary text-primary-foreground' : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
