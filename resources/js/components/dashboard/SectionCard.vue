<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

defineProps<{
    title: string;
    subtitle?: string | null;
    contentClass?: string;
}>();
</script>

<template>
    <Card
        class="overflow-hidden border border-gray-200 dark:border-neutral-800"
    >
        <CardHeader class="border-b border-gray-200 dark:border-neutral-800">
            <CardTitle
                class="text-base font-semibold text-gray-900 dark:text-gray-100"
            >
                {{ title }}
            </CardTitle>
            <p v-if="subtitle" class="text-xs text-gray-500 dark:text-gray-400">
                {{ subtitle }}
            </p>
        </CardHeader>
        <CardContent :class="contentClass ?? 'p-4'">
            <slot />
        </CardContent>
    </Card>
</template>
