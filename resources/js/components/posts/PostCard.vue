<script setup lang="ts">
import { Heart, MessageCircle, ThumbsUp } from 'lucide-vue-next';
import { computed } from 'vue';
import { Avatar, AvatarFallback } from '@/components/ui/avatar';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';

export type PostAuthor = {
    id: number;
    name?: string;
    first_name?: string;
    last_name?: string;
} | null;

export type PostItem = {
    id: number;
    title: string;
    body: string;
    image_url?: string | null;
    role_scope?: string;
    is_published?: boolean;
    comments_count?: number;
    reactions_count?: number;
    user_reaction?: string | null;
    author?: PostAuthor;
    created_at: string;
};

const props = defineProps<{
    post: PostItem;
    showRoleScope?: boolean;
    showPinned?: boolean;
    showDraft?: boolean;
    reactionTypes?: { key: string; label: string; icon: any }[];
    enableReactions?: boolean;
    enableCommentComposer?: boolean;
    commentDraft?: string;
    commentPlaceholder?: string;
}>();

const emit = defineEmits<{
    (e: 'react', payload: { postId: number; type: string }): void;
    (e: 'update:commentDraft', value: string): void;
    (e: 'submitComment', payload: { postId: number }): void;
}>();

const authorName = computed(() => {
    const a = props.post.author;
    if (a?.first_name || a?.last_name) return `${a?.first_name ?? ''} ${a?.last_name ?? ''}`.trim();
    return a?.name ?? 'System';
});

const authorInitials = computed(() => {
    const first = props.post.author?.first_name?.[0] || '';
    const last = props.post.author?.last_name?.[0] || '';
    return (first + last).toUpperCase() || '??';
});

function formatDate(value: string) {
    try {
        return new Date(value).toLocaleString();
    } catch {
        return value;
    }
}

const roleScopeLabel = (value?: string) => {
    if (value === 'hr') return 'HR only';
    if (value === 'employee') return 'Employees only';
    if (value === 'all') return 'All users';
    return value ?? '';
};

const defaultReactionTypes = [
    { key: 'like', icon: ThumbsUp, label: 'Like' },
    { key: 'heart', icon: Heart, label: 'Love' },
];

const reactions = computed(() => props.reactionTypes?.length ? props.reactionTypes : defaultReactionTypes);
</script>

<template>
    <article
        class="group rounded-2xl border border-gray-200 bg-white shadow-sm transition-colors hover:bg-gray-50/50 dark:border-neutral-700 dark:bg-neutral-900/60 dark:hover:bg-neutral-900"
    >
        <div class="p-4 sm:p-5">
            <div class="flex items-start gap-3">
                <Avatar class="mt-0.5 h-10 w-10 shrink-0 overflow-hidden rounded-xl">
                    <AvatarFallback class="rounded-xl bg-foreground text-background font-bold">
                        {{ authorInitials }}
                    </AvatarFallback>
                </Avatar>

                <div class="min-w-0 flex-1">
                    <header class="flex flex-wrap items-center gap-x-2 gap-y-1">
                        <span class="truncate text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ authorName }}
                        </span>
                        <span class="text-xs text-gray-500 dark:text-gray-400">· {{ formatDate(post.created_at) }}</span>

                        <Badge v-if="showDraft && post.is_published === false" variant="outline" class="text-[10px]">Draft</Badge>
                        <Badge
                            v-if="showRoleScope && post.role_scope"
                            variant="outline"
                            class="text-[10px]"
                        >
                            {{ roleScopeLabel(post.role_scope) }}
                        </Badge>
                    </header>

                    <h2 class="mt-3 text-base font-semibold leading-snug text-gray-900 dark:text-gray-100">
                        {{ post.title }}
                    </h2>

                    <p class="mt-2 whitespace-pre-wrap text-sm leading-relaxed text-gray-700 dark:text-gray-300">
                        {{ post.body }}
                    </p>

                    <div v-if="post.image_url" class="mt-4 overflow-hidden rounded-2xl border border-gray-200 bg-gray-50 dark:border-neutral-700 dark:bg-neutral-950">
                        <img
                            :src="post.image_url"
                            alt=""
                            class="block max-h-[520px] w-full object-cover"
                            loading="lazy"
                        />
                    </div>

                    <div class="mt-4 flex flex-wrap items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                        <span class="inline-flex items-center gap-1">
                            <ThumbsUp class="h-3.5 w-3.5" />
                            {{ post.reactions_count ?? 0 }} reactions
                        </span>
                        <span class="inline-flex items-center gap-1">
                            <MessageCircle class="h-3.5 w-3.5" />
                            {{ post.comments_count ?? 0 }} comments
                        </span>
                    </div>

                    <div v-if="enableReactions" class="mt-4 flex flex-wrap items-center gap-2">
                        <Button
                            v-for="reaction in reactions"
                            :key="reaction.key"
                            type="button"
                            size="sm"
                            variant="outline"
                            class="h-9 gap-1.5 rounded-full px-4 text-xs"
                            :class="post.user_reaction === reaction.key
                                ? 'border-brand bg-brand/10 text-brand dark:border-brand-light dark:bg-brand-light/10'
                                : ''"
                            @click="emit('react', { postId: post.id, type: reaction.key })"
                        >
                            <component :is="reaction.icon" class="h-3.5 w-3.5" />
                            {{ reaction.label }}
                        </Button>
                    </div>

                    <div v-if="enableCommentComposer" class="mt-5 space-y-2">
                        <div class="flex items-start gap-2">
                            <textarea
                                :value="commentDraft ?? ''"
                                :placeholder="commentPlaceholder ?? 'Write a comment...'"
                                rows="2"
                                class="min-h-[64px] flex-1 rounded-xl border border-gray-300 bg-white px-3 py-2 text-sm placeholder:text-gray-400 focus:border-brand focus:outline-none focus:ring-1 focus:ring-brand dark:border-neutral-700 dark:bg-neutral-900 dark:placeholder:text-gray-500"
                                @input="emit('update:commentDraft', ($event.target as HTMLTextAreaElement).value)"
                            />
                            <Button
                                type="button"
                                size="sm"
                                class="mt-1 h-9 rounded-full px-4"
                                @click="emit('submitComment', { postId: post.id })"
                            >
                                Comment
                            </Button>
                        </div>
                        <p class="text-[11px] text-gray-500 dark:text-gray-500">
                            Comments are visible to everyone who can see this announcement.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </article>
</template>
