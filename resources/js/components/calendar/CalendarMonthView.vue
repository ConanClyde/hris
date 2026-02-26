<script setup lang="ts">
import { addDays, differenceInDays, format, isSameDay, isSameMonth, startOfMonth, startOfWeek } from 'date-fns';
import { computed, inject } from 'vue';
import type { CalendarEventNormalized } from './types';
import type { UseCalendarReturn } from './useCalendar';

const calendar = inject<UseCalendarReturn & { onEventClick: (e: CalendarEventNormalized) => void }>('calendar');

if (!calendar) {
    throw new Error('CalendarMonthView must be used inside Calendar');
}

const weekStartsOn = 0;

const gridStart = computed(() => {
    const d = calendar.date.value;
    return startOfWeek(startOfMonth(d), { weekStartsOn });
});

const gridDays = computed(() => {
    const start = gridStart.value;
    return Array.from({ length: 42 }, (_, i) => addDays(start, i));
});

const weekDays = computed(() => {
    const start = startOfWeek(new Date(), { weekStartsOn });
    return Array.from({ length: 7 }, (_, i) => format(addDays(start, i), 'EEE'));
});

/** Multi-day event segment for one week: event + start/end column (0-6) and lane (row within bar area). */
interface MultiDaySegment {
    event: CalendarEventNormalized;
    startCol: number;
    endCol: number;
    lane: number;
}

/** Multi-day events as spanning bars, grouped by week index (0-5). */
const multiDaySegmentsByWeek = computed(() => {
    const start = gridStart.value;
    const events = calendar.events.value;
    const result: MultiDaySegment[][] = [[], [], [], [], [], []];

    for (const evt of events) {
        const startDayIdx = Math.max(0, differenceInDays(evt.start, start));
        const endDayIdx = Math.min(41, differenceInDays(evt.end, start));
        if (endDayIdx <= startDayIdx) continue; // single-day: show in day cell

        for (let w = 0; w < 6; w++) {
            const segStart = Math.max(0, startDayIdx - w * 7);
            const segEnd = Math.min(6, endDayIdx - w * 7);
            if (segStart > segEnd) continue;
            const weekSegs = result[w];
            const overlap = (a: { startCol: number; endCol: number }, b: { startCol: number; endCol: number }) =>
                a.startCol <= b.endCol && b.startCol <= a.endCol;
            let lane = 0;
            while (weekSegs.some((s) => s.lane === lane && overlap(s, { startCol: segStart, endCol: segEnd })))
                lane++;
            weekSegs.push({ event: evt, startCol: segStart, endCol: segEnd, lane });
        }
    }
    return result;
});

/** Single-day events only (for rendering inside day cells). */
function singleDayEventsForDay(day: Date) {
    return calendar.events.value.filter((evt) => {
        if (differenceInDays(evt.end, evt.start) > 0) return false; // multi-day: skip
        return isSameDay(evt.start, day);
    });
}

function isToday(date: Date) {
    return isSameDay(date, calendar.today);
}

function getWeekDays(weekIndex: number) {
    return gridDays.value.slice(weekIndex * 7, weekIndex * 7 + 7);
}
</script>

<template>
    <div class="h-full rounded-lg border border-border bg-card">
        <div class="grid grid-cols-7 text-sm">
            <div
                v-for="label in weekDays"
                :key="label"
                class="border-b border-r border-border p-2 text-center font-medium text-muted-foreground last:border-r-0"
            >
                {{ label }}
            </div>
            <!-- Each week is one block: bar row at top, then 7 day cells inside the same block -->
            <template v-for="(weekSegments, weekIndex) in multiDaySegmentsByWeek" :key="'week-' + weekIndex">
                <div
                    class="col-span-7 grid min-h-[100px] border-b border-border"
                    style="grid-template-columns: repeat(7, 1fr); grid-template-rows: auto 1fr;"
                >
                    <!-- Multi-day bar row: inside this week block, above the date cells -->
                    <div
                        v-if="weekSegments.length > 0"
                        class="col-span-7 grid gap-0.5 px-0.5 pt-1"
                        style="grid-template-columns: repeat(7, 1fr); grid-auto-rows: minmax(1.5rem, auto);"
                    >
                        <button
                            v-for="seg in weekSegments"
                            :key="seg.event.id + '-' + weekIndex + '-' + seg.startCol"
                            type="button"
                            class="rounded px-2 py-0.5 text-left text-xs text-white transition-opacity hover:opacity-90 min-w-0 overflow-hidden text-ellipsis whitespace-nowrap"
                            :style="{
                                gridColumn: `${seg.startCol + 1} / ${seg.endCol + 2}`,
                                gridRow: seg.lane + 1,
                                backgroundColor: seg.event.color || 'hsl(var(--muted))',
                            }"
                            :title="seg.event.title"
                            @click="calendar.onEventClick(seg.event)"
                        >
                            {{ seg.event.title }}
                        </button>
                    </div>
                    <!-- Day cells for this week: directly below the bar row, inside the same block -->
                    <div
                        v-for="day in getWeekDays(weekIndex)"
                        :key="day.toISOString()"
                        class="border-r border-border p-2 last:border-r-0"
                        :class="{
                            'bg-muted/30': !isSameMonth(day, calendar.date.value),
                        }"
                    >
                        <div
                            class="mb-1 flex h-7 w-7 items-center justify-center rounded-full text-sm"
                            :class="
                                isToday(day)
                                    ? 'bg-primary text-primary-foreground font-semibold'
                                    : isSameMonth(day, calendar.date.value)
                                      ? 'text-foreground'
                                      : 'text-muted-foreground'
                            "
                        >
                            {{ format(day, 'd') }}
                        </div>
                        <div class="space-y-1">
                            <button
                                v-for="evt in singleDayEventsForDay(day)"
                                :key="evt.id"
                                type="button"
                                class="block w-full rounded px-2 py-0.5 text-left text-xs text-white transition-opacity hover:opacity-90 break-words whitespace-normal"
                                :style="evt.color ? { backgroundColor: evt.color } : {}"
                                @click="calendar.onEventClick(evt)"
                            >
                                {{ evt.title }}
                            </button>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
