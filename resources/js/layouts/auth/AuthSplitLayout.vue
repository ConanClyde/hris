<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import { Moon, Sun } from 'lucide-vue-next';
import type { Component } from 'vue';
import { useAppearance } from '@/composables/useAppearance';

export type AuthFeature = {
    icon: Component;
    title: string;
    description: string;
};

defineProps<{
    title?: string;
    description?: string;
    backHref?: string;
    showProgress?: boolean;
    progressStep?: number;
    totalSteps?: number;
    headerLink?: { href: string; label: string };
    stepLabel?: string;
    features?: AuthFeature[];
}>();

const page = usePage();
const appName = (page.props.name as string) || 'HRIS';
const { updateAppearance } = useAppearance();
const isDark = () => document.documentElement.classList.contains('dark');

const toggleDark = () => {
    updateAppearance(isDark() ? 'light' : 'dark');
};
</script>

<template>
    <div class="flex min-h-dvh flex-col lg:flex-row">
        <aside
            class="relative hidden flex-1 flex-col justify-between border-r border-gray-200 bg-gradient-to-br from-brand via-brand-dark to-brand-light p-6 lg:flex lg:p-10"
        >
            <div class="flex items-center gap-3">
                <div
                    class="flex size-10 items-center justify-center rounded-lg border border-white/40 bg-white/10"
                >
                    <span class="text-xl font-bold text-white">H</span>
                </div>
                <span class="text-xl font-semibold text-white">{{
                    appName
                }}</span>
            </div>

            <div class="mt-auto">
                <ul v-if="features?.length" class="space-y-4">
                    <li
                        v-for="feature in features"
                        :key="feature.title"
                        class="flex items-start gap-4"
                    >
                        <div
                            class="flex size-10 shrink-0 items-center justify-center rounded-lg border border-white/40 bg-white/10"
                        >
                            <component
                                :is="feature.icon"
                                class="size-5 text-white"
                            />
                        </div>
                        <div>
                            <p class="text-base font-semibold text-white">
                                {{ feature.title }}
                            </p>
                            <p class="text-sm leading-relaxed text-white/80">
                                {{ feature.description }}
                            </p>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>

        <main
            class="relative flex min-h-0 w-full flex-1 flex-col items-center justify-center overflow-y-auto bg-white px-6 pt-20 pb-[env(safe-area-inset-bottom)] transition-colors duration-300 sm:px-12 lg:w-1/2 lg:overflow-visible lg:px-16 lg:pt-0 xl:px-24 dark:bg-neutral-950"
        >
            <!-- Mobile header (visible only on small screens) -->
            <div
                class="absolute top-[max(1.5rem,env(safe-area-inset-top))] right-6 left-6 flex items-center justify-between lg:hidden"
            >
                <div class="flex items-center gap-3">
                    <div
                        class="flex size-8 items-center justify-center rounded-lg bg-brand"
                    >
                        <span class="text-sm font-bold text-white">H</span>
                    </div>
                    <span
                        class="text-lg font-semibold text-gray-900 dark:text-gray-100"
                        >{{ appName }}</span
                    >
                </div>
                <div class="flex items-center gap-2">
                    <Link
                        v-if="headerLink"
                        :href="headerLink.href"
                        class="cursor-pointer text-sm font-medium text-brand transition-colors hover:text-brand-dark dark:text-blue-400 dark:hover:text-blue-300"
                    >
                        {{ headerLink.label }}
                    </Link>
                    <button
                        type="button"
                        class="cursor-pointer rounded-md p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-neutral-800 dark:hover:text-gray-100"
                        aria-label="Toggle dark mode"
                        @click="toggleDark"
                    >
                        <Moon class="size-5 dark:hidden" />
                        <Sun class="hidden size-5 dark:block" />
                    </button>
                </div>
            </div>

            <!-- Desktop header (visible only on large screens) -->
            <div
                class="absolute top-6 right-6 hidden items-center gap-2 lg:top-10 lg:right-10 lg:flex"
            >
                <Link
                    v-if="headerLink"
                    :href="headerLink.href"
                    class="cursor-pointer text-sm font-medium text-brand transition-colors hover:text-brand-dark dark:text-blue-400 dark:hover:text-blue-300"
                >
                    {{ headerLink.label }}
                </Link>
                <button
                    type="button"
                    class="cursor-pointer rounded-md p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-900 dark:text-gray-400 dark:hover:bg-neutral-800 dark:hover:text-gray-100"
                    aria-label="Toggle dark mode"
                    @click="toggleDark"
                >
                    <Moon class="size-5 dark:hidden" />
                    <Sun class="hidden size-5 dark:block" />
                </button>
            </div>

            <div class="w-full max-w-md min-w-0">
                <h1
                    v-if="title"
                    class="mb-2 text-center text-2xl font-semibold text-gray-900 dark:text-gray-100"
                >
                    {{ title }}
                </h1>
                <p
                    v-if="stepLabel"
                    class="mb-2 text-center text-base font-medium text-gray-500 dark:text-gray-400"
                >
                    {{ stepLabel }}
                </p>
                <div
                    v-if="showProgress && (progressStep ?? 0) >= 1"
                    class="mb-4 flex gap-1"
                >
                    <div
                        v-for="i in totalSteps ?? 4"
                        :key="i"
                        class="h-1.5 flex-1 rounded-sm transition-colors"
                        :class="
                            i <= (progressStep ?? 0)
                                ? 'bg-gray-900 dark:bg-gray-100'
                                : 'bg-gray-200 dark:bg-neutral-700'
                        "
                    />
                </div>
                <p
                    v-if="description"
                    class="mb-6 text-center text-base text-gray-600 dark:text-gray-400"
                >
                    {{ description }}
                </p>
                <div class="py-1">
                    <slot />
                </div>
            </div>
        </main>
    </div>
</template>
