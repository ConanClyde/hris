<script setup lang="ts">
import {
    differenceInMinutes,
    endOfDay,
    format,
    setHours,
    startOfDay,
} from 'date-fns';
import { computed, inject } from 'vue';
import type { CalendarEventNormalized } from './types';
import type { UseCalendarReturn } from './useCalendar';

const calendar = inject<
    UseCalendarReturn & { onEventClick: (e: CalendarEventNormalized) => void }
>('calendar');

if (!calendar) {
    throw new Error('CalendarDayView must be used inside Calendar');
}

const hours = Array.from({ length: 24 }, (_, i) => i);
const ROW_HEIGHT_PX = 56;
const totalHeight = 24 * ROW_HEIGHT_PX;

const dayEvents = computed(() => {
    const d = calendar!.date.value;
    const dayStart = startOfDay(d);
    const dayEnd = endOfDay(d);
    return calendar!.events.value.filter(
        (evt) =>
            (evt.start >= dayStart && evt.start <= dayEnd) ||
            (evt.end >= dayStart && evt.end <= dayEnd) ||
            (evt.start <= dayStart && evt.end >= dayEnd),
    );
});

function eventStyle(evt: CalendarEventNormalized) {
    const d = calendar!.date.value;
    const dayStart = startOfDay(d);
    const dayEnd = endOfDay(d);
    const start = evt.start < dayStart ? dayStart : evt.start;
    const end = evt.end > dayEnd ? dayEnd : evt.end;
    const totalMins = 24 * 60;
    const topPct = (differenceInMinutes(start, dayStart) / totalMins) * 100;
    const heightPct = Math.max(
        2,
        (differenceInMinutes(end, start) / totalMins) * 100,
    );
    return {
        top: `${topPct}%`,
        height: `${heightPct}%`,
        backgroundColor: evt.color || 'hsl(var(--muted))',
    };
}
</script>

<template>
    <div class="h-full overflow-auto rounded-lg border border-border bg-card">
        <div class="mb-4 text-center text-lg font-semibold text-foreground">
            {{ format(calendar.date.value, 'EEEE, MMMM d, yyyy') }}
        </div>
        <div class="grid grid-cols-[64px_1fr] gap-0">
            <div class="flex flex-col">
                <div
                    v-for="hour in hours"
                    :key="hour"
                    class="border-b border-border py-1 pr-2 text-right text-xs text-muted-foreground"
                    :style="{ height: ROW_HEIGHT_PX + 'px' }"
                >
                    {{ format(setHours(new Date(0), hour), 'ha') }}
                </div>
            </div>
            <div
                class="relative border-l border-border"
                :style="{
                    height: totalHeight + 'px',
                    minHeight: totalHeight + 'px',
                }"
            >
                <div
                    v-for="evt in dayEvents"
                    :key="evt.id"
                    class="absolute right-1 left-1 cursor-pointer overflow-hidden rounded px-2 py-1 text-sm text-white hover:opacity-90"
                    :style="eventStyle(evt)"
                    @click="calendar.onEventClick(evt)"
                >
                    {{ evt.title }}
                </div>
            </div>
        </div>
    </div>
</template>
