<script setup lang="ts">
import {
    addDays,
    differenceInMinutes,
    endOfDay,
    format,
    isSameDay,
    isWithinInterval,
    setHours,
    startOfDay,
    startOfWeek,
} from 'date-fns';
import { computed, inject } from 'vue';
import type { CalendarEventNormalized } from './types';
import type { UseCalendarReturn } from './useCalendar';

const calendar = inject<
    UseCalendarReturn & { onEventClick: (e: CalendarEventNormalized) => void }
>('calendar');

if (!calendar) {
    throw new Error('CalendarWeekView must be used inside Calendar');
}

const weekStartsOn = 0;
const hours = Array.from({ length: 24 }, (_, i) => i);
const ROW_HEIGHT_PX = 48;

const weekDays = computed(() => {
    const start = startOfWeek(calendar!.date.value, { weekStartsOn });
    return Array.from({ length: 7 }, (_, i) => addDays(start, i));
});

const dayHeaderLabels = computed(() =>
    weekDays.value.map((d) => ({
        date: d,
        short: format(d, 'EEE'),
        num: format(d, 'd'),
    })),
);

function eventsForDay(day: Date) {
    const dayStart = startOfDay(day);
    const dayEnd = endOfDay(day);
    return calendar!.events.value.filter(
        (evt) =>
            isWithinInterval(evt.start, { start: dayStart, end: dayEnd }) ||
            isWithinInterval(evt.end, { start: dayStart, end: dayEnd }) ||
            (evt.start <= dayStart && evt.end >= dayEnd),
    );
}

function eventsStartingInHour(day: Date, hour: number) {
    const dayStart = startOfDay(day);
    const hourStart = setHours(dayStart, hour);
    const hourEnd = setHours(dayStart, hour + 1);
    return eventsForDay(day).filter(
        (evt) => evt.start >= hourStart && evt.start < hourEnd,
    );
}

function eventBlockStyle(
    evt: CalendarEventNormalized,
    day: Date,
    hour: number,
) {
    const dayStart = startOfDay(day);
    const hourStart = setHours(dayStart, hour);
    const hourEnd = setHours(dayStart, hour + 1);
    const start = evt.start < hourStart ? hourStart : evt.start;
    const end = evt.end > hourEnd ? hourEnd : evt.end;
    const top = differenceInMinutes(start, hourStart);
    const duration = differenceInMinutes(end, start);
    return {
        top: `${top}px`,
        height: `${Math.max(20, (duration / 60) * ROW_HEIGHT_PX)}px`,
        backgroundColor: evt.color || 'hsl(var(--muted))',
    };
}

function isToday(date: Date) {
    return isSameDay(date, calendar!.today);
}
</script>

<template>
    <div class="overflow-auto rounded-lg border border-border bg-card">
        <div
            class="grid min-w-[800px]"
            :style="{ gridTemplateColumns: '64px repeat(7, 1fr)' }"
        >
            <div
                class="border-r border-b border-border p-2 text-center text-sm text-muted-foreground"
            />
            <div
                v-for="hd in dayHeaderLabels"
                :key="hd.date.toISOString()"
                class="border-r border-b border-border p-2 text-center text-sm last:border-r-0"
                :class="
                    isToday(hd.date)
                        ? 'bg-primary/10 font-semibold text-foreground'
                        : 'text-foreground'
                "
            >
                <div>{{ hd.short }}</div>
                <div class="text-lg font-medium">{{ hd.num }}</div>
            </div>
            <template v-for="hour in hours" :key="hour">
                <div
                    class="border-r border-b border-border py-1 pr-2 text-right text-xs text-muted-foreground"
                    :style="{ height: ROW_HEIGHT_PX + 'px' }"
                >
                    {{ format(setHours(new Date(0), hour), 'ha') }}
                </div>
                <div
                    v-for="day in weekDays"
                    :key="`${hour}-${day.toISOString()}`"
                    class="relative overflow-visible border-r border-b border-border last:border-r-0"
                    :style="{
                        height: ROW_HEIGHT_PX + 'px',
                        minHeight: ROW_HEIGHT_PX + 'px',
                    }"
                    :class="isToday(day) ? 'bg-primary/5' : ''"
                >
                    <div
                        v-for="evt in eventsStartingInHour(day, hour)"
                        :key="evt.id"
                        class="absolute right-0.5 left-0.5 cursor-pointer overflow-hidden rounded px-2 py-0.5 text-xs text-white hover:opacity-90"
                        :style="eventBlockStyle(evt, day, hour)"
                        @click="calendar.onEventClick(evt)"
                    >
                        {{ evt.title }}
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>
