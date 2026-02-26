<script setup lang="ts">
import { provide, ref, watch } from 'vue';
import { Spinner } from '@/components/ui/spinner';
import CalendarDayView from './CalendarDayView.vue';
import CalendarMonthView from './CalendarMonthView.vue';
import CalendarToolbar from './CalendarToolbar.vue';
import CalendarWeekView from './CalendarWeekView.vue';
import CalendarYearView from './CalendarYearView.vue';
import type { CalendarView, CalendarEventNormalized, CalendarEventInput } from './types';
import { useCalendar } from './useCalendar';

function normalizeEvent(evt: CalendarEventInput): CalendarEventNormalized {
    const start = evt.start instanceof Date ? evt.start : new Date(evt.start);
    const end = evt.end
        ? evt.end instanceof Date
            ? evt.end
            : new Date(evt.end)
        : new Date(start.getTime());
    return {
        id: evt.id,
        title: evt.title,
        start,
        end,
        allDay: evt.allDay ?? true,
        color: evt.backgroundColor,
        extendedProps: evt.extendedProps,
    };
}

const props = withDefaults(
    defineProps<{
        events?: CalendarEventInput[];
        loading?: boolean;
        initialView?: CalendarView;
        initialDate?: Date;
    }>(),
    {
        events: () => [],
        loading: false,
        initialView: 'month',
        initialDate: undefined,
    }
);

const emit = defineEmits<{
    (e: 'dates-change', payload: { start: Date; end: Date }): void;
    (e: 'event-click', payload: CalendarEventNormalized): void;
}>();

const normalizedEvents = ref<CalendarEventNormalized[]>(
    props.events.map(normalizeEvent)
);

watch(
    () => props.events,
    (next) => {
        normalizedEvents.value = (next || []).map(normalizeEvent);
    },
    { deep: true }
);

const calendar = useCalendar({
    initialDate: props.initialDate ?? new Date(),
    initialView: props.initialView,
    events: normalizedEvents,
});

function onEventClick(event: CalendarEventNormalized) {
    emit('event-click', event);
}

watch(
    () => calendar.visibleRange.value,
    (range) => {
        emit('dates-change', { start: range.start, end: range.end });
    },
    { immediate: true, deep: true }
);

provide('calendar', {
    ...calendar,
    onEventClick,
});
</script>

<template>
    <div class="flex flex-col h-full">
        <CalendarToolbar />
        <div class="relative flex-1 overflow-auto p-4 min-h-[400px]">
            <CalendarDayView v-if="calendar.view.value === 'day'" />
            <CalendarWeekView v-else-if="calendar.view.value === 'week'" />
            <CalendarMonthView v-else-if="calendar.view.value === 'month'" />
            <CalendarYearView v-else-if="calendar.view.value === 'year'" />
            <div
                v-if="loading"
                class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-3 bg-background/80 backdrop-blur-[2px]"
                aria-live="polite"
                aria-busy="true"
            >
                <Spinner class="size-8 text-muted-foreground" />
                <span class="text-sm text-muted-foreground">Loading…</span>
            </div>
        </div>
    </div>
</template>
