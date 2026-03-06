import { usePage, router } from '@inertiajs/vue3';
import { echo, echoIsConfigured } from '@laravel/echo-vue';

const AVATAR_CACHE_KEY = 'hris-avatar-cache';

interface AvatarCache {
    [userId: number]: string | null;
}

function getAvatarCache(): AvatarCache {
    try {
        const cached = localStorage.getItem(AVATAR_CACHE_KEY);
        return cached ? JSON.parse(cached) : {};
    } catch {
        return {};
    }
}

function setAvatarInCache(userId: number, avatar: string | null): void {
    try {
        const cache = getAvatarCache();
        if (avatar === null) {
            delete cache[userId];
        } else {
            cache[userId] = avatar;
        }
        localStorage.setItem(AVATAR_CACHE_KEY, JSON.stringify(cache));
    } catch {
        // Ignore errors
    }
}

function getAvatarFromCache(userId: number): string | null {
    return getAvatarCache()[userId] ?? null;
}

export function useRealtimeAvatar() {
    const page = usePage();
    let isListening = false;

    function startListening() {
        if (isListening) return;
        if (!echoIsConfigured()) {
            // Retry after a short delay if Echo isn't ready yet
            setTimeout(() => startListening(), 500);
            return;
        }

        const authUser = page.props.auth?.user as any;
        const uid: number | null =
            authUser && typeof authUser.id === 'number' ? authUser.id : null;
        if (uid === null) return;

        isListening = true;

        const echoInstance = echo() as any;

        echoInstance
            .private(`avatar-updates`)
            .listen(
                '.avatar.updated',
                (data: {
                    user_id: number;
                    avatar: string | null;
                    action: string;
                    timestamp: string;
                }) => {
                    // Update localStorage cache for ALL pages
                    setAvatarInCache(data.user_id, data.avatar);

                    // Update current user's auth avatar
                    if (
                        page.props.auth?.user &&
                        page.props.auth.user.id === data.user_id
                    ) {
                        page.props.auth.user = {
                            ...page.props.auth.user,
                            avatar: data.avatar ?? undefined,
                        };
                    }

                    // Reload current page to update tables
                    router.reload({
                        only: ['users', 'employees', 'data', 'logs'],
                    });
                },
            );
    }

    function stopListening() {
        if (!isListening) return;

        const echoInstance = echo() as any;
        echoInstance.leave('private-avatar-updates');
        isListening = false;
    }

    return {
        startListening,
        stopListening,
        getAvatarFromCache,
    };
}

// Helper composable for components that need to read from cache
export function useCachedAvatar() {
    function getAvatar(
        userId: number,
        propAvatar?: string | null,
    ): string | null {
        // If the prop explicitly says "no avatar", don't fall back to cache
        if (propAvatar === null) return null;
        // Prefer prop avatar, fallback to cache
        if (propAvatar) return propAvatar;
        return getAvatarFromCache(userId);
    }

    return {
        getAvatar,
    };
}
