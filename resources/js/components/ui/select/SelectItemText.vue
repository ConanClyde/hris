<script setup lang="ts">
import type { SelectItemTextProps } from "reka-ui"
import { SelectItemText } from "reka-ui"

const props = defineProps<SelectItemTextProps>()
</script>

<template>
  <SelectItemText
    data-slot="select-item-text"
    class="min-w-0 truncate"
    v-bind="props"
  >
    <slot />
  </SelectItemText>
</template>
