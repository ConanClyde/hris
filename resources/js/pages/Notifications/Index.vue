<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { Bell } from 'lucide-vue-next';
import { computed } from 'vue';
import Pagination from '@/components/Pagination.vue';
import TableUserCell from '@/components/TableUserCell.vue';
import { Badge } from '@/components/ui/badge';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type NotificationItem = {
    id: number;
    title: string;
    body: string;
    type?: string | null;
    icon?: string | null;
    data?: Record<string, unknown>;
    is_read: boolean;
    created_at: string | null;
    user_name: string;
    user_avatar?: string | null;
};

type PaginatedNotifications = {
    data: NotificationItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = defineProps<{
    notifications: PaginatedNotifications;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Notifications',
    },
];

const hasUnread = computed(() =>
    props.notifications.data.some((n) => !n.is_read),
);

function formatDate(value: string | null) {
    if (!value) return '';
    try {
        return new Date(value).toLocaleString();
    } catch {
        return value;
    }
}

function linkFor(notification: NotificationItem): string | null {
    const data = notification.data || {};
    const url = (data.url as string | undefined) ?? undefined;
    return url ?? null;
}

function openNotification(notification: NotificationItem) {
    const url = linkFor(notification);
    if (url) {
        router.visit(url);
    }
}
</script>

<template>
    <Head title="Notifications" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-4 p-4">
            <div
                class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex size-9 items-center justify-center rounded-full bg-blue-100 text-blue-700 dark:bg-blue-900/40 dark:text-blue-300"
                    >
                        <Bell class="size-5" />
                    </div>
                    <div>
                        <h1
                            class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100"
                        >
                            Notifications
                        </h1>
                        <p
                            class="mt-0.5 text-sm text-gray-500 dark:text-gray-400"
                        >
                            Recent updates and alerts for your account.
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <Badge
                        v-if="hasUnread"
                        variant="outline"
                        class="border-blue-200 bg-blue-50 text-xs font-medium text-blue-700 dark:border-blue-900/60 dark:bg-blue-900/20 dark:text-blue-300"
                    >
                        Unread:
                        {{
                            notifications.data.filter((n) => !n.is_read).length
                        }}
                    </Badge>
                </div>
            </div>

            <div
                class="overflow-hidden rounded-lg border border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-900"
            >
                <div
                    v-if="notifications.data.length"
                    class="divide-y divide-gray-200 dark:divide-neutral-800"
                >
                    <button
                        v-for="notification in notifications.data"
                        :key="notification.id"
                        type="button"
                        class="flex w-full items-start gap-3 px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-neutral-800"
                        :class="{
                            'bg-blue-50/60 dark:bg-blue-900/10':
                                !notification.is_read,
                        }"
                        @click="openNotification(notification)"
                    >
                        <TableUserCell
                            :name="notification.user_name"
                            :avatar="notification.user_avatar || null"
                            :user-id="0"
                            :subtitle="formatDate(notification.created_at)"
                            size="sm"
                        />
                        <div class="min-w-0 flex-1 space-y-1">
                            <div class="flex items-start justify-between gap-2">
                                <p
                                    class="truncate text-sm font-medium text-gray-900 dark:text-gray-100"
                                >
                                    {{ notification.title }}
                                </p>
                                <Badge
                                    v-if="notification.type"
                                    variant="outline"
                                    class="shrink-0 text-[11px]"
                                >
                                    {{ notification.type }}
                                </Badge>
                            </div>
                            <p
                                class="line-clamp-2 text-xs text-gray-600 dark:text-gray-400"
                            >
                                {{ notification.body }}
                            </p>
                        </div>
                    </button>
                </div>
                <div
                    v-else
                    class="px-4 py-12 text-center text-sm text-gray-500 dark:text-gray-400"
                >
                    You have no notifications yet.
                </div>
            </div>

            <Pagination :meta="notifications" />
        </div>
    </AppLayout>
</template>
