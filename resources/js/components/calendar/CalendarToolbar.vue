<script setup lang="ts">
import { format } from 'date-fns';
import { computed, inject } from 'vue';
import { Button } from '@/components/ui/button';
import type { CalendarView } from './types';
import type { UseCalendarReturn } from './useCalendar';

const calendar = inject<UseCalendarReturn>('calendar');

if (!calendar) {
    throw new Error('CalendarToolbar must be used inside Calendar');
}

const viewLabels: { value: CalendarView; label: string }[] = [
    { value: 'day', label: 'Day' },
    { value: 'week', label: 'Week' },
    { value: 'month', label: 'Month' },
    { value: 'year', label: 'Year' },
];

const currentTitle = computed(() => {
    const d = calendar.date.value;
    switch (calendar.view.value) {
        case 'day':
            return format(d, 'MMMM d, yyyy');
        case 'week':
            return `${format(calendar.visibleRange.value.start, 'MMM d')} – ${format(calendar.visibleRange.value.end, 'MMM d, yyyy')}`;
        case 'month':
            return format(d, 'MMMM yyyy');
        case 'year':
            return format(d, 'yyyy');
        default:
            return format(d, 'MMMM yyyy');
    }
});
</script>

<template>
    <div
        class="grid shrink-0 grid-cols-1 items-center gap-4 border-b border-border bg-card p-4 md:grid-cols-3"
    >
        <div
            class="flex items-center gap-2 justify-self-center md:justify-self-start"
        >
            <div class="inline-flex rounded-md border border-border bg-card">
                <button
                    type="button"
                    @click="calendar.goPrev"
                    class="flex h-10 w-10 items-center justify-center rounded-l-md border-r border-border text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                >
                    <svg
                        class="h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <polyline points="15 18 9 12 15 6" />
                    </svg>
                </button>
                <button
                    type="button"
                    @click="calendar.goNext"
                    class="flex h-10 w-10 items-center justify-center rounded-r-md text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                >
                    <svg
                        class="h-4 w-4"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <polyline points="9 18 15 12 9 6" />
                    </svg>
                </button>
            </div>
            <Button variant="outline" size="sm" @click="calendar.goToday">
                Today
            </Button>
        </div>

        <div class="text-center text-sm font-medium text-foreground">
            {{ currentTitle }}
        </div>

        <div
            class="flex items-center gap-2 justify-self-center md:justify-self-end"
        >
            <div class="inline-flex rounded-md border border-border bg-card">
                <button
                    v-for="(v, index) in viewLabels"
                    :key="v.value"
                    type="button"
                    @click="calendar.setView(v.value)"
                    :aria-current="
                        calendar.view.value === v.value ? 'true' : undefined
                    "
                    :class="[
                        'px-4 py-2 text-sm font-medium transition-colors',
                        calendar.view.value === v.value
                            ? 'bg-muted text-foreground'
                            : 'text-muted-foreground hover:bg-muted hover:text-foreground',
                        index === 0
                            ? 'rounded-l-md border-r border-border'
                            : '',
                        index === viewLabels.length - 1
                            ? 'rounded-r-md'
                            : 'border-r border-border',
                    ]"
                >
                    {{ v.label }}
                </button>
            </div>
        </div>
    </div>
</template>
