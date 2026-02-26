<script setup lang="ts">
import { Calendar } from '@fullcalendar/core';
import type { CalendarOptions, EventInput } from '@fullcalendar/core';
import dayGridPlugin from '@fullcalendar/daygrid';
import interactionPlugin from '@fullcalendar/interaction';
import listPlugin from '@fullcalendar/list';
import multiMonthPlugin from '@fullcalendar/multimonth';
import timeGridPlugin from '@fullcalendar/timegrid';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';
import { inject, nextTick, onMounted, onUnmounted, onUpdated, ref, watch } from 'vue';
import { Button } from '@/components/ui/button';
import { Spinner } from '@/components/ui/spinner';
import { cn } from '@/lib/utils';

const props = withDefaults(
    defineProps<{
        events?: EventInput[];
        initialView?: string;
        headerToolbar?: boolean;
        showViewSwitcher?: boolean;
        height?: string | number;
        selectable?: boolean;
        loading?: boolean;
    }>(),
    {
        events: () => [],
        initialView: 'dayGridMonth',
        headerToolbar: false,
        showViewSwitcher: true,
        height: '100%',
        selectable: false,
        loading: false,
    },
);

const emit = defineEmits<{
    (e: 'dateSelect', info: { start: Date; end: Date; allDay: boolean }): void;
    (e: 'dateClick', info: { date: Date; dateStr: string }): void;
    (
        e: 'eventClick',
        info: {
            event: {
                id: string;
                title: string;
                start: Date;
                end?: Date;
                extendedProps?: Record<string, unknown>;
            };
        },
    ): void;
    (e: 'datesSet', info: { start: Date; end: Date }): void;
}>();

const calendarEl = ref<HTMLElement | null>(null);
const calendar = ref<Calendar | null>(null);
const currentView = ref(props.initialView);
const currentTitle = ref('');

const viewOptions = [
    { value: 'multiMonthYear', label: 'Year' },
    { value: 'dayGridMonth', label: 'Month' },
    { value: 'timeGridWeek', label: 'Week' },
    { value: 'listMonth', label: 'List' },
];

function initCalendar() {
    if (!calendarEl.value) return;

    const options: CalendarOptions = {
        plugins: [
            dayGridPlugin,
            timeGridPlugin,
            listPlugin,
            multiMonthPlugin,
            interactionPlugin,
        ],
        initialView: props.initialView,
        events: props.events,
        headerToolbar: false,
        height: '100%',
        selectable: props.selectable,
        select: (info) => {
            emit('dateSelect', {
                start: info.start,
                end: info.end,
                allDay: info.allDay,
            });
        },
        dateClick: (info) => {
            emit('dateClick', { date: info.date, dateStr: info.dateStr });
        },
        eventClick: (info) => {
            emit('eventClick', { event: info.event });
        },
        datesSet: (info) => {
            currentTitle.value = info.view.title;
            emit('datesSet', { start: info.start, end: info.end });
        },
        dayMaxEvents: true,
        eventDisplay: 'block',
        firstDay: 0,
        fixedWeekCount: false,
        showNonCurrentDates: true,
    };

    calendar.value = new Calendar(calendarEl.value, options);
    calendar.value.render();
}

function changeView(viewName: string) {
    if (calendar.value) {
        calendar.value.changeView(viewName);
        currentView.value = viewName;

        // Adjust height based on view: 100% for Month to fill container, auto for scrolling views
        if (viewName === 'dayGridMonth') {
            calendar.value.setOption('height', '100%');
        } else {
            calendar.value.setOption('height', 'auto');
        }
    }
}

function prev() {
    calendar.value?.prev();
}

function next() {
    calendar.value?.next();
}

function today() {
    calendar.value?.today();
}

watch(
    () => props.events,
    (newEvents) => {
        if (calendar.value) {
            calendar.value.removeAllEvents();
            calendar.value.addEventSource(newEvents || []);
        }
    },
);

