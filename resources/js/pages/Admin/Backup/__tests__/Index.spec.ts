import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import Index from '../Index.vue';

describe('Admin Backup Index page (shallow mount)', () => {
    it('mounts with minimal props', () => {
        const wrapper = mount(Index, {
            props: {
                backups: {
                    data: [],
                    current_page: 1,
                    last_page: 1,
                    links: [],
                },
            },
            shallow: true,
        });

        expect(wrapper.exists()).toBe(true);
    });
});
