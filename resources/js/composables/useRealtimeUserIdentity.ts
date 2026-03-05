import { router, usePage } from '@inertiajs/vue3';
import { echo, echoIsConfigured } from '@laravel/echo-vue';

export type UserIdentityPayload = {
    user: {
        id: number;
        name?: string | null;
        first_name?: string | null;
        middle_name?: string | null;
        last_name?: string | null;
        name_extension?: string | null;
        email?: string | null;
        username?: string | null;
        role?: string | null;
        status?: string | null;
        is_active?: boolean | null;
        avatar?: string | null;
        updated_at?: string | null;
    };
    type?: string;
    timestamp?: string;
};

const DEFAULT_RELOAD_KEYS = [
    'users',
    'employees',
    'data',
    'logs',
    'applications',
    'trainings',
    'pdsList',
    'credits',
];

export function useRealtimeUserIdentity() {
    const page = usePage();
    let isListening = false;

    function getAuthRole(): string | null {
        const authUser = page.props.auth?.user as any;
        const role = authUser?.role;
        return typeof role === 'string' ? role : null;
    }

    function applyToAuthUser(payload: UserIdentityPayload) {
        const authUser = page.props.auth?.user as any;
        const u = payload.user;

        if (!authUser || typeof authUser.id !== 'number') return;
        if (authUser.id !== u.id) return;

        page.props.auth.user = {
            ...authUser,
            ...u,
        };
    }

    function startListening() {
        if (isListening) return;
        if (!echoIsConfigured()) return;

        const authUser = page.props.auth?.user as any;
        const uid: number | null = authUser && typeof authUser.id === 'number' ? authUser.id : null;

        if (uid === null) return;

        const role = getAuthRole();
        const isAdmin = role === 'admin';
        const isHr = role === 'hr';
        const isAdminOrHr = isAdmin || isHr;

         
        const echoInstance = echo() as any;
        isListening = true;

        // Global-ish channels (subscribe only if user is likely authorized)
        if (isAdmin) {
            echoInstance.private('admin.dashboard').listen('.user.identity.updated', (e: UserIdentityPayload) => {
                applyToAuthUser(e);
                router.reload({ only: DEFAULT_RELOAD_KEYS });
            });
        }

        if (isAdminOrHr) {
            echoInstance.private('hr.dashboard').listen('.user.identity.updated', (e: UserIdentityPayload) => {
                applyToAuthUser(e);
                router.reload({ only: DEFAULT_RELOAD_KEYS });
            });
        }

        echoInstance.private('employees').listen('.user.identity.updated', (e: UserIdentityPayload) => {
            applyToAuthUser(e);
            router.reload({ only: DEFAULT_RELOAD_KEYS });
        });

        // User-specific channel
        echoInstance.private(`App.Models.User.${uid}`).listen('.user.identity.updated', (e: UserIdentityPayload) => {
            applyToAuthUser(e);
            router.reload({ only: DEFAULT_RELOAD_KEYS });
        });
    }

    function stopListening() {
        if (!isListening) return;

         
        const echoInstance = echo() as any;

        echoInstance.leave('private-admin.dashboard');
        echoInstance.leave('private-hr.dashboard');
        echoInstance.leave('private-employees');

        const authUser = page.props.auth?.user as any;
        const uid: number | null = authUser && typeof authUser.id === 'number' ? authUser.id : null;
        if (uid !== null) {
            echoInstance.leave(`private-App.Models.User.${uid}`);
        }

        isListening = false;
    }

    return {
        startListening,
        stopListening,
    };
}