onUpdated(() => {
    // Trigger calendar resize when component updates
    nextTick(() => {
        calendar.value?.updateSize();
    });
});

let resizeTimeout: number | null = null;

function handleResize() {
    if (resizeTimeout !== null) {
        window.clearTimeout(resizeTimeout);
    }

    resizeTimeout = window.setTimeout(() => {
        calendar.value?.updateSize();
        resizeTimeout = null;
    }, 100);
}

const sidebar = inject('sidebar', null);

// Watch for sidebar state changes
if (sidebar) {
    watch(
        () => sidebar.state.value,
        () => {
            nextTick(() => {
                handleResize();
            });
        },
    );

    watch(
        () => sidebar.open.value,
        () => {
            nextTick(() => {
                handleResize();
            });
        },
    );
}

let resizeObserver: ResizeObserver | null = null;

onMounted(() => {
    nextTick(() => {
        initCalendar();
    });

    // Add resize listener
    window.addEventListener('resize', handleResize);

    // Add DOM resize observer for container changes
    if (calendarEl.value) {
        resizeObserver = new ResizeObserver(() => {
            handleResize();
        });
        resizeObserver.observe(calendarEl.value);
    }
});

onUnmounted(() => {
    calendar.value?.destroy();
    window.removeEventListener('resize', handleResize);
    if (resizeTimeout !== null) {
        window.clearTimeout(resizeTimeout);
        resizeTimeout = null;
    }
    if (resizeObserver && calendarEl.value) {
        resizeObserver.unobserve(calendarEl.value);
        resizeObserver.disconnect();
    }
});

defineExpose({
    changeView,
    prev,
    next,
    today,
});
</script>

<template>
    <div
        class="flex h-full flex-col rounded-lg border border-border bg-background text-card-foreground dark:border-border dark:bg-card dark:text-card-foreground"
    >
        <!-- ShadCN-style calendar header: flex justify-center relative items-center w-full px-8 -->
        <div
            class="flex shrink-0 flex-col items-center gap-4 bg-background p-3 sm:flex-row sm:justify-between sm:px-6 dark:bg-card"
        >
            <div
                class="flex w-full items-center justify-center gap-1 sm:w-auto sm:justify-self-start"
            >
                <Button
                    type="button"
                    variant="outline"
                    class="size-8 border-border bg-transparent p-0 text-foreground opacity-50 hover:bg-accent hover:text-accent-foreground hover:opacity-100 sm:size-9 dark:border-border dark:text-foreground dark:hover:bg-accent dark:hover:text-accent-foreground"
                    @click="prev"
                >
                    <ChevronLeft class="size-4" />
                </Button>
                <Button
                    type="button"
                    variant="outline"
                    class="size-8 border-border bg-transparent p-0 text-foreground opacity-50 hover:bg-accent hover:text-accent-foreground hover:opacity-100 sm:size-9 dark:border-border dark:text-foreground dark:hover:bg-accent dark:hover:text-accent-foreground"
                    @click="next"
                >
                    <ChevronRight class="size-4" />
                </Button>
                <Button
                    type="button"
                    variant="outline"
                    size="sm"
                    class="ml-2 border-border text-foreground hover:bg-accent hover:text-accent-foreground dark:border-border dark:text-foreground dark:hover:bg-accent dark:hover:text-accent-foreground"
                    @click="today"
                >
                    Today
                </Button>
            </div>

            <div v-if="showViewSwitcher" class="flex items-center gap-1">
                <Button
                    v-for="view in viewOptions"
                    :key="view.value"
                    type="button"
                    variant="outline"
                    size="sm"
                    :class="
                        cn(
                            'h-10 border-border px-3 font-normal text-foreground hover:bg-accent hover:text-accent-foreground',
                            currentView === view.value &&
                                '!border-brand !bg-brand !text-white hover:!border-brand-dark hover:!bg-brand-dark hover:!text-white',
                        )
                    "
                    @click="changeView(view.value)"
                >
                    {{ view.label }}
                </Button>
            </div>
        </div>
        <div
            class="relative h-full min-h-[400px] flex-1 overflow-hidden bg-background p-3 sm:p-4 dark:bg-card"
        >
            <div
                v-if="props.loading"
                class="absolute inset-0 z-10 flex items-center justify-center bg-background/70 dark:bg-card/70"
                aria-live="polite"
                aria-busy="true"
            >
                <Spinner class="size-6 text-muted-foreground" />
            </div>
            <div ref="calendarEl" class="fc-shadcn h-full min-h-[400px]" />
        </div>
    </div>
