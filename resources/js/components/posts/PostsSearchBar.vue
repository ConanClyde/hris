<script setup lang="ts">
import { Input } from '@/components/ui/input';

defineProps<{
    modelValue: string;
    placeholder?: string;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();
</script>

<template>
    <div class="flex items-center">
        <Input
            :model-value="modelValue"
            type="search"
            :placeholder="placeholder ?? 'Search...'"
            class="h-10 rounded-full"
            @update:model-value="(v) => emit('update:modelValue', String(v))"
        />
    </div>
</template>
