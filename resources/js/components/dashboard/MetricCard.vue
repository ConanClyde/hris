<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import type { Component } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

defineProps<{
    title: string;
    value?: string | number | null;
    helperText?: string | null;
    href?: string | null;
    linkText?: string | null;
    icon?: Component | null;
}>();
</script>

<template>
    <Card class="border border-gray-200 dark:border-neutral-800">
        <CardHeader class="pb-2">
            <CardTitle
                class="text-sm font-normal text-gray-500 dark:text-gray-400"
            >
                {{ title }}
            </CardTitle>
        </CardHeader>
        <CardContent>
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <p
                        class="text-2xl font-semibold text-gray-900 dark:text-gray-100"
                    >
                        {{ value ?? '—' }}
                    </p>
                    <p
                        v-if="helperText"
                        class="mt-1 text-xs text-gray-500 dark:text-gray-400"
                    >
                        {{ helperText }}
                    </p>
                    <Link
                        v-if="href"
                        :href="href"
                        class="mt-2 block text-xs text-brand transition-colors hover:text-brand-dark dark:text-brand-light dark:hover:text-brand-light/80"
                    >
                        {{ linkText ?? 'View →' }}
                    </Link>
                </div>
                <div
                    v-if="icon"
                    class="flex size-10 shrink-0 items-center justify-center rounded-md bg-brand/[0.08]"
                >
                    <component :is="icon" class="size-5 text-brand" />
                </div>
            </div>
        </CardContent>
    </Card>
</template>
