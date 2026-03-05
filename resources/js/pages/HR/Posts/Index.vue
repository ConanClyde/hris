<script setup lang="ts">
import { Head, useForm, usePage } from '@inertiajs/vue3';
import { useDebounceFn } from '@vueuse/core';
import { computed, onMounted, ref, watch } from 'vue';
import { toast } from 'vue-sonner';
import Pagination from '@/components/Pagination.vue';
import ComposerTrigger from '@/components/posts/ComposerTrigger.vue';
import PostCard from '@/components/posts/PostCard.vue';
import PostComposer from '@/components/posts/PostComposer.vue';
import PostsSearchBar from '@/components/posts/PostsSearchBar.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { useBroadcasting } from '@/composables/useBroadcasting';
import AppLayout from '@/layouts/AppLayout.vue';
import hr from '@/routes/hr';
import type { BreadcrumbItem } from '@/types';

type PostItem = {
    id: number;
    title: string;
    body: string;
    image_url?: string | null;
    role_scope: string;
    is_published: boolean;
    comments_count: number;
    reactions_count: number;
    author?: {
        id: number;
        name?: string;
        first_name?: string;
        last_name?: string;
    } | null;
    created_at: string;
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

const page = usePage();
const authUser = computed(() => page.props.auth?.user as { first_name?: string; last_name?: string; name?: string; avatar?: string } | undefined);
const userFirstName = computed(() => authUser.value?.first_name || authUser.value?.name?.split(' ')[0] || 'Alvin');
const userAvatar = computed(() => authUser.value?.avatar);
const userInitials = computed(() => {
    const first = authUser.value?.first_name?.[0] || '';
    const last = authUser.value?.last_name?.[0] || '';
    const initials = (first + last).toUpperCase();
    return initials || 'HR';
});

watch(
    () => props.posts,
    (next) => {
        posts.value = next;
    }
);

const searchForm = useForm({
    search: props.filters?.search ?? '',
});

const createForm = useForm({
    title: '',
    body: '',
    role_scope: 'employee',
    image: null as File | null,
    expires_at: null as string | null,
});

const createModalOpen = ref(false);

function prependPost(post: PostItem) {
    const exists = posts.value.data.some(p => p.id === post.id);
    if (exists) return;
    posts.value.data.unshift(post);
    posts.value.data = posts.value.data.slice(0, 10);
}

function submitCreate() {
    createForm.post(hr.posts.store.url(), {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.success('Post created.');
            createModalOpen.value = false;
        },
        onFinish: async () => {
            try {
                const res = await fetch(hr.posts.index().url + '?only=latest_post', {
                    headers: { 'Accept': 'application/json' },
                });
                if (!res.ok) return;
                const json = await res.json();
                const post = json?.props?.latestPost as PostItem | undefined;
                if (post && typeof post.id === 'number') {
                    prependPost(post);
                }
            } catch {
                // ignore
            }
            createForm.reset('title', 'body', 'image', 'expires_at');
        },
    });
}

const { setupPostListeners, lastPostEvent } = useBroadcasting();

const hasPosts = computed(() => posts.value.data.length > 0);

function submitSearch() {
    searchForm.get(hr.posts.index().url, {
        preserveState: true,
        replace: true,
    });
}

const submitSearchDebounced = useDebounceFn(() => {
    submitSearch();
}, 300);

watch(
    () => searchForm.search,
    () => {
        submitSearchDebounced();
    }
);

 onMounted(() => {
     setupPostListeners('hr');
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

        if (evt.type === 'reaction_updated') {
            const post = posts.value.data.find(p => p.id === evt.post_id);
            if (!post) return;
            post.reactions_count = evt.reactions_count;
            return;
        }

        if (evt.type === 'comment_created') {
            const post = posts.value.data.find(p => p.id === evt.post_id);
            if (!post) return;
            post.comments_count = evt.comments_count;
        }
    }
);
</script>

<template>
    <Head title="Announcements" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="mx-auto w-full max-w-7xl space-y-6 p-4 pt-8">
            <div class="mx-auto max-w-2xl space-y-4">
                <main class="space-y-4">
                    <PostsSearchBar
                        v-model="searchForm.search"
                        placeholder="Search announcements"
                    />

                    <ComposerTrigger
                        :avatar="userAvatar"
                        :initials="userInitials"
                        :placeholder="`What's on your mind, ${userFirstName}?`"
                        @click="createModalOpen = true"
                    />

                    <Dialog :open="createModalOpen" @update:open="createModalOpen = $event">
                        <DialogContent class="sm:max-w-2xl">
                            <DialogHeader>
                                <DialogTitle>Create announcement</DialogTitle>
                                <DialogDescription>
                                    Share an announcement with an optional image and expiration date.
                                </DialogDescription>
                            </DialogHeader>

                            <PostComposer
                                actor-initials="HR"
                                :title="createForm.title"
                                :body="createForm.body"
                                :role-scope="createForm.role_scope"
                                :image="createForm.image"
                                :expires-at="createForm.expires_at"
                                :processing="createForm.processing"
                                :role-scope-options="[
                                    { value: 'employee', label: 'Employees only' },
                                    { value: 'all', label: 'All users' },
                                    { value: 'hr', label: 'HR only' },
                                ]"
                                @update:title="(v) => (createForm.title = v)"
                                @update:body="(v) => (createForm.body = v)"
                                @update:roleScope="(v) => (createForm.role_scope = v)"
                                @update:image="(v) => (createForm.image = v)"
                                @update:expiresAt="(v) => (createForm.expires_at = v)"
                                @submit="submitCreate"
                            />
                        </DialogContent>
                    </Dialog>

                    <div v-if="hasPosts" class="space-y-4">
                        <div
                            v-for="post in posts.data"
                            :key="post.id"
                            :id="`post-${post.id}`"
                            class="scroll-mt-24"
                        >
                            <PostCard
                                :post="post"
                                :show-role-scope="true"
                                :show-pinned="false"
                                :show-draft="true"
                            />
                        </div>
                    </div>

                    <div
                        v-else
                        class="rounded-2xl border border-dashed border-gray-200 bg-gray-50 p-10 text-center text-sm text-gray-500 dark:border-neutral-700 dark:bg-neutral-900/50 dark:text-gray-400"
                    >
                        No announcements yet.
                    </div>

                    <Pagination :meta="posts" />
                </main>
            </div>
        </div>
    </AppLayout>
</template>

