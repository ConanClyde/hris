import { ref, computed } from 'vue';

export interface NotificationPayload {
    title: string;
    message: string;
    type: 'info' | 'success' | 'warning' | 'error';
    data?: Record<string, unknown>;
    timestamp?: string;
}

export interface RealTimeNotification {
    id: string;
    title: string;
    message: string;
    type: 'info' | 'success' | 'warning' | 'error';
    read: boolean;
    created_at: string;
    data?: Record<string, unknown>;
}

export function formatUnreadBadge(count: number): string {
    if (count > 99) return '99+';
    return String(count);
}

const usersPendingCount = ref<number | null>(null);
const notificationsUnreadCount = ref<number | null>(null);
const leavesPendingCount = ref<number | null>(null);
const trainingsAssignedCount = ref<number | null>(null);
const pdsPendingCount = ref<number | null>(null);

// Shared notification state — module-level so all components see the same data
const notifications = ref<RealTimeNotification[]>([]);
const unreadCount = computed(
    () => notifications.value.filter((n) => !n.read).length,
);

const currentAuthUserId = ref<number | null>(null);
const currentUserRole = ref<string>('');

function rolePrefix(): string {
    const role = currentUserRole.value;
    if (role === 'admin') return '/admin';
    if (role === 'hr') return '/hr';
    return '/employee';
}

function getCsrfToken(): string {
    return (
        document
            .querySelector('meta[name="csrf-token"]')
            ?.getAttribute('content') || ''
    );
}

type PostRealtimeEvent =
    | {
          type: 'reaction_updated';
          post_id: number;
          reactions_count: number;
          actor_user_id?: number | null;
          actor_reaction?: string | null;
      }
    | {
          type: 'comment_created';
          post_id: number;
          comments_count: number;
          comment?: {
              id: number;
              body: string;
              user_id: number;
              created_at: string;
          };
      }
    | {
          type: 'post_created';
          post: {
              id: number;
              title: string;
              body: string;
              role_scope: string;
              is_pinned: boolean;
              is_published: boolean;
              comments_count: number;
              reactions_count: number;
              author?: {
                  id: number;
                  name?: string;
                  first_name?: string;
                  last_name?: string;
              } | null;
              created_at: string;
          };
          actor_user_id?: number | null;
      };

const lastPostEvent = ref<PostRealtimeEvent | null>(null);

type UserManagementEventType =
    | 'registered'
    | 'approved'
    | 'rejected'
    | 'identity_updated';
type UserManagementEvent = {
    type: UserManagementEventType;
    user: {
        id: number;
        name?: string;
        email?: string;
        username?: string;
        role?: string;
        status?: string;
        created_at?: string;
        first_name?: string;
        middle_name?: string;
        last_name?: string;
        name_extension?: string;
        is_active?: boolean;
        avatar?: string | null;
    };
};

const lastUserManagementEvent = ref<UserManagementEvent | null>(null);

const MAX_NOTIFICATIONS = 100;
let userListenersReady = false;
let adminListenersReady = false;
let hrListenersReady = false;
let employeeListenersReady = false;
let postListenersReady = false;

function getEchoAny(): any {
    return (window as any)?.Echo;
}

function listen(
    channelName: string,
    event: string,
    callback: (e: any) => void,
) {
    try {
        const echoAny = getEchoAny();
        if (!echoAny) return;

        const channel =
            echoAny.private?.(channelName) ?? echoAny.channel?.(channelName);
        channel?.listen?.(event, callback);
    } catch {
        // ignore
    }
}

function bumpPending(delta: number) {
    const current = usersPendingCount.value;
    if (current === null) return;
    usersPendingCount.value = Math.max(0, current + delta);
}

function bumpNotificationsUnread(delta: number) {
    const current = notificationsUnreadCount.value;
    if (current === null) return;
    notificationsUnreadCount.value = Math.max(0, current + delta);
}

function bumpLeavesPending(delta: number) {
    const current = leavesPendingCount.value;
    if (current === null) return;
    leavesPendingCount.value = Math.max(0, current + delta);
}

function bumpTrainingsAssigned(delta: number) {
    const current = trainingsAssignedCount.value;
    if (current === null) return;
    trainingsAssignedCount.value = Math.max(0, current + delta);
}

