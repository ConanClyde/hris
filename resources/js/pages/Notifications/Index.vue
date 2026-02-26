<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import { Check } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { useBroadcasting } from '@/composables/useBroadcasting';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

type NoticeItem = {
    id: number;
    title: string;
    message: string;
    type: string;
    is_active: boolean;
    is_read?: boolean;
    expires_at: string | null;
    created_at: string;
};

type PaginatedData = {
    data: NoticeItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = defineProps<{ notices: PaginatedData }>();
const noticesData = ref(props.notices);

const { noticesUnreadCount } = useBroadcasting();
const page = usePage();

if (noticesUnreadCount.value === null) {
    const base = (page.props.auth?.counts || {}) as Record<string, any>;
    noticesUnreadCount.value = typeof base.notices_unread === 'number' ? base.notices_unread : 0;
}

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Notifications',
        href: '/notifications',
    },
];

function formatDate(value: string | null) {
    if (!value) return '—';
    try {
        return new Date(value).toLocaleDateString();
    } catch {
        return value;
    }
}

function typeVariant(type: string) {
    if (type === 'success') return 'default';
    if (type === 'danger') return 'destructive';
    if (type === 'warning') return 'secondary';
    return 'outline';
}

async function markAsRead(noticeId: number) {
    try {
        const url = `/admin/notifications/${noticeId}/mark-as-read`;
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
            },
        });

        if (response.ok) {
            const notice = noticesData.value.data.find(n => n.id === noticeId);
            if (notice) {
                const wasUnread = !notice.is_read;
                notice.is_read = true;

                if (wasUnread && typeof noticesUnreadCount.value === 'number') {
                    noticesUnreadCount.value = Math.max(0, noticesUnreadCount.value - 1);
                }
            }
        }
    } catch (error) {
        console.error('Failed to mark as read:', error);
    }
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
                    Global notices and updates.
                </p>
            </div>

            <div class="space-y-3">
                <div
                    v-for="n in noticesData.data"
                    :key="n.id"
                    class="rounded-lg border p-4 transition-colors"
                    :class="n.is_read ? 'border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800/50' : 'border-gray-200 bg-white dark:border-neutral-700 dark:bg-neutral-900'"
                >
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ n.title }}
                                </h3>
                                <Badge v-if="!n.is_read" variant="default" class="text-xs">New</Badge>
                                <Badge :variant="typeVariant(n.type)" class="text-xs">{{ n.type }}</Badge>
                            </div>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400 whitespace-pre-wrap">
                                {{ n.message }}
                            </p>
                            <p class="mt-2 text-xs text-gray-500 dark:text-gray-500">
                                {{ formatDate(n.created_at) }}
                                <span v-if="n.expires_at"> · Expires {{ formatDate(n.expires_at) }}</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-1">
                            <Button
                                v-if="!n.is_read"
                                type="button"
                                variant="ghost"
                                size="icon"
                                class="h-8 w-8 text-muted-foreground hover:text-primary"
                                title="Mark as read"
                                @click="markAsRead(n.id)"
                            >
                                <Check class="size-4" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <div
                v-if="!noticesData.data.length"
                class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-800/50 dark:text-gray-400"
            >
                No notifications.
            </div>

            <div
                v-if="noticesData.last_page > 1"
                class="flex flex-wrap items-center justify-center gap-2"
            >
                <template v-for="(link, i) in noticesData.links" :key="i">
                    <span
                        v-if="!link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-200 px-3 text-sm text-gray-400 dark:border-neutral-700"
                        v-html="link.label"
                    />
                    <Link
                        v-else
                        :href="link.url"
                        class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-3 text-sm transition-colors"
                        :class="link.active ? 'border-brand bg-brand text-white dark:border-brand-light dark:bg-brand-light dark:text-gray-900' : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
                    >
                        <span v-html="link.label" />
                    </Link>
                </template>
            </div>
        </div>
    </AppLayout>
</template>
