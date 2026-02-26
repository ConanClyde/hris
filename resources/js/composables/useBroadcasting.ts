import { useEcho } from '@laravel/echo-vue';
import { ref, computed } from 'vue';

export interface NotificationPayload {
    title: string;
    message: string;
    type: 'info' | 'success' | 'warning' | 'error';
    data?: Record<string, unknown>;
    timestamp?: string;
}

export interface RealTimeNotification {
    id: number;
    title: string;
    message: string;
    type: 'info' | 'success' | 'warning' | 'error';
    read: boolean;
    created_at: string;
    data?: Record<string, unknown>;
}

const usersPendingCount = ref<number | null>(null);
const noticesUnreadCount = ref<number | null>(null);
const leavesPendingCount = ref<number | null>(null);
const trainingsAssignedCount = ref<number | null>(null);
const pdsPendingCount = ref<number | null>(null);

type UserManagementEventType = 'registered' | 'approved' | 'rejected';
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
    };
};

const lastUserManagementEvent = ref<UserManagementEvent | null>(null);

const MAX_NOTIFICATIONS = 100;
let userListenersReady = false;
let adminListenersReady = false;
let hrListenersReady = false;
let employeeListenersReady = false;

function bumpPending(delta: number) {
    const current = usersPendingCount.value;
    if (current === null) return;
    usersPendingCount.value = Math.max(0, current + delta);
}

function bumpNoticesUnread(delta: number) {
    const current = noticesUnreadCount.value;
    if (current === null) return;
    noticesUnreadCount.value = Math.max(0, current + delta);
}

function markOneNoticeReadLocally() {
    bumpNoticesUnread(-1);
}

