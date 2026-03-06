<script setup lang="ts">
import { Bell, Sun, Moon, Check, Trash2 } from 'lucide-vue-next';
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Popover,
    PopoverContent,
    PopoverTrigger,
} from '@/components/ui/popover';
import { SidebarTrigger } from '@/components/ui/sidebar';
import { useAppearance } from '@/composables/useAppearance';
import { useBroadcasting } from '@/composables/useBroadcasting';
import type { BreadcrumbItem } from '@/types';

withDefaults(
    defineProps<{
        breadcrumbs?: BreadcrumbItem[];
    }>(),
    {
        breadcrumbs: () => [],
    },
);

// Theme toggle
const { appearance, updateAppearance } = useAppearance();

function toggleTheme() {
    const newTheme = appearance.value === 'dark' ? 'light' : 'dark';
    updateAppearance(newTheme);
}

// Notifications with WebSocket
const {
    notifications,
    unreadCount,
    markAsRead,
    markAllAsRead,
    deleteNotification,
} = useBroadcasting();

function formatTime(dateStr: string) {
    const date = new Date(dateStr);
    const now = new Date();
    const diff = Math.floor((now.getTime() - date.getTime()) / 1000);

    if (diff < 60) return 'Just now';
    if (diff < 3600) return `${Math.floor(diff / 60)}m ago`;
    if (diff < 86400) return `${Math.floor(diff / 3600)}h ago`;
    return `${Math.floor(diff / 86400)}d ago`;
}

function getNotificationColor(type: string) {
    switch (type) {
        case 'success':
            return 'bg-emerald-500';
        case 'warning':
            return 'bg-amber-500';
        case 'error':
            return 'bg-red-500';
        default:
            return 'bg-blue-500';
    }
}
</script>

<template>
    <header
        class="flex h-16 shrink-0 items-center gap-2 border-b border-sidebar-border/70 pr-[max(1.5rem,env(safe-area-inset-right))] pl-[max(1.5rem,env(safe-area-inset-left))] transition-[width,height] ease-linear group-has-data-[collapsible=icon]/sidebar-wrapper:h-12 md:px-4"
    >
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="breadcrumbs && breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="breadcrumbs" />
            </template>
        </div>

        <!-- Right side: Notifications + Theme Toggle -->
        <div class="ml-auto flex items-center gap-2">
            <!-- Notifications -->
            <Popover>
                <PopoverTrigger as-child>
                    <Button variant="ghost" size="icon" class="relative">
                        <Bell class="h-5 w-5" />
                        <Badge
                            v-if="unreadCount > 0"
                            class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full p-0 text-xs"
                        >
                            {{ unreadCount }}
                        </Badge>
                    </Button>
                </PopoverTrigger>
                <PopoverContent class="w-80 p-0" align="end">
                    <div class="flex items-center justify-between border-b p-3">
                        <h4 class="text-sm font-semibold">Notifications</h4>
                        <Button
                            v-if="unreadCount > 0"
                            variant="ghost"
                            size="sm"
                            class="h-auto px-2 py-1 text-xs"
                            @click="markAllAsRead"
                        >
                            Mark all read
                        </Button>
                    </div>
                    <div class="max-h-80 overflow-y-auto">
                        <div
                            v-for="notif in notifications"
                            :key="notif.id"
                            class="flex items-start gap-3 border-b p-3 transition-colors last:border-0 hover:bg-muted/50"
                            :class="{ 'opacity-60': notif.read }"
                        >
                            <div
                                class="mt-1 h-2 w-2 shrink-0 rounded-full"
                                :class="getNotificationColor(notif.type)"
                            />
                            <div class="min-w-0 flex-1">
                                <p class="truncate text-sm font-medium">
                                    {{ notif.title }}
                                </p>
                                <p
                                    class="line-clamp-2 text-xs text-muted-foreground"
                                >
                                    {{ notif.message }}
                                </p>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ formatTime(notif.created_at) }}
                                </p>
                            </div>
                            <div class="flex flex-col gap-1">
                                <Button
                                    v-if="!notif.read"
                                    variant="ghost"
                                    size="icon"
                                    class="h-6 w-6"
                                    @click="markAsRead(notif.id)"
                                >
                                    <Check class="h-3 w-3" />
                                </Button>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    class="h-6 w-6 text-muted-foreground hover:text-destructive"
                                    @click="deleteNotification(notif.id)"
                                >
                                    <Trash2 class="h-3 w-3" />
                                </Button>
                            </div>
                        </div>
                        <div
                            v-if="notifications.length === 0"
                            class="p-8 text-center text-sm text-muted-foreground"
                        >
                            No notifications
                        </div>
                    </div>
                </PopoverContent>
            </Popover>

            <!-- Theme Toggle -->
            <Button
                variant="ghost"
                size="icon"
                :title="
                    appearance === 'dark'
                        ? 'Switch to light mode'
                        : 'Switch to dark mode'
                "
                @click="toggleTheme"
            >
                <Sun v-if="appearance === 'dark'" class="h-5 w-5" />
                <Moon v-else class="h-5 w-5" />
            </Button>
        </div>
    </header>
</template>