function bumpPdsPending(delta: number) {
    const current = pdsPendingCount.value;
    if (current === null) return;
    pdsPendingCount.value = Math.max(0, current + delta);
}

export function useBroadcasting() {
    const addNotification = (payload: NotificationPayload) => {
        const notification: RealTimeNotification = {
            id: crypto?.randomUUID?.() ?? String(Date.now()),
            title: payload.title,
            message: payload.message,
            type: payload.type,
            read: false,
            created_at: payload.timestamp || new Date().toISOString(),
            data: payload.data,
        };
        notifications.value.unshift(notification);
        if (notifications.value.length > MAX_NOTIFICATIONS) {
            notifications.value.splice(MAX_NOTIFICATIONS);
        }
    };

    const setupPostListeners = (role?: string) => {
        if (postListenersReady) return;
        postListenersReady = true;

        const safeRole = typeof role === 'string' ? role.toLowerCase() : '';
        const channels: string[] = ['posts.all'];

        if (safeRole === 'admin') {
            channels.push('posts.hr');
            channels.push('posts.employee');
        } else if (safeRole === 'hr') {
            channels.push('posts.hr');
        } else {
            channels.push('posts.employee');
        }

        channels.forEach((channel) => {
            listen(
                channel,
                '.post.created',
                (e: { post?: any; actor_user_id?: number | null }) => {
                    const post = (e.post || {}) as any;
                    if (typeof post.id !== 'number') return;
                    lastPostEvent.value = {
                        type: 'post_created',
                        post,
                        actor_user_id:
                            typeof e.actor_user_id === 'number'
                                ? e.actor_user_id
                                : null,
                    };
                },
            );

            listen(
                channel,
                '.post.reaction.updated',
                (e: {
                    post_id: number;
                    reactions_count: number;
                    actor_user_id?: number | null;
                    actor_reaction?: string | null;
                }) => {
                    lastPostEvent.value = {
                        type: 'reaction_updated',
                        post_id: Number(e.post_id),
                        reactions_count: Number(e.reactions_count),
                        actor_user_id:
                            typeof e.actor_user_id === 'number'
                                ? e.actor_user_id
                                : null,
                        actor_reaction:
                            typeof e.actor_reaction === 'string'
                                ? e.actor_reaction
                                : null,
                    };
                },
            );

            listen(
                channel,
                '.post.comment.created',
                (e: {
                    post_id: number;
                    comments_count: number;
                    comment?: {
                        id: number;
                        body: string;
                        user_id: number;
                        created_at: string;
                    };
                }) => {
                    lastPostEvent.value = {
                        type: 'comment_created',
                        post_id: Number(e.post_id),
                        comments_count: Number(e.comments_count),
                        comment: e.comment,
                    };
                },
            );
        });
    };

    const markAsRead = async (id: string) => {
        const notif = notifications.value.find((n) => n.id === id);
        if (notif && !notif.read) {
            notif.read = true;
            bumpNotificationsUnread(-1);
            try {
                await fetch(
                    `${rolePrefix()}/notifications/${id}/mark-as-read`,
                    {
                        method: 'POST',
                        credentials: 'same-origin',
                        headers: {
                            Accept: 'application/json',
                            'X-CSRF-TOKEN': getCsrfToken(),
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    },
                );
            } catch {
                /* ignore */
            }
        }
    };

    const markAllAsRead = async () => {
        notifications.value.forEach((n) => (n.read = true));
        if (notificationsUnreadCount.value !== null) {
            notificationsUnreadCount.value = 0;
        }
        try {
            await fetch(`${rolePrefix()}/notifications/mark-all-read`, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                    'X-Requested-With': 'XMLHttpRequest',
                },
            });
        } catch {
            /* ignore */
        }
    };

    const deleteNotification = async (id: string) => {
        const index = notifications.value.findIndex((n) => n.id === id);
        if (index > -1) {
            notifications.value.splice(index, 1);
        }
        try {
            await fetch(`${rolePrefix()}/notifications/${id}`, {
                method: 'DELETE',
                headers: {
                    Accept: 'application/json',
                    'X-CSRF-TOKEN': getCsrfToken(),
                },
            });
        } catch {
            /* ignore */
        }
    };

    const initCountsFromPage = (
        pageAuthCounts: Record<string, unknown> | undefined,
    ) => {
        const base = (pageAuthCounts || {}) as Record<string, any>;

        if (
            usersPendingCount.value === null &&
            typeof base.users_pending === 'number'
        ) {
            usersPendingCount.value = base.users_pending;
        }
        if (
            notificationsUnreadCount.value === null &&
            typeof base.notifications_unread === 'number'
        ) {
            notificationsUnreadCount.value = base.notifications_unread;
        }
        if (
            leavesPendingCount.value === null &&
            typeof base.leaves_pending === 'number'
        ) {
            leavesPendingCount.value = base.leaves_pending;
        }
        if (
            trainingsAssignedCount.value === null &&
            typeof base.trainings_assigned === 'number'
        ) {
            trainingsAssignedCount.value = base.trainings_assigned;
        }
        if (
            pdsPendingCount.value === null &&
            typeof base.pds_pending === 'number'
        ) {
            pdsPendingCount.value = base.pds_pending;
        }
    };

    const loadInitialDropdownItems = async (role: string | undefined) => {
        try {
            const safeRole = typeof role === 'string' ? role.toLowerCase() : '';
            const notificationsUrl =
                safeRole === 'admin'
                    ? '/admin/notifications?only=notifications'
                    : safeRole === 'hr'
                      ? '/hr/notifications?only=notifications'
                      : '/employee/notifications?only=notifications';

            const notifResponse = await fetch(notificationsUrl, {
                headers: {
                    Accept: 'application/json',
                },
            });

            if (notifResponse.ok) {
                const json = await notifResponse.json();
                const items = (json?.props?.notifications?.data ||
                    []) as Array<any>;
                notifications.value = items.map((n, idx) => ({
                    id: String(n.id ?? idx + 1),
                    title: String(n.title ?? 'Notification'),
                    message: String(n.body ?? ''),
                    type:
                        n.type === 'success' ||
                        n.type === 'warning' ||
                        n.type === 'error' ||
                        n.type === 'info'
                            ? n.type
                            : 'info',
                    read: !!n.is_read,
                    created_at: String(
                        n.created_at ?? new Date().toISOString(),
                    ),
                    data: n.data ?? undefined,
                }));
            }

            if (safeRole === 'admin') {
                const r = await fetch('/admin/notifications/unread-count', {
                    headers: { Accept: 'application/json' },
                });
                if (r.ok) {
                    const j = await r.json();
                    if (
                        notificationsUnreadCount.value === null &&
                        typeof j.count === 'number'
                    ) {
                        notificationsUnreadCount.value = j.count;
                    }
                }
            } else if (safeRole === 'hr') {
                const r = await fetch('/hr/notifications/unread-count', {
                    headers: { Accept: 'application/json' },
                });
                if (r.ok) {
                    const j = await r.json();
                    if (
                        notificationsUnreadCount.value === null &&
                        typeof j.count === 'number'
                    ) {
                        notificationsUnreadCount.value = j.count;
                    }
                }
            } else {
                const r = await fetch('/employee/notifications/unread-count', {
                    headers: { Accept: 'application/json' },
                });
                if (r.ok) {
                    const j = await r.json();
                    if (
                        notificationsUnreadCount.value === null &&
                        typeof j.count === 'number'
                    ) {
                        notificationsUnreadCount.value = j.count;
                    }
                }
            }
        } catch {
            // ignore
        }
    };

    const refreshNotificationsDropdown = async (role: string | undefined) => {
        await loadInitialDropdownItems(role);
    };

    // Listen to user-specific notifications
    const setupUserListeners = (uid: number | string, role?: string) => {
        if (userListenersReady) return;
        userListenersReady = true;
        if (typeof uid === 'number') {
            currentAuthUserId.value = uid;
        } else {
            const parsed = Number(uid);
            currentAuthUserId.value = Number.isFinite(parsed) ? parsed : null;
        }
        // Store the role so API actions use the correct URL prefix
        if (typeof role === 'string') {
            currentUserRole.value = role.toLowerCase();
        }
        const safeRole = typeof role === 'string' ? role.toLowerCase() : '';
        const isAdmin = safeRole === 'admin';
        // Leave events
        listen(
            `App.Models.User.${uid}`,
            '.leave.submitted',
            (e: { message: string }) => {
                addNotification({
                    title: 'Leave Submitted',
                    message: e.message,
                    type: 'info',
                });
            },
        );

        if (isAdmin) {
            listen(
                'admin.dashboard',
                '.user.identity.updated',
                (e: { user?: Record<string, unknown> }) => {
                    const user = (e.user || {}) as any;
                    if (typeof user.id === 'number') {
                        lastUserManagementEvent.value = {
                            type: 'identity_updated',
                            user,
                        };
                    }
                },
            );
        }

        listen(
            `App.Models.User.${uid}`,
            '.leave.approved',
            (e: { message: string }) => {
                addNotification({
                    title: 'Leave Approved',
                    message: e.message,
                    type: 'success',
                });
            },
        );

        listen(
            `App.Models.User.${uid}`,
            '.leave.rejected',
            (e: { message: string }) => {
                addNotification({
                    title: 'Leave Rejected',
                    message: e.message,
                    type: 'error',
                });
            },
        );

        listen(
            `App.Models.User.${uid}`,
            '.leave.cancelled',
            (e: { message: string }) => {
                addNotification({
                    title: 'Leave Cancelled',
                    message: e.message,
                    type: 'warning',
                });
            },
        );

        // Training events
        listen(
            `App.Models.User.${uid}`,
            '.training.assigned',
            (e: { message: string }) => {
                addNotification({
                    title: 'Training Assigned',
                    message: e.message,
                    type: 'info',
                });
            },
        );

        listen(
            `App.Models.User.${uid}`,
            '.training.completed',
            (e: { message: string }) => {
                addNotification({
                    title: 'Training Completed',
                    message: e.message,
                    type: 'success',
                });
            },
        );

        // User events
        listen(
            `App.Models.User.${uid}`,
            '.user.approved',
            (e: { message: string }) => {
                addNotification({
                    title: 'Account Approved',
                    message: e.message,
                    type: 'success',
                });
            },
        );

        listen(
            `App.Models.User.${uid}`,
            '.user.rejected',
            (e: { message: string }) => {
                addNotification({
                    title: 'Account Rejected',
                    message: e.message,
                    type: 'error',
                });
            },
        );

        // Generic notifications
        try {
            const echoAny = (window as any)?.Echo;
            const channel = echoAny?.private?.(`App.Models.User.${uid}`);
            if (channel?.notification) {
                channel.notification((n: any) => {
                    const data = (n?.data || {}) as any;
                    const payload: NotificationPayload = {
                        title: String(data.title ?? 'Notification'),
                        message: String(data.message ?? data.body ?? ''),
                        type:
                            data.type === 'success' ||
                            data.type === 'warning' ||
                            data.type === 'error' ||
                            data.type === 'info'
                                ? data.type
                                : 'info',
                        data: (data.data ?? data) as Record<string, unknown>,
                        timestamp: String(
                            n?.created_at ?? new Date().toISOString(),
                        ),
                    };

                    const notification: RealTimeNotification = {
                        id: String(
                            n?.id ?? crypto?.randomUUID?.() ?? Date.now(),
                        ),
                        title: payload.title,
                        message: payload.message,
                        type: payload.type,
                        read: false,
                        created_at:
                            payload.timestamp || new Date().toISOString(),
                        data: payload.data,
                    };

                    notifications.value.unshift(notification);
                    if (notifications.value.length > MAX_NOTIFICATIONS) {
                        notifications.value.splice(MAX_NOTIFICATIONS);
                    }

                    bumpNotificationsUnread(1);
                });
            }
        } catch {
            // ignore
        }

        listen(
            `App.Models.User.${uid}`,
            '.notifications.unread.updated',
            (e: { count?: number }) => {
                if (typeof e?.count === 'number') {
                    notificationsUnreadCount.value = e.count;
                }
            },
        );
    };

    // Listen to admin dashboard
    const setupAdminListeners = (role?: string) => {
        if (adminListenersReady) return;
        adminListenersReady = true;
        const safeRole = typeof role === 'string' ? role.toLowerCase() : '';
        const isAdmin = safeRole === 'admin';
        if (!isAdmin) return;
        listen(
            'admin.dashboard',
            '.user.registered',
            (e: { message: string; user?: Record<string, unknown> }) => {
                addNotification({
                    title: 'New User Registered',
                    message: e.message,
                    type: 'info',
                });

                bumpPending(1);

                const user = (e.user || {}) as any;
                if (typeof user.id === 'number') {
                    lastUserManagementEvent.value = {
                        type: 'registered',
                        user,
                    };
                }
            },
        );

        listen(
            'admin.dashboard',
            '.user.approved',
            (e: { user?: Record<string, unknown> }) => {
                addNotification({
                    title: 'User Approved',
                    message: e.user?.name
                        ? `Approved: ${e.user.name}`
                        : 'A user account was approved.',
                    type: 'success',
                    data: { user: e.user },
                });

                bumpPending(-1);

                const user = (e.user || {}) as any;
                if (typeof user.id === 'number') {
                    lastUserManagementEvent.value = {
                        type: 'approved',
                        user,
                    };
                }
            },
        );

        listen(
            'admin.dashboard',
            '.user.rejected',
            (e: { user?: Record<string, unknown> }) => {
                addNotification({
                    title: 'User Rejected',
                    message: e.user?.name
                        ? `Rejected: ${e.user.name}`
                        : 'A user account was rejected.',
                    type: 'error',
                    data: { user: e.user },
                });

                bumpPending(-1);

                const user = (e.user || {}) as any;
                if (typeof user.id === 'number') {
                    lastUserManagementEvent.value = {
                        type: 'rejected',
                        user,
                    };
                }
            },
        );

        const isAdminOrHr = true;
        if (isAdminOrHr) {
            listen(
                'hr.dashboard',
                '.user.identity.updated',
                (e: { user?: Record<string, unknown> }) => {
                    const user = (e.user || {}) as any;
                    if (typeof user.id === 'number') {
                        lastUserManagementEvent.value = {
                            type: 'identity_updated',
                            user,
                        };
                    }
                },
            );
        }

        listen(
            'admin.dashboard',
            '.user.identity.updated',
            (e: { user?: Record<string, unknown> }) => {
                const user = (e.user || {}) as any;
                if (typeof user.id === 'number') {
                    lastUserManagementEvent.value = {
                        type: 'identity_updated',
                        user,
                    };
                }
            },
        );

        listen(
            'admin.dashboard',
            '.holiday.added',
            (e: { message: string }) => {
                addNotification({
                    title: 'Holiday Added',
                    message: e.message,
                    type: 'info',
                });
            },
        );

        listen(
            'admin.dashboard',
            '.holiday.updated',
            (e: { message: string }) => {
                addNotification({
                    title: 'Holiday Updated',
                    message: e.message,
                    type: 'warning',
                });
            },
        );

        listen(
            'employees',
            '.user.identity.updated',
            (e: { user?: Record<string, unknown> }) => {
                const user = (e.user || {}) as any;
                if (typeof user.id === 'number') {
                    lastUserManagementEvent.value = {
                        type: 'identity_updated',
                        user,
                    };
                }
            },
        );
    };

    // Listen to HR dashboard
    const setupHrListeners = (role?: string) => {
        if (hrListenersReady) return;
        hrListenersReady = true;
        const safeRole = typeof role === 'string' ? role.toLowerCase() : '';
        const isAdmin = safeRole === 'admin';
        const isHr = safeRole === 'hr';
        const isAdminOrHr = isAdmin || isHr;
        if (!isAdminOrHr) return;
        listen('hr.dashboard', '.leave.submitted', (e: { message: string }) => {
            addNotification({
                title: 'New Leave Application',
                message: e.message,
                type: 'info',
            });

            bumpLeavesPending(1);
        });

        listen(
            'hr.dashboard',
            '.user.registered',
            (e: { message: string; user?: Record<string, unknown> }) => {
                addNotification({
                    title: 'New User Registered',
                    message: e.message,
                    type: 'info',
                });

                bumpPending(1);

                const user = (e.user || {}) as any;
                if (typeof user.id === 'number') {
                    lastUserManagementEvent.value = {
                        type: 'registered',
                        user,
                    };
                }
            },
        );

        listen(
            'hr.dashboard',
            '.user.approved',
            (e: { user?: Record<string, unknown> }) => {
                addNotification({
                    title: 'User Approved',
                    message: e.user?.name
                        ? `Approved: ${e.user.name}`
                        : 'A user account was approved.',
                    type: 'success',
                    data: { user: e.user },
                });

                bumpPending(-1);

                const user = (e.user || {}) as any;
                if (typeof user.id === 'number') {
                    lastUserManagementEvent.value = {
                        type: 'approved',
                        user,
                    };
                }
            },
        );

        listen(
            'hr.dashboard',
            '.user.rejected',
            (e: { user?: Record<string, unknown> }) => {
                addNotification({
                    title: 'User Rejected',
                    message: e.user?.name
                        ? `Rejected: ${e.user.name}`
                        : 'A user account was rejected.',
                    type: 'error',
                    data: { user: e.user },
                });

                bumpPending(-1);

                const user = (e.user || {}) as any;
                if (typeof user.id === 'number') {
                    lastUserManagementEvent.value = {
                        type: 'rejected',
                        user,
                    };
                }
            },
        );

        listen('hr.dashboard', '.holiday.added', (e: { message: string }) => {
            addNotification({
                title: 'Holiday Added',
                message: e.message,
                type: 'info',
            });
        });

        listen('hr.dashboard', '.holiday.updated', (e: { message: string }) => {
            addNotification({
                title: 'Holiday Updated',
                message: e.message,
                type: 'warning',
            });
        });

        listen(
            'leave.management',
            '.LeaveStatusUpdated',
            (e: { status?: string }) => {
                if (e?.status === 'pending') bumpLeavesPending(1);
                if (e?.status === 'approved' || e?.status === 'rejected')
                    bumpLeavesPending(-1);
            },
        );

        listen(
            'training.management',
            '.TrainingStatusUpdated',
            (e: { status?: string }) => {
                if (e?.status === 'assigned') bumpTrainingsAssigned(1);
                if (
                    e?.status === 'approved' ||
                    e?.status === 'rejected' ||
                    e?.status === 'completed'
                )
                    bumpTrainingsAssigned(-1);
            },
        );

        listen(
            'pds.management',
            '.PdsStatusUpdated',
            (e: { status?: string }) => {
                if (e?.status === 'pending') bumpPdsPending(1);
                if (
                    e?.status === 'approved' ||
                    e?.status === 'rejected' ||
                    e?.status === 'draft' ||
                    e?.status === 'submitted'
                )
                    bumpPdsPending(-1);
            },
        );
    };

    // Listen to employee-wide broadcasts
    const setupEmployeeListeners = () => {
        if (employeeListenersReady) {
            console.log('[Echo] Employee listeners already set up, skipping');
            return;
        }
        employeeListenersReady = true;
        console.log(
            '[Echo] Setting up employee listeners on channel: employees',
        );
        listen('employees', '.holiday.added', (e: { message: string }) => {
            addNotification({
                title: 'New Holiday',
                message: e.message,
                type: 'info',
            });
        });

        listen(
            'employees',
            '.user.identity.updated',
            (e: { user?: Record<string, unknown> }) => {
                console.log(
                    '[Echo] Received identity update on employees channel:',
                    e,
                );
                const user = (e.user || {}) as any;
                if (typeof user.id === 'number') {
                    lastUserManagementEvent.value = {
                        type: 'identity_updated',
                        user,
                    };
                    addNotification({
                        title: 'Profile Updated',
                        message: user.name
                            ? `Your profile has been updated to ${user.name}`
                            : 'Your profile has been updated',
                        type: 'info',
                    });
                }
            },
        );
    };

    return {
        notifications,
        unreadCount,
        usersPendingCount,
        notificationsUnreadCount,
        loadInitialDropdownItems,
        refreshNotificationsDropdown,
        leavesPendingCount,
        trainingsAssignedCount,
        pdsPendingCount,
        lastUserManagementEvent,
        addNotification,
        markAsRead,
        markAllAsRead,
        deleteNotification,
        initCountsFromPage,
        setupUserListeners,
        setupAdminListeners,
        setupHrListeners,
        setupEmployeeListeners,
        setupPostListeners,
        lastPostEvent,
    };
}
