<script setup lang="ts">
import { Info } from 'lucide-vue-next';
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import { Card, CardHeader, CardTitle, CardContent } from '@/components/ui/card';

const isOpen = ref(false);

function toggle() {
    isOpen.value = !isOpen.value;
}

function close() {
    isOpen.value = false;
}

const legendItems = [
    { color: 'bg-emerald-500', label: 'Approved Leave' },
    { color: 'bg-amber-500', label: 'Pending Leave' },
    { color: 'bg-blue-500', label: 'Approved Training' },
    { color: 'bg-sky-400', label: 'Pending Training' },
    { color: 'bg-red-500', label: 'Holiday' },
];
</script>

<template>
    <div class="relative">
        <Button
            type="button"
            variant="outline"
            size="sm"
            class="h-9 gap-2"
            @click="toggle"
        >
            <Info class="w-4 h-4" />
            Legend
        </Button>

        <Card
            v-if="isOpen"
            class="absolute right-0 top-full z-50 mt-2 w-56 gap-0 border border-border py-0"
            @click.stop
        >
            <CardHeader class="border-b border-border p-4 !pb-4">
                <CardTitle class="text-sm font-semibold text-foreground m-0">Calendar Legend</CardTitle>
            </CardHeader>
            <CardContent class="space-y-3 p-4 pt-4">
                <div
                    v-for="item in legendItems"
                    :key="item.label"
                    class="flex items-center gap-3"
                >
                    <span :class="['h-3 w-3 shrink-0 rounded-full', item.color]" />
                    <span class="text-sm text-foreground">{{ item.label }}</span>
                </div>
            </CardContent>
        </Card>

        <div
            v-if="isOpen"
            class="fixed inset-0 z-40"
            @click="close"
        />
    </div>
</template>
