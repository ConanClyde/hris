<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { computed, onMounted, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import Pagination from '@/components/Pagination.vue';
import PinnedPostsSidebar from '@/components/posts/PinnedPostsSidebar.vue';
import PostCard from '@/components/posts/PostCard.vue';
import PostsSearchBar from '@/components/posts/PostsSearchBar.vue';
import { useBroadcasting } from '@/composables/useBroadcasting';
import AppLayout from '@/layouts/AppLayout.vue';
import employee from '@/routes/employee';
import type { BreadcrumbItem } from '@/types';

type PostItem = {
    id: number;
    title: string;
    body: string;
    image_url?: string | null;
    role_scope: string;
    created_at: string;
    author?: {
        id: number;
        name?: string;
        first_name?: string;
        last_name?: string;
    } | null;
    comments_count: number;
    reactions_count: number;
    user_reaction?: string | null;
};

type PaginatedData = {
    data: PostItem[];
    current_page: number;
    last_page: number;
    links: { url: string | null; label: string; active: boolean }[];
};

const props = defineProps<{
    posts: PaginatedData;
    filters?: { search?: string };
}>();

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Announcements' }];

const posts = ref<PaginatedData>(props.posts);
const search = ref(props.filters?.search ?? '');
const commentDrafts = ref<Record<number, string>>({});

const { setupPostListeners, lastPostEvent } = useBroadcasting();

const hasPosts = computed(() => posts.value.data.length > 0);

const pinnedPosts = computed(() => [] as PostItem[]);

function submitSearch() {
    router.get(employee.posts.index().url, { search: search.value || undefined }, {
        preserveState: true,
        replace: true,
    });
}

const submitSearchDebounced = useDebounceFn(() => {
    submitSearch();
}, 300);

watch(
    () => search.value,
    () => {
        submitSearchDebounced();
    }
);

async function react(postId: number, type: string) {
    try {
        const post = posts.value.data.find(p => p.id === postId);
        if (post) {
            post.user_reaction = type;
        }

        await fetch(employee.posts.react.url({ post: postId }), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ type }),
        });
    } catch (e) {
        console.error('Failed to react:', e);
    }
}

async function submitComment(postId: number) {
    const body = commentDrafts.value[postId] || '';
    if (!body.trim()) return;

    try {
        const post = posts.value.data.find(p => p.id === postId);
        if (post) {
            post.comments_count += 1;
        }

        await fetch(employee.posts.comment.url({ post: postId }), {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ body }),
        });

        commentDrafts.value[postId] = '';
    } catch (e) {
        console.error('Failed to post comment:', e);
    }
}

onMounted(() => {
    setupPostListeners('employee');
});

watch(
    () => lastPostEvent.value,
    (evt) => {
        if (!evt) return;

        if (evt.type === 'post_created') {
            const incoming = evt.post;
            const exists = posts.value.data.some(p => p.id === incoming.id);
            if (!exists) {
                posts.value.data.unshift(incoming as any);
                posts.value.data = posts.value.data.slice(0, 10);
            }

            toast.info(`New announcement: ${incoming.title}`);
            return;
        }

        const post = posts.value.data.find(p => p.id === evt.post_id);
        if (!post) return;

        if (evt.type === 'reaction_updated') {
            post.reactions_count = evt.reactions_count;
        }

        if (evt.type === 'comment_created') {
            post.comments_count = evt.comments_count;
        }
    }
);
</script>

<template>
    <Head title="Announcements" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-4">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div class="space-y-1">
                    <h1 class="text-2xl font-semibold tracking-tight text-gray-900 dark:text-gray-100">Announcements</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Stay up to date with HR and admin posts.
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-[260px_1fr_320px]">
                <aside class="hidden lg:block">
                    <div class="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm dark:border-neutral-700 dark:bg-neutral-900/60">
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-gray-100">Quick filters</h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use search to find announcements.</p>
                        <div class="mt-4 space-y-2 text-xs text-gray-600 dark:text-gray-300">
                            <div class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 dark:border-neutral-700 dark:bg-neutral-900">All announcements</div>
                            <div class="rounded-xl border border-gray-200 bg-gray-50 px-3 py-2 dark:border-neutral-700 dark:bg-neutral-900">Pinned appear first</div>
                        </div>
                    </div>
                </aside>

                <main class="space-y-4">
                    <PostsSearchBar
                        v-model="search"
                        placeholder="Search announcements"
                    />

                    <div v-if="hasPosts" class="space-y-4">
                        <div
                            v-for="post in posts.data"
                            :key="post.id"
                            :id="`post-${post.id}`"
                            class="scroll-mt-24"
                        >
                            <PostCard
                                :post="post"
                                :show-pinned="true"
                                :enable-reactions="true"
                                :enable-comment-composer="true"
                                :comment-draft="commentDrafts[post.id]"
                                @react="({ postId, type }) => react(postId, type)"
                                @update:commentDraft="(v) => (commentDrafts[post.id] = v)"
                                @submitComment="({ postId }) => submitComment(postId)"
                            />
                        </div>
                    </div>

                    <div
                        v-else
                        class="rounded-2xl border border-gray-200 bg-gray-50 p-10 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-900/50 dark:text-gray-400"
                    >
                        No announcements yet.
                    </div>

                    <Pagination :meta="posts" />
                </main>

                <aside class="hidden lg:block">
                    <PinnedPostsSidebar :pinned-posts="pinnedPosts" />
                </aside>
            </div>
        </div>
    </AppLayout>
</template>

