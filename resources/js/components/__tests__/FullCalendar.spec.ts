import { mount } from '@vue/test-utils';
import { describe, expect, it, vi } from 'vitest';

const mockCalendarState = vi.hoisted(() => ({
    lastOptions: null as any,
}));

vi.mock('@fullcalendar/core', () => {
    class Calendar {
        el: HTMLElement;
        options: any;
        constructor(el: HTMLElement, options: any) {
            this.el = el;
            this.options = options;
            mockCalendarState.lastOptions = options;
        }
        render() {}
        destroy() {}
        prev() {}
        next() {}
        today() {}
        changeView() {}
        setOption() {}
        updateSize() {}
        removeAllEvents() {}
        addEventSource() {}
    }
    return {
        Calendar,
    };
});

vi.mock('@fullcalendar/daygrid', () => ({ default: {} }));
vi.mock('@fullcalendar/interaction', () => ({ default: {} }));
vi.mock('@fullcalendar/list', () => ({ default: {} }));
vi.mock('@fullcalendar/multimonth', () => ({ default: {} }));
vi.mock('@fullcalendar/timegrid', () => ({ default: {} }));
import FullCalendar from '@/components/FullCalendar.vue';

describe('FullCalendar', () => {
    it('renders a scrollable container for calendar content', async () => {
        const wrapper = mount(FullCalendar);
        await wrapper.vm.$nextTick();
        const calendarEl = wrapper.find('.fc-shadcn');
        const scrollContainer = calendarEl.element.parentElement;
        expect(scrollContainer).toBeTruthy();
        expect(scrollContainer?.className).toContain('overflow-auto');
        expect(scrollContainer?.className).toContain('touch-pan-y');
    });

    it('emits date and event interactions from FullCalendar callbacks', async () => {
        const wrapper = mount(FullCalendar);
        await wrapper.vm.$nextTick();
        await wrapper.vm.$nextTick();
        const options = mockCalendarState.lastOptions;
        options.dateClick({
            date: new Date('2024-01-01'),
            dateStr: '2024-01-01',
        });
        options.eventClick({
            event: {
                id: '1',
                title: 'Event',
                start: new Date('2024-01-01'),
            },
        });
        expect(wrapper.emitted('dateClick')?.length).toBe(1);
        expect(wrapper.emitted('eventClick')?.length).toBe(1);
    });
});
