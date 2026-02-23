<script setup lang="ts">
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials } from '@/composables/useInitials';

const props = withDefaults(
    defineProps<{
        name: string;
        avatar?: string | null;
        subtitle?: string | null;
        size?: 'sm' | 'md';
    }>(),
    { avatar: null, subtitle: null, size: 'sm' }
);

const showAvatar = computed(() => Boolean(props.avatar && props.avatar !== ''));
const avatarSizeClass = props.size === 'md' ? 'h-9 w-9' : 'h-8 w-8';
</script>

<template>
    <div class="flex items-center gap-2 min-w-0">
        <Avatar
            :class="[avatarSizeClass, 'shrink-0 overflow-hidden rounded-lg']"
        >
            <AvatarImage v-if="showAvatar" :src="avatar!" :alt="name" />
            <AvatarFallback
                class="rounded-lg bg-foreground text-background text-xs font-bold"
            >
                {{ getInitials(name) }}
            </AvatarFallback>
        </Avatar>
        <div class="grid min-w-0 flex-1 text-left text-sm leading-tight">
            <span class="truncate font-medium text-foreground">{{ name }}</span>
            <span
                v-if="subtitle"
                class="truncate text-xs text-muted-foreground"
            >
                {{ subtitle }}
            </span>
        </div>
    </div>
</template>
