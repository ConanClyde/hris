import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import calendarFa95d0 from './calendar'
import notifications1ce82a from './notifications'
import profile937a89 from './profile'
import pds from './pds'
import users from './users'
import training from './training'
import leaveApplications from './leave-applications'
import leaveAttachments from './leave-attachments'
import leaveCredits from './leave-credits'
import notices from './notices'
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/hr/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
    const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
        dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
        dashboardForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    dashboard.form = dashboardForm
/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
export const calendar = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: calendar.url(options),
    method: 'get',
})

calendar.definition = {
    methods: ["get","head"],
    url: '/hr/calendar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
calendar.url = (options?: RouteQueryOptions) => {
    return calendar.definition.url + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
calendar.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: calendar.url(options),
    method: 'get',
})
/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
calendar.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: calendar.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
    const calendarForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: calendar.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
        calendarForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: calendar.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
        calendarForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: calendar.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    calendar.form = calendarForm
/**
 * @see routes/web/hr.php:23
 * @route '/hr/reports'
 */
export const reports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})

reports.definition = {
    methods: ["get","head"],
    url: '/hr/reports',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web/hr.php:23
 * @route '/hr/reports'
 */
reports.url = (options?: RouteQueryOptions) => {
    return reports.definition.url + queryParams(options)
}

/**
 * @see routes/web/hr.php:23
 * @route '/hr/reports'
 */
reports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})
/**
 * @see routes/web/hr.php:23
 * @route '/hr/reports'
 */
reports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reports.url(options),
    method: 'head',
})

    /**
 * @see routes/web/hr.php:23
 * @route '/hr/reports'
 */
    const reportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: reports.url(options),
        method: 'get',
    })

            /**
 * @see routes/web/hr.php:23
 * @route '/hr/reports'
 */
        reportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: reports.url(options),
            method: 'get',
        })
            /**
 * @see routes/web/hr.php:23
 * @route '/hr/reports'
 */
        reportsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: reports.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    reports.form = reportsForm
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:13
 * @route '/hr/notifications'
 */
export const notifications = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notifications.url(options),
    method: 'get',
})

notifications.definition = {
    methods: ["get","head"],
    url: '/hr/notifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:13
 * @route '/hr/notifications'
 */
notifications.url = (options?: RouteQueryOptions) => {
    return notifications.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:13
 * @route '/hr/notifications'
 */
notifications.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notifications.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:13
 * @route '/hr/notifications'
 */
notifications.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: notifications.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:13
 * @route '/hr/notifications'
 */
    const notificationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: notifications.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:13
 * @route '/hr/notifications'
 */
        notificationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notifications.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:13
 * @route '/hr/notifications'
 */
        notificationsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notifications.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    notifications.form = notificationsForm
/**
 * @see routes/web/hr.php:33
 * @route '/hr/settings'
 */
export const settings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})

settings.definition = {
    methods: ["get","head"],
    url: '/hr/settings',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web/hr.php:33
 * @route '/hr/settings'
 */
settings.url = (options?: RouteQueryOptions) => {
    return settings.definition.url + queryParams(options)
}

/**
 * @see routes/web/hr.php:33
 * @route '/hr/settings'
 */
settings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})
/**
 * @see routes/web/hr.php:33
 * @route '/hr/settings'
 */
settings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: settings.url(options),
    method: 'head',
})

    /**
 * @see routes/web/hr.php:33
 * @route '/hr/settings'
 */
    const settingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: settings.url(options),
        method: 'get',
    })

            /**
 * @see routes/web/hr.php:33
 * @route '/hr/settings'
 */
        settingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: settings.url(options),
            method: 'get',
        })
            /**
 * @see routes/web/hr.php:33
 * @route '/hr/settings'
 */
        settingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: settings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    settings.form = settingsForm
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
export const profile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})

profile.definition = {
    methods: ["get","head"],
    url: '/hr/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
profile.url = (options?: RouteQueryOptions) => {
    return profile.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
profile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
profile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: profile.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
    const profileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: profile.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
        profileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: profile.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
        profileForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: profile.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    profile.form = profileForm
const hr = {
    dashboard: Object.assign(dashboard, dashboard),
calendar: Object.assign(calendar, calendarFa95d0),
reports: Object.assign(reports, reports),
notifications: Object.assign(notifications, notifications1ce82a),
settings: Object.assign(settings, settings),
profile: Object.assign(profile, profile937a89),
pds: Object.assign(pds, pds),
users: Object.assign(users, users),
training: Object.assign(training, training),
leaveApplications: Object.assign(leaveApplications, leaveApplications),
leaveAttachments: Object.assign(leaveAttachments, leaveAttachments),
leaveCredits: Object.assign(leaveCredits, leaveCredits),
notices: Object.assign(notices, notices),
}

export default hr