import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
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
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
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
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
markAsRead.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
    const markAsReadForm = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAsRead.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
        markAsReadForm.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAsRead.url(args, options),
            method: 'post',
        })
    
    markAsRead.form = markAsReadForm
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
export const markAllRead = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllRead.url(options),
    method: 'post',
})

markAllRead.definition = {
    methods: ["post"],
    url: '/employee/notifications/mark-all-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
markAllRead.url = (options?: RouteQueryOptions) => {
    return markAllRead.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
markAllRead.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllRead.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
    const markAllReadForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAllRead.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
        markAllReadForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAllRead.url(options),
            method: 'post',
        })
    
    markAllRead.form = markAllReadForm
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
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
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
unreadCount.url = (options?: RouteQueryOptions) => {
    return unreadCount.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
unreadCount.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unreadCount.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
unreadCount.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: unreadCount.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
    const unreadCountForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: unreadCount.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
        unreadCountForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: unreadCount.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
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
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/employee/notifications/{noticeId}'
 */
export const destroy = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/employee/notifications/{noticeId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/employee/notifications/{noticeId}'
 */
destroy.url = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{noticeId}', parsedArgs.noticeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/employee/notifications/{noticeId}'
 */
destroy.delete = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/employee/notifications/{noticeId}'
 */
    const destroyForm = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/employee/notifications/{noticeId}'
 */
        destroyForm.delete = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/employee/notifications'
 */
export const destroyAll = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAll.url(options),
    method: 'delete',
})

destroyAll.definition = {
    methods: ["delete"],
    url: '/employee/notifications',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/employee/notifications'
 */
destroyAll.url = (options?: RouteQueryOptions) => {
    return destroyAll.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/employee/notifications'
 */
destroyAll.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAll.url(options),
    method: 'delete',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/employee/notifications'
 */
    const destroyAllForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroyAll.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/employee/notifications'
 */
        destroyAllForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroyAll.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroyAll.form = destroyAllForm
const notifications = {
    markAsRead: Object.assign(markAsRead, markAsRead),
markAllRead: Object.assign(markAllRead, markAllRead),
unreadCount: Object.assign(unreadCount, unreadCount),
destroy: Object.assign(destroy, destroy),
destroyAll: Object.assign(destroyAll, destroyAll),
}

export default notifications