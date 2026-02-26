import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

// Connection error handling
if (window.Echo && window.Echo.connector && window.Echo.connector.pusher) {
    const conn = window.Echo.connector.pusher.connection;

    conn.bind('error', function (err) {
        console.warn('[Echo] Connection error:', err);
    });

    conn.bind('disconnected', function () {
        console.warn('[Echo] Disconnected — will attempt auto-reconnect');
    });

    conn.bind('connected', function () {
        console.info('[Echo] Connected to Reverb');
    });
}

const userId = typeof window.HRIS_USER_ID === 'string' ? window.HRIS_USER_ID : null;
const role = typeof window.HRIS_ROLE === 'string' ? window.HRIS_ROLE.toLowerCase() : null;

if (window.Echo && role) {
    window.Echo.private(`role.${role}`)
        .listen('.LeaveStatusUpdated', (event) => {
            window.dispatchEvent(new CustomEvent('realtime:leave-status-updated', { detail: event }));
        })
        .listen('.TrainingStatusUpdated', (event) => {
            window.dispatchEvent(new CustomEvent('realtime:training-status-updated', { detail: event }));
        })
        .listen('.NoticePublished', (event) => {
            window.dispatchEvent(new CustomEvent('realtime:notice-published', { detail: event }));
        });

    if (role === 'hr') {
        window.Echo.private('leave.management')
            .listen('.LeaveStatusUpdated', (event) => {
                window.dispatchEvent(new CustomEvent('realtime:leave-status-updated', { detail: event }));
            });

        window.Echo.private('training.management')
            .listen('.TrainingStatusUpdated', (event) => {
                window.dispatchEvent(new CustomEvent('realtime:training-status-updated', { detail: event }));
            });

        window.Echo.private('calendar.holidays')
            .listen('.CustomHolidayCreated', (event) => {
                window.dispatchEvent(new CustomEvent('realtime:calendar-holidays-updated', { detail: event }));
            })
            .listen('.CustomHolidayUpdated', (event) => {
                window.dispatchEvent(new CustomEvent('realtime:calendar-holidays-updated', { detail: event }));
            })
            .listen('.CustomHolidayDeleted', (event) => {
                window.dispatchEvent(new CustomEvent('realtime:calendar-holidays-updated', { detail: event }));
            });
    }
}

if (window.Echo && userId) {
    window.Echo.private(`users.${userId}`)
        .listen('.NoticePublished', (event) => {
            window.dispatchEvent(new CustomEvent('realtime:notice-published', { detail: event }));
        });
}
