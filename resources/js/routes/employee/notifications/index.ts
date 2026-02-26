import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:45
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
export const markAsRead = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead.url(args, options),
    method: 'post',
})

markAsRead.definition = {
    methods: ["post"],
    url: '/employee/notifications/{noticeId}/mark-as-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:45
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
markAsRead.url = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { noticeId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    noticeId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        noticeId: args.noticeId,
                }

    return markAsRead.definition.url
            .replace('{noticeId}', parsedArgs.noticeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:45
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
markAsRead.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:45
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
    const markAsReadForm = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAsRead.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:45
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
        markAsReadForm.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAsRead.url(args, options),
            method: 'post',
        })
    
    markAsRead.form = markAsReadForm
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:66
 * @route '/employee/notifications/unread-count'
 */
export const unreadCount = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unreadCount.url(options),
    method: 'get',
})

unreadCount.definition = {
    methods: ["get","head"],
    url: '/employee/notifications/unread-count',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:66
 * @route '/employee/notifications/unread-count'
 */
unreadCount.url = (options?: RouteQueryOptions) => {
    return unreadCount.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:66
 * @route '/employee/notifications/unread-count'
 */
unreadCount.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unreadCount.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:66
 * @route '/employee/notifications/unread-count'
 */
unreadCount.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: unreadCount.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:66
 * @route '/employee/notifications/unread-count'
 */
    const unreadCountForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: unreadCount.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:66
 * @route '/employee/notifications/unread-count'
 */
        unreadCountForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: unreadCount.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:66
 * @route '/employee/notifications/unread-count'
 */
        unreadCountForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: unreadCount.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    unreadCount.form = unreadCountForm
const notifications = {
    markAsRead: Object.assign(markAsRead, markAsRead),
unreadCount: Object.assign(unreadCount, unreadCount),
}

export default notifications