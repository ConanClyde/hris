export type CalendarView = 'day' | 'week' | 'month' | 'year';

export interface CalendarEventNormalized {
    id: string;
    title: string;
    start: Date;
    end: Date;
    allDay?: boolean;
    color?: string;
    extendedProps?: Record<string, unknown>;
}

export interface CalendarEventInput {
    id: string;
    title: string;
    start: string | Date;
    end?: string | Date;
    allDay?: boolean;
    backgroundColor?: string;
    extendedProps?: Record<string, unknown>;
}
