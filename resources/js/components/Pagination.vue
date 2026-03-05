<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps<{
    meta: {
        current_page: number;
        last_page: number;
        links: { url: string | null; label: string; active: boolean }[];
    };
}>();

const prevUrl = computed(() => {
    return props.meta.links[0]?.url || null;
});

const nextUrl = computed(() => {
    return props.meta.links[props.meta.links.length - 1]?.url || null;
});

const getPageUrl = (pageNumber: number) => {
    const link = props.meta.links.find(l => l.label === String(pageNumber));
    if (link && link.url) return link.url;
    
    // Fallback: extract url from any links
    const anyUrl = props.meta.links.find(l => l.url)?.url;
    if (!anyUrl) return '#';
    try {
        const url = new URL(anyUrl, window.location.origin);
        url.searchParams.set('page', String(pageNumber));
        return url.toString().replace(window.location.origin, '');
    } catch {
        return '#';
    }
};

const middleBoxes = computed(() => {
    const current = props.meta.current_page;
    const last = props.meta.last_page;
    const boxes = [];

    if (last <= 3) {
        for (let i = 1; i <= last; i++) {
            boxes.push({ type: 'page', label: String(i), page: i, active: i === current });
        }
    } else {
        if (current === 1 || current === last) {
            boxes.push({ type: 'page', label: '1', page: 1, active: current === 1 });
            boxes.push({ type: 'ellipsis', label: '...', active: false });
            boxes.push({ type: 'page', label: String(last), page: last, active: current === last });
        } else {
            boxes.push({ type: 'page', label: '1', page: 1, active: false });
            boxes.push({ type: 'page', label: String(current), page: current, active: true });
            boxes.push({ type: 'page', label: String(last), page: last, active: false });
        }
    }
    return boxes;
});
</script>

<template>
    <div v-if="meta.last_page > 1" class="flex flex-wrap items-center justify-end gap-2 text-sm mt-4">
        <Component
            :is="prevUrl ? Link : 'span'"
            :href="prevUrl"
            class="inline-flex h-9 items-center justify-center rounded-md border px-3 transition-colors"
            :class="prevUrl ? 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800' : 'border-gray-200 text-gray-400 dark:border-neutral-700 cursor-not-allowed select-none bg-gray-50/50 dark:bg-neutral-800/50'"
        >
            <span class="mr-1">&lt;</span> Previous
        </Component>

        <template v-for="(box, i) in middleBoxes" :key="i">
            <span
                v-if="box.type === 'ellipsis'"
                class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-200 px-3 text-gray-400 dark:border-neutral-700 select-none bg-gray-50/50 dark:bg-neutral-800/50"
            >
                {{ box.label }}
            </span>
            <Link
                v-else
                :href="getPageUrl(box.page!)"
                class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-3 transition-colors"
                :class="box.active ? 'border-brand bg-brand text-white dark:border-brand-light dark:bg-brand-light dark:text-gray-900 pointer-events-none' : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'"
            >
                {{ box.label }}
            </Link>
        </template>

        <Component
            :is="nextUrl ? Link : 'span'"
            :href="nextUrl"
            class="inline-flex h-9 items-center justify-center rounded-md border px-3 transition-colors"
            :class="nextUrl ? 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800' : 'border-gray-200 text-gray-400 dark:border-neutral-700 cursor-not-allowed select-none bg-gray-50/50 dark:bg-neutral-800/50'"
        >
            Next <span class="ml-1">&gt;</span>
        </Component>
    </div>
</template>