function markAllNoticesReadLocally() {
    if (noticesUnreadCount.value === null) return;
    noticesUnreadCount.value = 0;
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
    const notifications = ref<RealTimeNotification[]>([]);
    const unreadCount = computed(() => notifications.value.filter(n => !n.read).length);

    let notifId = 1;

    const addNotification = (payload: NotificationPayload) => {
        const notification: RealTimeNotification = {
            id: notifId++,
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

    const markAsRead = (id: number) => {
        const notif = notifications.value.find(n => n.id === id);
        if (notif) {
            notif.read = true;
        }
    };

    const markAllAsRead = () => {
        notifications.value.forEach(n => n.read = true);
    };

    const deleteNotification = (id: number) => {
        const index = notifications.value.findIndex(n => n.id === id);
        if (index > -1) {
            notifications.value.splice(index, 1);
        }
    };

    // Listen to user-specific notifications
    const setupUserListeners = (uid: number | string) => {
        if (userListenersReady) return;
        userListenersReady = true;
        // Leave events
        useEcho(
            `App.Models.User.${uid}`,
            '.leave.submitted',
            (e: { message: string }) => {
                addNotification({
                    title: 'Leave Submitted',
                    message: e.message,
                    type: 'info',
                });
            }
        );

        useEcho(
            `App.Models.User.${uid}`,
            '.leave.approved',
            (e: { message: string }) => {
                addNotification({
                    title: 'Leave Approved',
                    message: e.message,
                    type: 'success',
                });
            }
        );

        useEcho(
            `App.Models.User.${uid}`,
            '.leave.rejected',
            (e: { message: string }) => {
                addNotification({
                    title: 'Leave Rejected',
                    message: e.message,
                    type: 'error',
                });
            }
        );

        useEcho(
            `App.Models.User.${uid}`,
            '.leave.cancelled',
            (e: { message: string }) => {
                addNotification({
                    title: 'Leave Cancelled',
                    message: e.message,
                    type: 'warning',
                });
            }
        );

        // Training events
        useEcho(
            `App.Models.User.${uid}`,
            '.training.assigned',
            (e: { message: string }) => {
                addNotification({
                    title: 'Training Assigned',
                    message: e.message,
                    type: 'info',
                });
            }
        );

        useEcho(
            `App.Models.User.${uid}`,
            '.training.completed',
            (e: { message: string }) => {
                addNotification({
                    title: 'Training Completed',
                    message: e.message,
                    type: 'success',
                });
            }
        );

        // User events
        useEcho(
            `App.Models.User.${uid}`,
            '.user.approved',
            (e: { message: string }) => {
                addNotification({
                    title: 'Account Approved',
                    message: e.message,
                    type: 'success',
                });
            }
        );

        useEcho(
            `App.Models.User.${uid}`,
            '.user.rejected',
            (e: { message: string }) => {
                addNotification({
                    title: 'Account Rejected',
                    message: e.message,
                    type: 'error',
                });
            }
        );

        // Generic notifications
        useEcho(
            `App.Models.User.${uid}`,
            '.notification.received',
            (e: NotificationPayload) => {
                addNotification(e);
            }
        );
    };

    // Listen to admin dashboard
    const setupAdminListeners = () => {
        if (adminListenersReady) return;
        adminListenersReady = true;
        useEcho(
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
            }
        );

        useEcho(
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
            }
        );

        useEcho(
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
            }
        );

        useEcho(
            'admin.dashboard',
            '.holiday.added',
            (e: { message: string }) => {
                addNotification({
                    title: 'Holiday Added',
                    message: e.message,
                    type: 'info',
                });
            }
        );

        useEcho(
            'admin.dashboard',
            '.holiday.updated',
            (e: { message: string }) => {
                addNotification({
                    title: 'Holiday Updated',
                    message: e.message,
                    type: 'warning',
                });
            }
        );

        useEcho(
            'admin.dashboard',
            '.notice.created',
            (e: { message: string; type: 'info' | 'success' | 'warning' | 'error'; notice?: { title: string } }) => {
                addNotification({
                    title: e.notice?.title || 'New Notice',
                    message: e.message,
                    type: e.type,
                });

                bumpNoticesUnread(1);
            }
        );

        useEcho(
            'admin.dashboard',
            '.notice.updated',
            (e: { message: string; notice?: { title: string } }) => {
                addNotification({
                    title: 'Notice Updated',
                    message: e.message,
                    type: 'warning',
                });
            }
        );

        useEcho(
            'admin.dashboard',
            '.notice.deleted',
            (e: { message: string }) => {
                addNotification({
                    title: 'Notice Removed',
                    message: e.message,
                    type: 'warning',
                });
            }
        );
    };

    // Listen to HR dashboard
    const setupHrListeners = () => {
        if (hrListenersReady) return;
        hrListenersReady = true;
        useEcho(
            'hr.dashboard',
            '.leave.submitted',
            (e: { message: string }) => {
                addNotification({
                    title: 'New Leave Application',
                    message: e.message,
                    type: 'info',
                });

                bumpLeavesPending(1);
            }
        );

        useEcho(
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
            }
        );

        useEcho(
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
            }
        );

        useEcho(
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
            }
        );

        useEcho(
            'hr.dashboard',
            '.holiday.added',
            (e: { message: string }) => {
                addNotification({
                    title: 'Holiday Added',
                    message: e.message,
                    type: 'info',
                });
            }
        );

        useEcho(
            'hr.dashboard',
            '.notice.created',
            (e: { message: string; type: 'info' | 'success' | 'warning' | 'error'; notice?: { title: string } }) => {
                addNotification({
                    title: e.notice?.title || 'New Notice',
                    message: e.message,
                    type: e.type,
                });

                bumpNoticesUnread(1);
            }
        );

        useEcho(
            'hr.dashboard',
            '.notice.updated',
            (e: { message: string; notice?: { title: string } }) => {
                addNotification({
                    title: 'Notice Updated',
                    message: e.message,
                    type: 'warning',
                });
            }
        );

        useEcho(
            'hr.dashboard',
            '.notice.deleted',
            (e: { message: string }) => {
                addNotification({
                    title: 'Notice Removed',
                    message: e.message,
                    type: 'warning',
                });
            }
        );
    };

    // Listen to employee-wide broadcasts
    const setupEmployeeListeners = () => {
        if (employeeListenersReady) return;
        employeeListenersReady = true;
        useEcho(
            'employees',
            '.holiday.added',
            (e: { message: string }) => {
                addNotification({
                    title: 'New Holiday',
                    message: e.message,
                    type: 'info',
                });
            }
        );

        // Notice events
        useEcho(
            'employees',
            '.notice.created',
            (e: { message: string; type: 'info' | 'success' | 'warning' | 'error'; notice?: { title: string } }) => {
                addNotification({
                    title: e.notice?.title || 'New Notice',
                    message: e.message,
                    type: e.type,
                });

                bumpNoticesUnread(1);
            }
        );

        useEcho(
            'employees',
            '.notice.updated',
            (e: { message: string; notice?: { title: string } }) => {
                addNotification({
                    title: 'Notice Updated',
                    message: e.message,
                    type: 'warning',
                });
            }
        );

        useEcho(
            'employees',
            '.notice.deleted',
            (e: { message: string }) => {
                addNotification({
                    title: 'Notice Removed',
                    message: e.message,
                    type: 'warning',
                });
            }
        );

        useEcho(
            'leave.management',
            '.LeaveStatusUpdated',
            (e: { status?: string }) => {
                if (e?.status === 'pending') bumpLeavesPending(1);
                if (e?.status === 'approved' || e?.status === 'rejected') bumpLeavesPending(-1);
            }
        );

        useEcho(
            'training.management',
            '.TrainingStatusUpdated',
            (e: { status?: string }) => {
                if (e?.status === 'assigned') bumpTrainingsAssigned(1);
                if (e?.status === 'approved' || e?.status === 'rejected' || e?.status === 'completed') bumpTrainingsAssigned(-1);
            }
        );

        useEcho(
            'pds.management',
            '.PdsStatusUpdated',
            (e: { status?: string }) => {
                if (e?.status === 'pending') bumpPdsPending(1);
                if (e?.status === 'approved' || e?.status === 'rejected' || e?.status === 'draft' || e?.status === 'submitted') bumpPdsPending(-1);
            }
        );
    };

    return {
        notifications,
        unreadCount,
        usersPendingCount,
        noticesUnreadCount,
        leavesPendingCount,
        trainingsAssignedCount,
        pdsPendingCount,
        markOneNoticeReadLocally,
        markAllNoticesReadLocally,
        lastUserManagementEvent,
        addNotification,
        markAsRead,
        markAllAsRead,
        deleteNotification,
        setupUserListeners,
        setupAdminListeners,
        setupHrListeners,
        setupEmployeeListeners,
    };
}
