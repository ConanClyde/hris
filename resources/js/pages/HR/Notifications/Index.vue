<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, ref } from 'vue';
import NotificationCard from '@/components/notifications/NotificationCard.vue';
import Pagination from '@/components/Pagination.vue';
import { useBroadcasting } from '@/composables/useBroadcasting';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type NotificationItem = {
    id: string;
    title: string;
    type: string;
    body: string;
    icon?: string | null;
    data?: Record<string, unknown>;
    is_read: boolean;
    created_at: string | null;
    user_name?: string;
    user_avatar?: string | null;
};

type PaginatedData = {
    data: NotificationItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = defineProps<{ notifications: PaginatedData; filter?: string }>();
const notifications = ref<PaginatedData>(props.notifications ?? { data: [], current_page: 1, last_page: 1, links: [] });

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Notifications' },
];

const hasNotifications = computed(() => notifications.value.data.length > 0);

const {
    notificationsUnreadCount,
    markAllAsRead: markAllAsReadGlobal,
    markAsRead: markAsReadGlobal,
    refreshNotificationsDropdown,
} = useBroadcasting();

function csrfToken(): string {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
}

async function markAsRead(notificationId: string) {
    const notif = notifications.value.data.find(n => n.id === notificationId);
    const wasUnread = !!notif && !notif.is_read;
    if (notif) {
        notif.is_read = true;
    }
    if (wasUnread) {
        markAsReadGlobal(notificationId);
    }

    try {
        const response = await fetch(`/hr/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!response.ok && notif) {
            notif.is_read = false;
            return;
        }

        await refreshNotificationsDropdown('hr');
    } catch (error) {
        if (notif) {
            notif.is_read = false;
        }
        console.error('Failed to mark as read:', error);
    }
}

async function openNotification(notificationId: string) {
    const notification = notifications.value.data.find(n => n.id === notificationId);
    if (!notification) return;

    const wasUnread = !notification.is_read;
    notification.is_read = true;
    if (wasUnread) {
        markAsReadGlobal(notificationId);
    }

    try {
        const response = await fetch(`/hr/notifications/${notificationId}/mark-as-read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken(),
                'Accept': 'application/json',
            },
        });

        if (!response.ok) {
            notification.is_read = false;
            return;
        }

        await refreshNotificationsDropdown('hr');
    } catch (error) {
        notification.is_read = false;
        console.error('Failed to mark as read before open:', error);
    }

    const data = notification.data || {};
    const redirect = (data.redirect_url as string | undefined)
        ?? (data.url as string | undefined)
        ?? undefined;

    if (redirect) {
        window.location.href = redirect;
    }
}

async function markAllRead() {
    notifications.value = {
        ...notifications.value,
        data: notifications.value.data.map(n => ({ ...n, is_read: true })),
    };
    markAllAsReadGlobal();

    await fetch('/hr/notifications/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            'Accept': 'application/json',
        },
    });

    await refreshNotificationsDropdown('hr');
}

async function deleteOne(notificationId: string) {
    await fetch(`/hr/notifications/${notificationId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            'Accept': 'application/json',
        },
    });

    notifications.value = {
        ...notifications.value,
        data: notifications.value.data.filter(n => n.id !== notificationId),
    };
}

async function deleteAll() {
    await fetch('/hr/notifications', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            'Accept': 'application/json',
        },
    });

    if (typeof notificationsUnreadCount.value === 'number') {
        notificationsUnreadCount.value = 0;
    }

    notifications.value = {
        ...notifications.value,
        data: [],
    };
}
</script>

<template>
    <Head title="Notifications" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-4 p-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">
                        Notifications
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Updates about employees, leave applications, training, and notices.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        href="/hr/notifications"
                        class="text-sm"
                        :class="props.filter === 'unread' ? 'text-muted-foreground hover:text-foreground' : 'font-medium text-foreground'"
                    >
                        All
                    </Link>
                    <span class="text-muted-foreground">•</span>
                    <Link
                        href="/hr/notifications?filter=unread"
                        class="text-sm"
                        :class="props.filter === 'unread' ? 'font-medium text-foreground' : 'text-muted-foreground hover:text-foreground'"
                    >
                        Unread
                    </Link>
                    <div class="ml-2 h-4 w-px bg-border"></div>
                    <button
                        type="button"
                        class="text-sm text-muted-foreground hover:text-foreground"
                        @click="markAllRead"
                    >
                        Mark all read
                    </button>
                    <button
                        type="button"
                        class="text-sm text-red-600 hover:text-red-700"
                        @click="deleteAll"
                    >
                        Delete all
                    </button>
                </div>
            </div>

            <div
                v-if="hasNotifications"
                class="space-y-3"
            >
                <NotificationCard
                    v-for="n in notifications.data"
                    :id="n.id"
                    :key="n.id"
                    :title="n.title"
                    :body="n.body"
                    :type="n.type"
                    :is-read="n.is_read"
                    :created-at="n.created_at"
                    :user-name="n.user_name"
                    :user-avatar="n.user_avatar || undefined"
                    :show-new-badge="true"
                    :show-type-badge="true"
                    :can-mark-read="!n.is_read"
                    :can-delete="true"
                    @open="openNotification"
                    @markRead="markAsRead"
                    @delete="deleteOne"
                />
            </div>

            <div
                v-else
                class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-gray-400"
            >
                No notifications.
            </div>

            <Pagination :meta="notifications" />
        </div>
    </AppLayout>
</template>

