<script setup lang="ts">
import { computed } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { useInitials } from '@/composables/useInitials';
import type { User } from '@/types';

type Props = {
    user: User;
    showEmail?: boolean;
};

const props = withDefaults(defineProps<Props>(), {
    showEmail: false,
});

const { getInitials, getInitialsFromName } = useInitials();

const userInitials = computed(() => {
    const first = (props.user as any).first_name;
    const last = (props.user as any).last_name;
    return getInitialsFromName({ first_name: first, last_name: last }) || getInitials(props.user.name);
});

// Compute whether we should show the avatar image
const showAvatar = computed(
    () => typeof (props.user as any).avatar === 'string' && (props.user as any).avatar.trim() !== '',
);
</script>

<template>
    <Avatar class="h-8 w-8 shrink-0 overflow-hidden rounded-lg">
        <AvatarImage v-if="showAvatar" :src="user.avatar!" :alt="user.name" />
        <AvatarFallback class="rounded-lg bg-foreground text-background font-bold">
            {{ userInitials }}
        </AvatarFallback>
    </Avatar>

    <div class="grid flex-1 text-left text-sm leading-tight">
        <span class="truncate font-medium">{{ user.name }}</span>
        <span v-if="showEmail" class="truncate text-xs text-muted-foreground">{{
            user.email
        }}</span>
    </div>
</template>
