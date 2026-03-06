import { usePage } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const authUser = ref<any>(null);
let initialized = false;

export function useAuthStore() {
    const page = usePage();

    // Initialize from page props on first call
    if (!initialized && page.props.auth?.user) {
        authUser.value = { ...page.props.auth.user };
        initialized = true;
    }

    function updateAuthUser(updates: Partial<any>) {
        if (authUser.value) {
            authUser.value = { ...authUser.value, ...updates };
        }
        // Also update page.props for consistency with other components
        if (page.props.auth?.user) {
            page.props.auth.user = { ...page.props.auth.user, ...updates };
        }
    }

    function setAuthUser(user: any) {
        authUser.value = user ? { ...user } : null;
        initialized = true;
    }

    return {
        authUser: computed(() => authUser.value ?? page.props.auth?.user),
        updateAuthUser,
        setAuthUser,
    };
}
