<script setup lang="ts">
import { computed, watch, onMounted, ref } from 'vue';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { getInitials, getInitialsFromName } from '@/composables/useInitials';
import { useCachedAvatar } from '@/composables/useRealtimeAvatar';

const props = withDefaults(
    defineProps<{
        name: string;
        firstName?: string | null;
        lastName?: string | null;
        avatar?: string | null;
        userId?: number;
        subtitle?: string | null;
        size?: 'sm' | 'md';
    }>(),
    { firstName: null, lastName: null, avatar: null, subtitle: null, size: 'sm' },
);

const { getAvatar } = useCachedAvatar();

const cachedAvatar = ref<string | null>(null);

onMounted(() => {
    if (props.userId) {
        cachedAvatar.value = getAvatar(props.userId, props.avatar);
    }
});

watch(
    () => props.avatar,
    (newAvatar) => {
        if (props.userId) {
            cachedAvatar.value = getAvatar(props.userId, newAvatar);
        }
    },
    { immediate: true },
);

watch(
    () => props.userId,
    (newId) => {
        if (newId) {
            cachedAvatar.value = getAvatar(newId, props.avatar);
        }
    },
    { immediate: true },
);

const displayAvatar = computed(() => {
    if (cachedAvatar.value) return cachedAvatar.value;
    return props.avatar;
});

const showAvatar = computed(() =>
    typeof displayAvatar.value === 'string' && displayAvatar.value.trim() !== '',
);
const avatarSizeClass = props.size === 'md' ? 'h-9 w-9' : 'h-8 w-8';

const userInitials = computed(() => {
    return getInitialsFromName({ first_name: props.firstName, last_name: props.lastName }) || getInitials(props.name);
});
</script>

<template>
    <div class="flex min-w-0 items-center gap-2">
        <Avatar
            :class="[avatarSizeClass, 'shrink-0 overflow-hidden rounded-lg']"
        >
            <AvatarImage v-if="showAvatar" :src="displayAvatar!" :alt="name" />
            <AvatarFallback
                class="rounded-lg bg-foreground text-xs font-bold text-background"
            >
                {{ userInitials }}
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