</template>

<style scoped>
/* ShadCN-style FullCalendar: semantic tokens, rounded cells, accent for today */
.fc-shadcn :deep(.fc-theme-standard .fc-scrollgrid),
.fc-shadcn :deep(.fc-scrollgrid) {
    border-color: hsl(var(--border));
}

.fc-shadcn :deep(.fc-theme-standard td),
.fc-shadcn :deep(.fc-theme-standard th),
.fc-shadcn :deep(td),
.fc-shadcn :deep(th) {
    border-color: hsl(var(--border));
}

/* Dark mode specific overrides */
.dark .fc-shadcn :deep(.fc-theme-standard .fc-scrollgrid),
.dark .fc-shadcn :deep(.fc-scrollgrid) {
    border-color: hsl(var(--border));
}

.dark .fc-shadcn :deep(.fc-theme-standard td),
.dark .fc-shadcn :deep(.fc-theme-standard th),
.dark .fc-shadcn :deep(td),
.dark .fc-shadcn :deep(th) {
    border-color: hsl(var(--border));
}

.fc-shadcn :deep(.fc-col-header-cell) {
    padding: 0.25rem 0;
    background-color: hsl(var(--background));
}

.fc-shadcn :deep(.fc-col-header-cell-cushion) {
    color: hsl(var(--muted-foreground));
    font-size: 0.8rem;
    font-weight: 400;
    padding: 0.25rem;
}

.dark .fc-shadcn :deep(.fc-col-header-cell) {
    background-color: hsl(var(--card));
}

.dark .fc-shadcn :deep(.fc-col-header-cell-cushion) {
    color: hsl(var(--muted-foreground));
}

.fc-shadcn :deep(.fc-daygrid-day) {
    min-height: 6rem;
    background-color: hsl(var(--background));
}

.fc-shadcn :deep(.fc-daygrid-day-frame) {
    min-height: 100%;
}

.fc-shadcn :deep(.fc-daygrid-day-number) {
    color: hsl(var(--foreground));
    font-size: 0.875rem;
    font-weight: 400;
    width: 1.75rem;
    height: 1.75rem;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    margin: 0.125rem;
    transition: background-color, color;
}

.fc-shadcn :deep(.fc-daygrid-day.fc-day-today .fc-daygrid-day-number) {
    background-color: hsl(var(--accent));
    color: hsl(var(--accent-foreground));
}

.fc-shadcn :deep(.fc-day-other .fc-daygrid-day-number) {
    color: hsl(var(--muted-foreground));
}

.dark .fc-shadcn :deep(.fc-daygrid-day) {
    background-color: hsl(var(--card));
}

.dark .fc-shadcn :deep(.fc-daygrid-day.fc-day-today .fc-daygrid-day-number) {
    background-color: hsl(var(--accent));
    color: hsl(var(--accent-foreground));
}

.dark .fc-shadcn :deep(.fc-day-other .fc-daygrid-day-number) {
    color: hsl(var(--muted-foreground));
}

.fc-shadcn :deep(.fc-daygrid-day-events) {
    margin-top: 0.25rem;
    min-height: 0;
}

.fc-shadcn :deep(.fc-daygrid-event) {
    margin: 0 0.125rem 0.125rem;
    border-radius: 0.375rem;
    border: none;
    padding: 0.125rem 0.375rem;
    font-size: 0.75rem;
    line-height: 1.25;
}

