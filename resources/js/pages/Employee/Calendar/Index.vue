<script setup lang="ts">
import type { EventInput } from '@fullcalendar/core';
import { Head } from '@inertiajs/vue3';
import {
    AlertCircle,
    Download,
} from 'lucide-vue-next';
import { computed, defineAsyncComponent, onMounted, ref } from 'vue';
import LegendPopover from '@/components/calendar/LegendPopover.vue';
import { Alert, AlertDescription, AlertTitle } from '@/components/ui/alert';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent } from '@/components/ui/card';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogHeader,
    DialogTitle,
    DialogFooter,
} from '@/components/ui/dialog';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import employee from '@/routes/employee';
import type { BreadcrumbItem } from '@/types';

const FullCalendar = defineAsyncComponent(() => import('@/components/FullCalendar.vue'));

type Holiday = {
    id: number;
    title: string;
    date: string;
    category: string;
    description?: string;
};

type CalendarEvent = {
    id: string;
    title: string;
    start: string;
    end?: string;
    allDay?: boolean;
    category: 'leave' | 'training' | 'holiday';
    status?: 'approved' | 'pending';
    backgroundColor?: string;
    borderColor?: string;
    textColor?: string;
    extendedProps?: {
        description?: string;
        employeeName?: string;
        employeeId?: number;
    };
};

withDefaults(
    defineProps<{
        holidays?: Holiday[];
    }>(),
    { holidays: () => [] },
);

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Calendar' }];

const events = ref<EventInput[]>([]);
const loading = ref(false);
const error = ref<string | null>(null);
const calendarRef = ref<InstanceType<typeof FullCalendar> | null>(null);

const dateEventsModalOpen = ref(false);
const selectedDateStr = ref('');
const eventDetailModalOpen = ref(false);
const selectedEvent = ref<{
    id: string;
    title: string;
    start: Date | string;
    end?: Date | string;
    extendedProps?: Record<string, unknown>;
} | null>(null);

const filterCategory = ref('all');
const filterStatus = ref('all');
const showStatusFilter = computed(() => filterCategory.value === 'leave');

function getEventColor(category: string, status?: string) {
    if (category === 'holiday') {
        return {
            backgroundColor: '#ef4444',
            borderColor: '#dc2626',
            textColor: '#ffffff',
        };
    }
    if (category === 'leave') {
        if (status === 'approved') {
            return {
                backgroundColor: '#10b981',
                borderColor: '#059669',
                textColor: '#ffffff',
            };
        }
        return {
            backgroundColor: '#f59e0b',
            borderColor: '#d97706',
            textColor: '#ffffff',
        };
    }
    if (category === 'training') {
        if (status === 'approved') {
            return {
                backgroundColor: '#3b82f6',
                borderColor: '#2563eb',
                textColor: '#ffffff',
            };
        }
        return {
            backgroundColor: '#38bdf8',
            borderColor: '#0ea5e9',
            textColor: '#ffffff',
        };
    }
    return {
        backgroundColor: '#6b7280',
        borderColor: '#4b5563',
        textColor: '#ffffff',
    };
}

async function fetchEvents(start?: Date, end?: Date) {
    loading.value = true;
    error.value = null;

    try {
        const query: Record<string, string> = {};
        if (start) query.start = start.toISOString().slice(0, 10);
        if (end) query.end = end.toISOString().slice(0, 10);
        if (filterCategory.value !== 'all')
            query.category = filterCategory.value;
        if (showStatusFilter.value && filterStatus.value !== 'all')
            query.status = filterStatus.value;

        const url = employee.calendar.events.url({ query });
        const res = await fetch(url);

        if (!res.ok) throw new Error('Failed to load calendar events');

        const data: CalendarEvent[] = await res.json();

        events.value = data.map((evt) => {
            const colors = getEventColor(evt.category, evt.status);
            return {
                id: evt.id,
                title: evt.title,
                start: evt.start,
                end: evt.end,
                allDay: evt.allDay ?? true,
                backgroundColor: colors.backgroundColor,
                borderColor: colors.borderColor,
                textColor: colors.textColor,
                extendedProps: {
                    category: evt.category,
                    status: evt.status,
                    ...evt.extendedProps,
                },
            };
        });
    } catch (e) {
        error.value = e instanceof Error ? e.message : 'Failed to load events';
    } finally {
        loading.value = false;
    }
}

