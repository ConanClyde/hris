import {
    startOfDay,
    endOfDay,
    startOfWeek,
    endOfWeek,
    startOfMonth,
    endOfMonth,
    startOfYear,
    endOfYear,
    addDays,
    addWeeks,
    addMonths,
    addYears,
    subDays,
    subWeeks,
    subMonths,
    subYears,
} from 'date-fns';
import { ref, computed, type Ref } from 'vue';
import type { CalendarView } from './types';
import type { CalendarEventNormalized } from './types';

export interface UseCalendarOptions {
    initialDate?: Date;
    initialView?: CalendarView;
    events: Ref<CalendarEventNormalized[]>;
}

export interface UseCalendarReturn {
    date: Ref<Date>;
    view: Ref<CalendarView>;
    events: Ref<CalendarEventNormalized[]>;
    today: Date;
    setDate: (d: Date) => void;
    setView: (v: CalendarView) => void;
    goPrev: () => void;
    goNext: () => void;
    goToday: () => void;
    visibleRange: Ref<{ start: Date; end: Date }>;
}

export function useCalendar(options: UseCalendarOptions): UseCalendarReturn {
    const { initialDate = new Date(), initialView = 'month', events } = options;

    const date = ref<Date>(new Date(initialDate.getTime()));
    const view = ref<CalendarView>(initialView);

    const today = new Date();

    const visibleRange = computed(() => {
        const d = date.value;
        switch (view.value) {
            case 'day':
                return { start: startOfDay(d), end: endOfDay(d) };
            case 'week': {
                const start = startOfWeek(d, { weekStartsOn: 0 });
                return { start, end: endOfWeek(d, { weekStartsOn: 0 }) };
            }
            case 'month':
                return { start: startOfMonth(d), end: endOfMonth(d) };
            case 'year':
                return { start: startOfYear(d), end: endOfYear(d) };
            default:
                return { start: startOfMonth(d), end: endOfMonth(d) };
        }
    });

    function setDate(d: Date) {
        date.value = new Date(d.getTime());
    }

    function setView(v: CalendarView) {
        view.value = v;
    }

    function goPrev() {
        const d = date.value;
        switch (view.value) {
            case 'day':
                date.value = subDays(d, 1);
                break;
            case 'week':
                date.value = subWeeks(d, 1);
                break;
            case 'month':
                date.value = subMonths(d, 1);
                break;
            case 'year':
                date.value = subYears(d, 1);
                break;
        }
    }

    function goNext() {
        const d = date.value;
        switch (view.value) {
            case 'day':
                date.value = addDays(d, 1);
                break;
            case 'week':
                date.value = addWeeks(d, 1);
                break;
            case 'month':
                date.value = addMonths(d, 1);
                break;
            case 'year':
                date.value = addYears(d, 1);
                break;
        }
    }

    function goToday() {
        date.value = new Date();
    }

    return {
        date,
        view,
        events,
        today,
        setDate,
        setView,
        goPrev,
        goNext,
        goToday,
        visibleRange,
    };
}
