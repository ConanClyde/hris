<script setup lang="ts">
import { ref, computed } from 'vue';
import { Eye, EyeOff } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        modelValue?: string;
        id?: string;
        name?: string;
        placeholder?: string;
        autocomplete?: string;
        required?: boolean;
        disabled?: boolean;
        readonly?: boolean;
        class?: string;
        tabindex?: number;
    }>(),
    {
        modelValue: '',
        autocomplete: 'current-password',
    }
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const showPassword = ref(false);
const inputType = computed(() =>
    showPassword.value ? 'text' : 'password'
);

const toggleVisibility = () => {
    showPassword.value = !showPassword.value;
};
</script>

<template>
    <div class="relative">
        <Input
            :id="id"
            :name="name"
            :type="inputType"
            :model-value="modelValue"
            :placeholder="placeholder"
            :autocomplete="autocomplete"
            :required="required"
            :disabled="disabled"
            :readonly="readonly"
            :tabindex="tabindex"
            :class="cn('pr-10', props.class)"
            @update:model-value="emit('update:modelValue', $event)"
        />
        <button
            type="button"
            class="cursor-pointer absolute right-3 top-1/2 -translate-y-1/2 p-1 text-gray-400 transition-colors hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300"
            aria-label="Toggle password visibility"
            @click="toggleVisibility"
        >
            <Eye v-show="!showPassword" class="size-4" />
            <EyeOff v-show="showPassword" class="size-4" />
        </button>
    </div>
</template>
