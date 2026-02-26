<script setup lang="ts">
import type { HTMLAttributes } from "vue"
import { useVModel } from "@vueuse/core"
import { cn } from "@/lib/utils"

const props = defineProps<{
  defaultValue?: string | number
  modelValue?: string | number
  class?: HTMLAttributes["class"]
}>()

const emits = defineEmits<{
  (e: "update:modelValue", payload: string | number): void
}>()

const modelValue = useVModel(props, "modelValue", emits, {
  passive: true,
  defaultValue: props.defaultValue,
})
</script>

<template>
  <input
    v-model="modelValue"
    data-slot="input"
    :class="cn(
      'h-10 w-full rounded-md border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder:text-gray-400 transition-all hover:border-gray-400 focus:outline-none focus:ring-1 focus:ring-brand focus:border-brand dark:border-gray-800 dark:bg-neutral-900 dark:text-white dark:placeholder:text-gray-500 dark:hover:border-gray-600 dark:focus:border-brand',
      'disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50',
      'file:border-0 file:bg-transparent file:text-sm file:font-medium',
      'aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive',
      props.class,
    )"
    style="color-scheme: light;"
  >
</template>

<style scoped>
input[data-slot="input"][type="date"] {
  color-scheme: light;
}

:global(.dark) input[data-slot="input"][type="date"] {
  color-scheme: dark;
}
</style>
