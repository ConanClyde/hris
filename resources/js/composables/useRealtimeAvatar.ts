import { echo, echoIsConfigured } from '@laravel/echo-vue';
import { usePage } from '@inertiajs/vue3';

export function useRealtimeAvatar() {
    const page = usePage();
    let isListening = false;

    function startListening() {
        if (isListening) return;
        if (!echoIsConfigured()) return;

        const userId = page.props.auth?.user?.id;
        if (!userId) return;

        isListening = true;

        const echoInstance = echo();

        // Use public channel for avatar updates (no auth required)
        // This broadcasts to all users who can then update their local state
        echoInstance
            .channel(`avatar-updates`)
            .listen(
                '.avatar.updated',
                (data: {
                    user_id: number;
                    avatar: string | null;
                    action: string;
                    timestamp: string;
                }) => {
                    // Update the user in auth if it matches the updated user
                    if (
                        page.props.auth?.user &&
                        page.props.auth.user.id === data.user_id
                    ) {
                        page.props.auth.user = {
                            ...page.props.auth.user,
                            avatar: data.avatar ?? undefined,
                        };
                    }
                },
            );
    }

    function stopListening() {
        if (!isListening) return;

        const echoInstance = echo();
        echoInstance.leaveChannel('avatar-updates');
        isListening = false;
    }

    return {
        startListening,
        stopListening,
    };
}