function onDatesSet(info: { start: Date; end: Date }) {
    fetchEvents(info.start, info.end);
}

function onDateClick(info: { dateStr: string }) {
    selectedDateStr.value = info.dateStr;
    dateEventsModalOpen.value = true;
}

const eventsForSelectedDate = computed(() => {
    const dateStr = selectedDateStr.value;
    if (!dateStr) return [];
    const day = dateStr.slice(0, 10);
    const dayStart = new Date(day + 'T00:00:00');
    const dayEnd = new Date(dayStart);
    dayEnd.setDate(dayEnd.getDate() + 1);
    return events.value.filter((ev) => {
        const start =
            ev.start instanceof Date ? ev.start : new Date(ev.start as string);
        const end =
            ev.end != null
                ? ev.end instanceof Date
                    ? ev.end
                    : new Date(ev.end as string)
                : start;
        return start < dayEnd && end > dayStart;
    });
});

function exportCalendar() {
    alert('Calendar export functionality would be implemented here');
}

function formatDateEventsTitle(dateStr: string) {
    if (!dateStr) return '';
    const d = new Date(dateStr + (dateStr.length === 10 ? 'T12:00:00' : ''));
    return d.toLocaleDateString(undefined, {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    });
}

function openEventDetail(eventInput: EventInput) {
    selectedEvent.value = {
        id: String(eventInput.id ?? ''),
        title: String(eventInput.title ?? ''),
        start:
            eventInput.start instanceof Date
                ? eventInput.start
                : new Date(eventInput.start as string),
        end: eventInput.end
            ? eventInput.end instanceof Date
                ? eventInput.end
                : new Date(eventInput.end as string)
            : undefined,
        extendedProps: (eventInput.extendedProps ?? {}) as Record<
            string,
            unknown
        >,
    };
    dateEventsModalOpen.value = false;
    eventDetailModalOpen.value = true;
}

function onEventClick(info: {
    event: {
        id: string;
        title: string;
        start: Date;
        end?: Date;
        extendedProps?: Record<string, unknown>;
    };
}) {
    const e = info.event;
    selectedEvent.value = {
        id: e.id,
        title: e.title,
        start: e.start,
        end: e.end,
        extendedProps: e.extendedProps ?? {},
    };
    eventDetailModalOpen.value = true;
}

function closeEventDetailModal() {
    eventDetailModalOpen.value = false;
    selectedEvent.value = null;
}

function getEventBadgeClasses(category: string, status?: string) {
    if (category === 'holiday') {
        return 'bg-red-100 text-red-700 border-red-200 dark:bg-red-900/30 dark:text-red-300 dark:border-red-800';
    }
    if (category === 'leave') {
        if (status === 'approved') {
            return 'bg-emerald-100 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-300 dark:border-emerald-800';
        }
        return 'bg-amber-100 text-amber-700 border-amber-200 dark:bg-amber-900/30 dark:text-amber-300 dark:border-amber-800';
    }
    if (category === 'training') {
        if (status === 'approved') {
            return 'bg-blue-100 text-blue-700 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300 dark:border-blue-800';
        }
        return 'bg-sky-100 text-sky-700 border-sky-200 dark:bg-sky-900/30 dark:text-sky-300 dark:border-sky-800';
    }
    return 'bg-gray-100 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700';
}

function getStatusBadgeClasses(status?: string) {
    if (status === 'approved') {
        return 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/20 dark:text-emerald-300 dark:border-emerald-800';
    }
    if (status === 'pending') {
        return 'bg-amber-50 text-amber-700 border-amber-200 dark:bg-amber-900/20 dark:text-amber-300 dark:border-amber-800';
    }
    return 'bg-gray-50 text-gray-700 border-gray-200 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700';
}

