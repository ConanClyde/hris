<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

type PaginationLink = { url: string | null; label: string; active: boolean };

const props = withDefaults(
    defineProps<{
        meta?: {
            current_page: number;
            last_page: number;
            links: PaginationLink[];
        };
        links?: PaginationLink[];
    }>(),
    { meta: undefined, links: undefined },
);

/** Normalize to meta shape: support both meta and links-only props */
const resolvedMeta = computed(() => {
    if (props.meta) return props.meta;
    if (!props.links?.length) return null;
    const links = props.links;
    const activeLink = links.find((l) => l.active);
    const current = activeLink ? parseInt(activeLink.label, 10) : 1;
    const numericLabels = links
        .map((l) => parseInt(l.label, 10))
        .filter((n) => !Number.isNaN(n));
    const last = numericLabels.length ? Math.max(...numericLabels) : 1;
    return { current_page: current, last_page: last, links };
});

const prevUrl = computed(() => {
    const m = resolvedMeta.value;
    return m?.links[0]?.url || null;
});

const nextUrl = computed(() => {
    const m = resolvedMeta.value;
    if (!m) return null;
    return m.links[m.links.length - 1]?.url || null;
});

const getPageUrl = (pageNumber: number) => {
    const m = resolvedMeta.value;
    if (!m) return '#';
    const link = m.links.find((l) => l.label === String(pageNumber));
    if (link && link.url) return link.url;

    const anyUrl = m.links.find((l) => l.url)?.url;
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
    const m = resolvedMeta.value;
    if (!m) return [];
    const current = m.current_page;
    const last = m.last_page;
    const boxes = [];

    if (last <= 3) {
        for (let i = 1; i <= last; i++) {
            boxes.push({
                type: 'page',
                label: String(i),
                page: i,
                active: i === current,
            });
        }
    } else {
        if (current === 1 || current === last) {
            boxes.push({
                type: 'page',
                label: '1',
                page: 1,
                active: current === 1,
            });
            boxes.push({ type: 'ellipsis', label: '...', active: false });
            boxes.push({
                type: 'page',
                label: String(last),
                page: last,
                active: current === last,
            });
        } else {
            boxes.push({ type: 'page', label: '1', page: 1, active: false });
            boxes.push({
                type: 'page',
                label: String(current),
                page: current,
                active: true,
            });
            boxes.push({
                type: 'page',
                label: String(last),
                page: last,
                active: false,
            });
        }
    }
    return boxes;
});
</script>

<template>
    <div
        v-if="resolvedMeta && resolvedMeta.last_page > 1"
        class="mt-4 flex flex-wrap items-center justify-end gap-2 text-sm"
    >
        <Component
            :is="prevUrl ? Link : 'span'"
            :href="prevUrl"
            class="inline-flex h-9 items-center justify-center rounded-md border px-3 transition-colors"
            :class="
                prevUrl
                    ? 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'
                    : 'cursor-not-allowed border-gray-200 bg-gray-50/50 text-gray-400 select-none dark:border-neutral-700 dark:bg-neutral-800/50'
            "
        >
            <span class="mr-1">&lt;</span> Previous
        </Component>

        <template v-for="(box, i) in middleBoxes" :key="i">
            <span
                v-if="box.type === 'ellipsis'"
                class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border border-gray-200 bg-gray-50/50 px-3 text-gray-400 select-none dark:border-neutral-700 dark:bg-neutral-800/50"
            >
                {{ box.label }}
            </span>
            <Link
                v-else
                :href="getPageUrl(box.page!)"
                class="inline-flex h-9 min-w-9 items-center justify-center rounded-md border px-3 transition-colors"
                :class="
                    box.active
                        ? 'pointer-events-none border-brand bg-brand text-white dark:border-brand-light dark:bg-brand-light dark:text-gray-900'
                        : 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'
                "
            >
                {{ box.label }}
            </Link>
        </template>

        <Component
            :is="nextUrl ? Link : 'span'"
            :href="nextUrl"
            class="inline-flex h-9 items-center justify-center rounded-md border px-3 transition-colors"
            :class="
                nextUrl
                    ? 'border-gray-200 text-gray-700 hover:bg-gray-50 dark:border-neutral-700 dark:text-gray-300 dark:hover:bg-neutral-800'
                    : 'cursor-not-allowed border-gray-200 bg-gray-50/50 text-gray-400 select-none dark:border-neutral-700 dark:bg-neutral-800/50'
            "
        >
            Next <span class="ml-1">&gt;</span>
        </Component>
    </div>
</template>
