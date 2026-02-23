import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
import calendarFa95d0 from './calendar'
import customHolidays from './custom-holidays'
import users48860f from './users'
import activityLogs from './activity-logs'
import performance from './performance'
import backup from './backup'
import notices from './notices'
import profile937a89 from './profile'
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
export const dashboard = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})

dashboard.definition = {
    methods: ["get","head"],
    url: '/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
dashboard.url = (options?: RouteQueryOptions) => {
    return dashboard.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
dashboard.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: dashboard.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
dashboard.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: dashboard.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
    const dashboardForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: dashboard.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
        dashboardForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: dashboard.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::dashboard
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
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
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
export const calendar = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: calendar.url(options),
    method: 'get',
})

calendar.definition = {
    methods: ["get","head"],
    url: '/admin/calendar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
calendar.url = (options?: RouteQueryOptions) => {
    return calendar.definition.url + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
calendar.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: calendar.url(options),
    method: 'get',
})
/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
calendar.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: calendar.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
    const calendarForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: calendar.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
        calendarForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: calendar.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::calendar
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
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
* @see \App\Features\Users\Http\Controllers\Admin\UserController::users
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
export const users = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(options),
    method: 'get',
})

users.definition = {
    methods: ["get","head"],
    url: '/admin/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::users
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
users.url = (options?: RouteQueryOptions) => {
    return users.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::users
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
users.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(options),
    method: 'get',
})
/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::users
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
users.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: users.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::users
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
    const usersForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: users.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::users
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
        usersForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: users.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::users
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
        usersForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: users.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    users.form = usersForm
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
export const notifications = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notifications.url(options),
    method: 'get',
})

notifications.definition = {
    methods: ["get","head"],
    url: '/admin/notifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
notifications.url = (options?: RouteQueryOptions) => {
    return notifications.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
notifications.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notifications.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
notifications.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: notifications.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
    const notificationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: notifications.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
        notificationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notifications.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::notifications
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
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
 * @see routes/web/admin.php:70
 * @route '/admin/settings'
 */
export const settings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})

settings.definition = {
    methods: ["get","head"],
    url: '/admin/settings',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web/admin.php:70
 * @route '/admin/settings'
 */
settings.url = (options?: RouteQueryOptions) => {
    return settings.definition.url + queryParams(options)
}

/**
 * @see routes/web/admin.php:70
 * @route '/admin/settings'
 */
settings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: settings.url(options),
    method: 'get',
})
/**
 * @see routes/web/admin.php:70
 * @route '/admin/settings'
 */
settings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: settings.url(options),
    method: 'head',
})

    /**
 * @see routes/web/admin.php:70
 * @route '/admin/settings'
 */
    const settingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: settings.url(options),
        method: 'get',
    })

            /**
 * @see routes/web/admin.php:70
 * @route '/admin/settings'
 */
        settingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: settings.url(options),
            method: 'get',
        })
            /**
 * @see routes/web/admin.php:70
 * @route '/admin/settings'
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
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:14
 * @route '/admin/profile'
 */
export const profile = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})

profile.definition = {
    methods: ["get","head"],
    url: '/admin/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:14
 * @route '/admin/profile'
 */
profile.url = (options?: RouteQueryOptions) => {
    return profile.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:14
 * @route '/admin/profile'
 */
profile.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: profile.url(options),
    method: 'get',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:14
 * @route '/admin/profile'
 */
profile.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: profile.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:14
 * @route '/admin/profile'
 */
    const profileForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: profile.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:14
 * @route '/admin/profile'
 */
        profileForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: profile.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::profile
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:14
 * @route '/admin/profile'
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
/**
 * @see routes/web/admin.php:81
 * @route '/admin/leave'
 */
export const leave = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leave.url(options),
    method: 'get',
})

leave.definition = {
    methods: ["get","head"],
    url: '/admin/leave',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web/admin.php:81
 * @route '/admin/leave'
 */
leave.url = (options?: RouteQueryOptions) => {
    return leave.definition.url + queryParams(options)
}

/**
 * @see routes/web/admin.php:81
 * @route '/admin/leave'
 */
leave.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leave.url(options),
    method: 'get',
})
/**
 * @see routes/web/admin.php:81
 * @route '/admin/leave'
 */
leave.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: leave.url(options),
    method: 'head',
})

    /**
 * @see routes/web/admin.php:81
 * @route '/admin/leave'
 */
    const leaveForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: leave.url(options),
        method: 'get',
    })

            /**
 * @see routes/web/admin.php:81
 * @route '/admin/leave'
 */
        leaveForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leave.url(options),
            method: 'get',
        })
            /**
 * @see routes/web/admin.php:81
 * @route '/admin/leave'
 */
        leaveForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leave.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    leave.form = leaveForm
/**
 * @see routes/web/admin.php:86
 * @route '/admin/reports'
 */
export const reports = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})

reports.definition = {
    methods: ["get","head"],
    url: '/admin/reports',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web/admin.php:86
 * @route '/admin/reports'
 */
reports.url = (options?: RouteQueryOptions) => {
    return reports.definition.url + queryParams(options)
}

/**
 * @see routes/web/admin.php:86
 * @route '/admin/reports'
 */
reports.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: reports.url(options),
    method: 'get',
})
/**
 * @see routes/web/admin.php:86
 * @route '/admin/reports'
 */
reports.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: reports.url(options),
    method: 'head',
})

    /**
 * @see routes/web/admin.php:86
 * @route '/admin/reports'
 */
    const reportsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: reports.url(options),
        method: 'get',
    })

            /**
 * @see routes/web/admin.php:86
 * @route '/admin/reports'
 */
        reportsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: reports.url(options),
            method: 'get',
        })
            /**
 * @see routes/web/admin.php:86
 * @route '/admin/reports'
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
 * @see routes/web/admin.php:92
 * @route '/admin/pds'
 */
export const pds = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pds.url(options),
    method: 'get',
})

pds.definition = {
    methods: ["get","head"],
    url: '/admin/pds',
} satisfies RouteDefinition<["get","head"]>

/**
 * @see routes/web/admin.php:92
 * @route '/admin/pds'
 */
pds.url = (options?: RouteQueryOptions) => {
    return pds.definition.url + queryParams(options)
}

/**
 * @see routes/web/admin.php:92
 * @route '/admin/pds'
 */
pds.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: pds.url(options),
    method: 'get',
})
/**
 * @see routes/web/admin.php:92
 * @route '/admin/pds'
 */
pds.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: pds.url(options),
    method: 'head',
})

    /**
 * @see routes/web/admin.php:92
 * @route '/admin/pds'
 */
    const pdsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: pds.url(options),
        method: 'get',
    })

            /**
 * @see routes/web/admin.php:92
 * @route '/admin/pds'
 */
        pdsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pds.url(options),
            method: 'get',
        })
            /**
 * @see routes/web/admin.php:92
 * @route '/admin/pds'
 */
        pdsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: pds.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    pds.form = pdsForm
const admin = {
    dashboard: Object.assign(dashboard, dashboard),
calendar: Object.assign(calendar, calendarFa95d0),
customHolidays: Object.assign(customHolidays, customHolidays),
users: Object.assign(users, users48860f),
activityLogs: Object.assign(activityLogs, activityLogs),
performance: Object.assign(performance, performance),
backup: Object.assign(backup, backup),
notices: Object.assign(notices, notices),
notifications: Object.assign(notifications, notifications),
settings: Object.assign(settings, settings),
profile: Object.assign(profile, profile937a89),
leave: Object.assign(leave, leave),
reports: Object.assign(reports, reports),
pds: Object.assign(pds, pds),
}

export default admin