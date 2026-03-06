<script setup lang="ts">
import { Video, Image as ImageIcon, Smile } from 'lucide-vue-next';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';

defineProps<{
    avatar?: string | null;
    initials?: string;
    placeholder?: string;
}>();

const emit = defineEmits<{
    (e: 'click'): void;
}>();
</script>

<template>
    <div
        class="flex cursor-pointer items-center gap-3 rounded-full border border-gray-200 bg-white px-4 py-2 shadow-sm transition-colors hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700"
        @click="emit('click')"
    >
        <!-- Avatar -->
        <Avatar class="h-10 w-10 shrink-0 overflow-hidden rounded-xl">
            <img
                v-if="avatar"
                :src="avatar"
                class="h-full w-full object-cover"
                alt="User avatar"
            />
            <AvatarFallback
                class="rounded-xl bg-foreground font-bold text-background"
            >
                {{ initials || '?' }}
            </AvatarFallback>
        </Avatar>

        <!-- Placeholder Input -->
        <div class="min-w-0 flex-1">
            <input
                type="text"
                readonly
                :placeholder="placeholder || 'What\'s on your mind?'"
                class="w-full cursor-pointer truncate bg-transparent text-gray-500 focus:outline-none dark:text-gray-400"
            />
        </div>

        <!-- Icons -->
        <div class="flex flex-shrink-0 items-center gap-1">
            <button
                type="button"
                class="rounded-full p-2 text-pink-500 transition-colors hover:bg-gray-100 dark:hover:bg-neutral-700"
                @click.stop="emit('click')"
            >
                <Video class="h-5 w-5" />
            </button>
            <button
                type="button"
                class="rounded-full p-2 text-green-500 transition-colors hover:bg-gray-100 dark:hover:bg-neutral-700"
                @click.stop="emit('click')"
            >
                <ImageIcon class="h-5 w-5" />
            </button>
            <button
                type="button"
                class="rounded-full p-2 text-yellow-500 transition-colors hover:bg-gray-100 dark:hover:bg-neutral-700"
                @click.stop="emit('click')"
            >
                <Smile class="h-5 w-5" />
            </button>
        </div>
    </div>
</template>
