import { computed } from 'vue';

/**
 * Returns the CSRF token from the meta tag. Safe for use in templates and during SSR
 * (returns empty string when document is not available).
 */
export function useCsrfToken() {
    return computed(() => {
        if (typeof document === 'undefined') return '';
        return (
            document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content') || ''
        );
    });
}
