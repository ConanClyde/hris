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
        class="flex items-center gap-3 rounded-full border border-gray-200 bg-white px-4 py-2 shadow-sm cursor-pointer hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-800 dark:hover:bg-neutral-700 transition-colors"
        @click="emit('click')"
    >
        <!-- Avatar -->
        <Avatar class="h-10 w-10 shrink-0 overflow-hidden rounded-xl">
            <img v-if="avatar" :src="avatar" class="h-full w-full object-cover" alt="User avatar" />
            <AvatarFallback class="rounded-xl bg-foreground text-background font-bold">
                {{ initials || '?' }}
            </AvatarFallback>
        </Avatar>

        <!-- Placeholder Input -->
        <div class="flex-1 min-w-0">
            <input
                type="text"
                readonly
                :placeholder="placeholder || 'What\'s on your mind?'"
                class="w-full bg-transparent text-gray-500 cursor-pointer focus:outline-none dark:text-gray-400 truncate"
            />
        </div>

        <!-- Icons -->
        <div class="flex items-center gap-1 flex-shrink-0">
            <button
                type="button"
                class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-700 text-pink-500 transition-colors"
                @click.stop="emit('click')"
            >
                <Video class="h-5 w-5" />
            </button>
            <button
                type="button"
                class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-700 text-green-500 transition-colors"
                @click.stop="emit('click')"
            >
                <ImageIcon class="h-5 w-5" />
            </button>
            <button
                type="button"
                class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-neutral-700 text-yellow-500 transition-colors"
                @click.stop="emit('click')"
            >
                <Smile class="h-5 w-5" />
            </button>
        </div>
    </div>
</template>