.fc-shadcn :deep(.fc-event-main) {
    padding: 0;
}

.fc-shadcn :deep(.fc-event) {
    cursor: pointer;
    border-radius: 0.375rem;
}

.fc-shadcn :deep(.fc-list-empty) {
    color: hsl(var(--muted-foreground));
    font-size: 0.875rem;
}

.fc-shadcn :deep(.fc-list-event-title) {
    color: hsl(var(--foreground));
}

.fc-shadcn :deep(.fc-list-event:hover td) {
    background-color: hsl(var(--accent) / 0.5);
}

/* List view specific styling */
.fc-shadcn :deep(.fc-list) {
    border: none !important;
}

.fc-shadcn :deep(.fc-list-day-cushion) {
    background-color: hsl(var(--muted) / 0.3);
    color: hsl(var(--foreground));
    font-weight: 500;
    padding: 0.5rem 1rem;
}

.fc-shadcn :deep(.fc-list-event) {
    cursor: pointer !important;
    border: none;
}

.fc-shadcn :deep(.fc-list-event td) {
    border: none;
    padding: 0.5rem 1rem;
}

.fc-shadcn :deep(.fc-list-event-time) {
    color: hsl(var(--muted-foreground));
    font-size: 0.875rem;
}

.fc-shadcn :deep(.fc-list-event-title) {
    color: hsl(var(--foreground));
    font-size: 0.875rem;
}

/* Dark mode specific overrides for list view */
.dark .fc-shadcn :deep(.fc-list-day-cushion) {
    background-color: hsl(var(--muted) / 0.5);
    color: hsl(var(--foreground));
}

.dark .fc-shadcn :deep(.fc-list-event:hover td) {
    background-color: hsl(var(--accent) / 0.3);
}

.dark .fc-shadcn :deep(.fc-list-event-time) {
    color: hsl(var(--muted-foreground));
}

.dark .fc-shadcn :deep(.fc-list-event-title) {
    color: hsl(var(--foreground));
}

.dark .fc-shadcn :deep(.fc-list-empty) {
    color: hsl(var(--muted-foreground));
}

.fc-shadcn :deep(.fc-timegrid-slot) {
    height: 2rem;
    background-color: hsl(var(--background));
}

.fc-shadcn :deep(.fc-timegrid-axis-cushion),
.fc-shadcn :deep(.fc-timegrid-slot-label-cushion) {
    color: hsl(var(--muted-foreground));
    font-size: 0.75rem;
}

.fc-shadcn :deep(.fc-timegrid-event) {
    border-radius: 0.375rem;
    border: none;
}

.fc-shadcn :deep(.fc-multimonth-month-header) {
    background: hsl(var(--muted) / 0.3);
    color: hsl(var(--foreground));
    font-size: 0.875rem;
    font-weight: 500;
}

.fc-shadcn :deep(.fc-multimonth .fc-daygrid-day-number) {
    font-size: 0.75rem;
    width: 1.5rem;
    height: 1.5rem;
}

/* Dark mode specific overrides */
.dark .fc-shadcn :deep(.fc-timegrid-slot) {
    background-color: hsl(var(--card));
}

.dark .fc-shadcn :deep(.fc-timegrid-axis-cushion),
.dark .fc-shadcn :deep(.fc-timegrid-slot-label-cushion) {
    color: hsl(var(--muted-foreground));
}

.dark .fc-shadcn :deep(.fc-multimonth-month-header) {
    background: hsl(var(--muted) / 0.3);
    color: hsl(var(--foreground));
}

/* Ensure proper contrast for events in dark mode */
.dark .fc-shadcn :deep(.fc-event) {
    color: hsl(var(--foreground));
}

.dark .fc-shadcn :deep(.fc-list-event-title) {
    color: hsl(var(--foreground));
}

.dark .fc-shadcn :deep(.fc-list-empty) {
    color: hsl(var(--muted-foreground));
}
</style>
