<script setup lang="ts">
import { Bell, Check, Trash2 } from 'lucide-vue-next';
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

const props = withDefaults(
    defineProps<{
        id: string;
        title: string;
        body: string;
        type?: string | null;
        isRead: boolean;
        createdAt: string | null;
        userName?: string | null;
        userAvatar?: string | null;
        showNewBadge?: boolean;
        showTypeBadge?: boolean;
        canMarkRead?: boolean;
        canDelete?: boolean;
        primaryActionLabel?: string;
        disabled?: boolean;
    }>(),
    {
        type: 'info',
        isRead: false,
        createdAt: null,
        userName: null,
        userAvatar: null,
        showNewBadge: true,
        showTypeBadge: true,
        canMarkRead: true,
        canDelete: true,
        primaryActionLabel: 'Open',
        disabled: false,
    },
);

const emit = defineEmits<{
    (e: 'open', id: string): void;
    (e: 'markRead', id: string): void;
    (e: 'delete', id: string): void;
}>();

const formattedDate = computed(() => {
    if (!props.createdAt) return '';
    try {
        return new Date(props.createdAt).toLocaleString(undefined, {
            dateStyle: 'short',
            timeStyle: 'short',
        });
    } catch {
        return props.createdAt;
    }
});

const typeVariant = computed(() => {
    if (props.type === 'success') return 'default';
    if (props.type === 'error') return 'destructive';
    if (props.type === 'warning') return 'secondary';
    return 'outline';
});

const avatarInitials = computed(() => {
    const name = props.userName ?? '';
    if (!name) return '•';
    const parts = name.split(' ').filter(Boolean);
    if (!parts.length) return name.slice(0, 2).toUpperCase();
    if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase();
    return (parts[0][0] + parts[1][0]).toUpperCase();
});

function handleOpen() {
    if (props.disabled) return;
    emit('open', props.id);
}

function handleMarkRead(event: MouseEvent) {
    event.stopPropagation();
    if (props.disabled) return;
    emit('markRead', props.id);
}

function handleDelete(event: MouseEvent) {
    event.stopPropagation();
    if (props.disabled) return;
    emit('delete', props.id);
}
</script>

<template>
    <button
        type="button"
        class="flex w-full items-start gap-3 rounded-lg border px-4 py-3 text-left transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-primary disabled:cursor-not-allowed"
        :class="[
            isRead
                ? 'border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-900/40'
                : 'border-blue-200 bg-blue-50/60 dark:border-blue-900/60 dark:bg-blue-950/30',
        ]"
        :aria-pressed="!isRead"
        :disabled="disabled"
        @click="handleOpen"
    >
        <div
            class="mt-1 flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-background text-primary shadow-sm"
            :class="{
                'bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-300': type === 'success',
                'bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-300': type === 'warning',
                'bg-red-50 text-red-600 dark:bg-red-900/30 dark:text-red-300': type === 'error',
            }"
        >
            <Bell class="h-4 w-4" />
        </div>
        <div class="min-w-0 flex-1 space-y-1">
            <div class="flex items-start justify-between gap-2">
                <div class="min-w-0 flex-1">
                    <div class="flex flex-wrap items-center gap-2">
                        <p class="truncate text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ title }}
                        </p>
                        <Badge
                            v-if="showNewBadge && !isRead"
                            variant="default"
                            class="text-[10px]"
                        >
                            New
                        </Badge>
                        <Badge
                            v-if="showTypeBadge && type"
                            :variant="typeVariant"
                            class="text-[10px]"
                        >
                            {{ type }}
                        </Badge>
                    </div>
                </div>
                <div class="flex shrink-0 items-center gap-1">
                    <Button
                        v-if="canMarkRead && !isRead"
                        type="button"
                        variant="ghost"
                        size="icon"
                        class="h-7 w-7"
                        title="Mark as read"
                        @click="handleMarkRead"
                    >
                        <Check class="h-3 w-3" />
                    </Button>
                    <Button
                        v-if="canDelete"
                        type="button"
                        variant="ghost"
                        size="icon"
                        class="h-7 w-7 text-muted-foreground hover:text-destructive"
                        title="Delete"
                        @click="handleDelete"
                    >
                        <Trash2 class="h-3 w-3" />
                    </Button>
                </div>
            </div>
            <p class="line-clamp-2 text-xs text-gray-600 dark:text-gray-400 whitespace-pre-wrap">
                {{ body }}
            </p>
            <div class="mt-2 flex items-center justify-between gap-2 text-[11px] text-gray-500 dark:text-gray-500">
                <div class="flex items-center gap-2 min-w-0">
                    <Avatar v-if="userName || userAvatar" class="h-6 w-6">
                        <AvatarImage v-if="userAvatar" :src="userAvatar" :alt="userName || 'User'" />
                        <AvatarFallback class="text-[10px]">
                            {{ avatarInitials }}
                        </AvatarFallback>
                    </Avatar>
                    <span class="truncate">
                        {{ userName || '' }}
                    </span>
                </div>
                <span>
                    {{ formattedDate }}
                </span>
            </div>
        </div>
    </button>
</template>

