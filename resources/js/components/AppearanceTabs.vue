<script setup lang="ts">
import { Monitor, Moon, Sun } from 'lucide-vue-next';
import { useAppearance } from '@/composables/useAppearance';

const { appearance, updateAppearance } = useAppearance();

const tabs = [
    { value: 'light', Icon: Sun, label: 'Light' },
    { value: 'dark', Icon: Moon, label: 'Dark' },
    { value: 'system', Icon: Monitor, label: 'System' },
] as const;
</script>

<template>
    <div
        class="inline-flex gap-1 rounded-lg border border-border bg-muted/50 p-1"
    >
        <button
            v-for="{ value, Icon, label } in tabs"
            :key="value"
            type="button"
            @click="updateAppearance(value)"
            :class="[
                'flex items-center rounded-md px-3.5 py-1.5 text-sm font-medium transition-colors',
                appearance === value
                    ? 'bg-primary text-primary-foreground shadow-sm'
                    : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground',
            ]"
        >
            <component :is="Icon" class="-ml-0.5 size-4" />
            <span class="ml-1.5">{{ label }}</span>
        </button>
    </div>
</template>
