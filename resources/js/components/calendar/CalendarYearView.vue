<script setup lang="ts">
import {
    addDays,
    addMonths,
    format,
    isSameDay,
    isSameMonth,
    setMonth,
    startOfMonth,
    startOfWeek,
} from 'date-fns';
import { computed, inject } from 'vue';
import type { UseCalendarReturn } from './useCalendar';

const calendar = inject<UseCalendarReturn>('calendar');

if (!calendar) {
    throw new Error('CalendarYearView must be used inside Calendar');
}

const weekStartsOn = 0;

const months = computed(() => {
    const d = calendar!.date.value;
    return Array.from({ length: 12 }, (_, i) =>
        addMonths(setMonth(startOfMonth(d), 0), i),
    );
});

function getDaysForMonth(month: Date) {
    const start = startOfWeek(startOfMonth(month), { weekStartsOn });
    return Array.from({ length: 42 }, (_, i) => addDays(start, i));
}

function isToday(date: Date) {
    return isSameDay(date, calendar!.today);
}
</script>

<template>
    <div
        class="h-full overflow-auto rounded-lg border border-border bg-card p-4"
    >
        <div class="grid grid-cols-2 gap-6 md:grid-cols-3 lg:grid-cols-4">
            <div
                v-for="month in months"
                :key="month.toISOString()"
                class="rounded border border-border bg-card p-2"
            >
                <div
                    class="mb-2 text-center text-sm font-semibold text-foreground"
                >
                    {{ format(month, 'MMMM') }}
                </div>
                <div class="grid grid-cols-7 text-[10px]">
                    <div
                        v-for="label in ['S', 'M', 'T', 'W', 'T', 'F', 'S']"
                        :key="label"
                        class="text-center font-medium text-muted-foreground"
                    >
                        {{ label }}
                    </div>
                    <div
                        v-for="day in getDaysForMonth(month)"
                        :key="day.toISOString()"
                        class="flex h-6 items-center justify-center rounded"
                        :class="{
                            'text-muted-foreground': !isSameMonth(day, month),
                            'bg-primary font-semibold text-primary-foreground':
                                isToday(day),
                        }"
                    >
                        {{ format(day, 'd') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