function closeDateEventsModal() {
    dateEventsModalOpen.value = false;
    selectedDateStr.value = '';
}

onMounted(() => {
    const now = new Date();
    const start = new Date(now.getFullYear(), now.getMonth(), 1);
    const end = new Date(now.getFullYear(), now.getMonth() + 1, 0);
    fetchEvents(start, end);
});
</script>

<template>
    <Head title="Calendar" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="space-y-6 p-4 md:p-6">
            <!-- Header -->
            <div
                class="flex flex-col items-start justify-between gap-4 sm:flex-row sm:items-center"
            >
                <div>
                    <h1
                        class="text-2xl font-semibold tracking-tight text-foreground"
                    >
                        Calendar
                    </h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Your leave, training, and holidays
                    </p>
                </div>

                <div class="flex w-full flex-wrap items-center gap-3 sm:w-auto">
                    <Button
                        type="button"
                        variant="outline"
                        class="h-10 px-4"
                        @click="exportCalendar"
                    >
                        <Download class="mr-2 h-4 w-4" />
                        Export CSV
                    </Button>

                    <Select
                        v-model="filterCategory"
                        @update:model-value="fetchEvents()"
                    >
                        <SelectTrigger class="h-9 w-[140px]">
                            <SelectValue placeholder="All Events" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Events</SelectItem>
                            <SelectItem value="leave">My Leaves</SelectItem>
                            <SelectItem value="training"
                                >My Training</SelectItem
                            >
                            <SelectItem value="holiday">Holidays</SelectItem>
                        </SelectContent>
                    </Select>

                    <Select
                        v-if="showStatusFilter"
                        v-model="filterStatus"
                        @update:model-value="fetchEvents()"
                    >
                        <SelectTrigger class="h-9 w-[140px]">
                            <SelectValue placeholder="All Status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="all">All Status</SelectItem>
                            <SelectItem value="approved">Approved</SelectItem>
                            <SelectItem value="pending">Pending</SelectItem>
                        </SelectContent>
                    </Select>

                    <LegendPopover />
                </div>
            </div>

            <Alert v-if="error" variant="destructive" class="border-border">
                <AlertCircle class="size-4" />
                <AlertTitle>Error</AlertTitle>
                <AlertDescription>{{ error }}</AlertDescription>
            </Alert>

            <div class="grid grid-cols-1 gap-6">
                <div class="col-span-1">
                    <Card
                        class="flex h-[70vh] min-h-[500px] flex-col gap-0 overflow-hidden border-border bg-background py-0 text-foreground dark:border-border dark:bg-card dark:text-card-foreground"
                    >
                        <CardContent class="flex-1 min-h-0 overflow-hidden p-0">
                            <Suspense>
                                <template #default>
                                    <FullCalendar
                                        ref="calendarRef"
                                        :events="events"
                                        :loading="loading"
                                        initial-view="dayGridMonth"
                                        @dates-set="onDatesSet"
                                        @date-click="onDateClick"
                                        @event-click="onEventClick"
                                    />
                                </template>
                                <template #fallback>
                                    <div class="h-full min-h-[500px] w-full animate-pulse rounded-lg bg-muted" />
                                </template>
                            </Suspense>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </div>

        <!-- Date Events List Modal -->
        <Dialog
            v-model:open="dateEventsModalOpen"
            @update:open="(v: boolean) => !v && closeDateEventsModal()"
        >
            <DialogContent :show-close-button="true" class="sm:max-w-md">
                <DialogHeader>
                    <DialogTitle>Events</DialogTitle>
                    <DialogDescription class="sr-only">
                        List of events on the selected date.
                    </DialogDescription>
                    <p class="text-sm text-muted-foreground">
                        {{ formatDateEventsTitle(selectedDateStr) }}
                    </p>
                </DialogHeader>
                <div class="max-h-[60vh] space-y-2 overflow-y-auto py-2">
                    <button
                        v-for="ev in eventsForSelectedDate"
                        :key="ev.id"
                        type="button"
                        class="w-full rounded-lg border border-gray-200 bg-white p-3 text-left transition-colors hover:border-gray-300 hover:bg-gray-50 dark:border-neutral-700 dark:bg-neutral-900 dark:hover:border-neutral-600 dark:hover:bg-neutral-800"
                        @click="openEventDetail(ev)"
                    >
                        <p class="font-medium text-gray-900 dark:text-gray-100">
                            {{ ev.title }}
                        </p>
                        <div class="mt-1 flex flex-wrap gap-2">
                            <Badge
                                variant="outline"
                                class="text-xs capitalize"
                                :class="getEventBadgeClasses((ev.extendedProps as { category?: string })?.category ?? 'event', (ev.extendedProps as { status?: string })?.status)"
                            >
                                {{ (ev.extendedProps as { category?: string })?.category ?? 'event' }}
                            </Badge>
                            <Badge
                                v-if="(ev.extendedProps as { status?: string })?.status"
                                variant="outline"
                                class="text-xs capitalize"
                                :class="getStatusBadgeClasses((ev.extendedProps as { status?: string })?.status)"
                            >
                                {{ (ev.extendedProps as { status?: string })?.status }}
                            </Badge>
                        </div>
                    </button>
                    <p
                        v-if="eventsForSelectedDate.length === 0"
                        class="py-4 text-center text-sm text-gray-500 dark:text-gray-400"
                    >
                        No events on this day
                    </p>
                </div>
                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeDateEventsModal"
                    >
                        Close
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Event Detail Modal -->
        <Dialog
            v-model:open="eventDetailModalOpen"
            @update:open="(v: boolean) => !v && closeEventDetailModal()"
        >
            <DialogContent
                v-if="selectedEvent"
                :show-close-button="true"
                class="sm:max-w-md"
            >
                <DialogHeader>
                    <DialogTitle>{{ selectedEvent.title }}</DialogTitle>
                    <DialogDescription class="sr-only">
                        Detailed information about the selected calendar event.
                    </DialogDescription>
                </DialogHeader>

                <div class="space-y-4 py-4">
                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Schedule</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{
                                typeof selectedEvent.start === 'string'
                                    ? new Date(selectedEvent.start).toLocaleString()
                                    : selectedEvent.start.toLocaleString()
                            }}
                            <template v-if="selectedEvent.end">
                                —
                                {{
                                    typeof selectedEvent.end === 'string'
                                        ? new Date(selectedEvent.end).toLocaleString()
                                        : selectedEvent.end.toLocaleString()
                                }}
                            </template>
                        </p>
                    </div>

                    <div v-if="selectedEvent.extendedProps?.employeeName" class="space-y-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Employee</p>
                        <p class="text-sm text-gray-900 dark:text-gray-100">
                            {{ selectedEvent.extendedProps.employeeName }}
                        </p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Category & Status</p>
                        <div class="flex flex-wrap gap-2">
                            <Badge
                                variant="outline"
                                class="capitalize"
                                :class="getEventBadgeClasses((selectedEvent.extendedProps?.category as string) ?? 'event', (selectedEvent.extendedProps?.status as string))"
                            >
                                {{ (selectedEvent.extendedProps?.category as string) ?? 'event' }}
                            </Badge>
                            <Badge
                                v-if="selectedEvent.extendedProps?.status"
                                variant="outline"
                                class="capitalize"
                                :class="getStatusBadgeClasses(selectedEvent.extendedProps?.status as string)"
                            >
                                {{ selectedEvent.extendedProps.status as string }}
                            </Badge>
                        </div>
                    </div>

                    <div v-if="selectedEvent.extendedProps?.description" class="space-y-1">
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Details</p>
                        <div class="rounded-md bg-gray-50 p-3 text-sm text-gray-700 dark:bg-neutral-800/50 dark:text-gray-300">
                            {{ selectedEvent.extendedProps.description }}
                        </div>
                    </div>
                </div>

                <DialogFooter>
                    <Button
                        type="button"
                        variant="outline"
                        @click="closeEventDetailModal"
                    >
                        Close
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
