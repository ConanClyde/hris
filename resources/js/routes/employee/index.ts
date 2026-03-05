import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import pds from './pds'
import calendarFa95d0 from './calendar'
import notifications1ce82a from './notifications'
import posts from './posts'
import activityLogs from './activity-logs'
import profile937a89 from './profile'
import training from './training'
import leaveApplications from './leave-applications'
import leaveAttachments from './leave-attachments'
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:100
 * @route '/employee/dashboard'
 */
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/employee/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:100
 * @route '/employee/dashboard'
 */
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:100
 * @route '/employee/dashboard'
 */
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:100
 * @route '/employee/dashboard'
 */
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:100
 * @route '/employee/dashboard'
 */
    const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:100
 * @route '/employee/dashboard'
 */
        dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:100
 * @route '/employee/dashboard'
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
* @see \App\Features\Calendar\Http\Controllers\Employee\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Employee/CalendarController.php:19
 * @route '/employee/calendar'
 */
export const calendar = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: calendar.url(options),
    method: 'get',
})

calendar.definition = {
    methods: ["get","head"],
    url: '/employee/calendar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Calendar\Http\Controllers\Employee\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Employee/CalendarController.php:19
 * @route '/employee/calendar'
 */
calendar.url = (options?: RouteQueryOptions) => {
    return calendar.definition.url + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\Employee\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Employee/CalendarController.php:19
 * @route '/employee/calendar'
 */
calendar.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: calendar.url(options),
    method: 'get',
})
/**
* @see \App\Features\Calendar\Http\Controllers\Employee\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Employee/CalendarController.php:19
 * @route '/employee/calendar'
 */
calendar.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: calendar.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\Employee\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Employee/CalendarController.php:19
 * @route '/employee/calendar'
 */
    const calendarForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: calendar.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\Employee\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Employee/CalendarController.php:19
 * @route '/employee/calendar'
 */
        calendarForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: calendar.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Calendar\Http\Controllers\Employee\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Employee/CalendarController.php:19
 * @route '/employee/calendar'
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
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
export const notifications = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notifications.url(options),
    method: 'get',
})

notifications.definition = {
    methods: ["get","head"],
    url: '/employee/notifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
notifications.url = (options?: RouteQueryOptions) => {
    return notifications.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
notifications.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notifications.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
notifications.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: notifications.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
    const notificationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: notifications.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
        notificationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notifications.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
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
 * @see routes/web/employee.php:38
 * @route '/employee/settings'
 */
export const settings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})

settings.definition = {
    methods: ["get","head"],
    url: '/employee/settings',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web/employee.php:38
 * @route '/employee/settings'
 */
settings.url = (options?: RouteQueryOptions) => {
    return settings.definition.url + queryParams(options)
}

/**
 * @see routes/web/employee.php:38
 * @route '/employee/settings'
 */
settings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})
/**
 * @see routes/web/employee.php:38
 * @route '/employee/settings'
 */
settings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: settings.url(options),
    method: 'head',
})

    /**
 * @see routes/web/employee.php:38
 * @route '/employee/settings'
 */
    const settingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: settings.url(options),
        method: 'get',
    })

            /**
 * @see routes/web/employee.php:38
 * @route '/employee/settings'
 */
        settingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: settings.url(options),
            method: 'get',
        })
            /**
 * @see routes/web/employee.php:38
 * @route '/employee/settings'
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
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:18
 * @route '/employee/profile'
 */
export const profile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})

profile.definition = {
    methods: ["get","head"],
    url: '/employee/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:18
 * @route '/employee/profile'
 */
profile.url = (options?: RouteQueryOptions) => {
    return profile.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:18
 * @route '/employee/profile'
 */
profile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:18
 * @route '/employee/profile'
 */
profile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: profile.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:18
 * @route '/employee/profile'
 */
    const profileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: profile.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:18
 * @route '/employee/profile'
 */
        profileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: profile.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:18
 * @route '/employee/profile'
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
const employee = {
    dashboard: Object.assign(dashboard, dashboard),
pds: Object.assign(pds, pds),
calendar: Object.assign(calendar, calendarFa95d0),
notifications: Object.assign(notifications, notifications1ce82a),
posts: Object.assign(posts, posts),
activityLogs: Object.assign(activityLogs, activityLogs),
settings: Object.assign(settings, settings),
profile: Object.assign(profile, profile937a89),
training: Object.assign(training, training),
leaveApplications: Object.assign(leaveApplications, leaveApplications),
leaveAttachments: Object.assign(leaveAttachments, leaveAttachments),
}

export default employee