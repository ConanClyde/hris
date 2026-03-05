import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/admin/notifications'
 */
const index24beda923a32e093a2b98b10d0d96642 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index24beda923a32e093a2b98b10d0d96642.url(options),
    method: 'get',
})

index24beda923a32e093a2b98b10d0d96642.definition = {
    methods: ["get","head"],
    url: '/admin/notifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/admin/notifications'
 */
index24beda923a32e093a2b98b10d0d96642.url = (options?: RouteQueryOptions) => {
    return index24beda923a32e093a2b98b10d0d96642.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/admin/notifications'
 */
index24beda923a32e093a2b98b10d0d96642.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index24beda923a32e093a2b98b10d0d96642.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/admin/notifications'
 */
index24beda923a32e093a2b98b10d0d96642.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index24beda923a32e093a2b98b10d0d96642.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/admin/notifications'
 */
    const index24beda923a32e093a2b98b10d0d96642Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index24beda923a32e093a2b98b10d0d96642.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/admin/notifications'
 */
        index24beda923a32e093a2b98b10d0d96642Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index24beda923a32e093a2b98b10d0d96642.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/admin/notifications'
 */
        index24beda923a32e093a2b98b10d0d96642Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index24beda923a32e093a2b98b10d0d96642.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index24beda923a32e093a2b98b10d0d96642.form = index24beda923a32e093a2b98b10d0d96642Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/hr/notifications'
 */
const index1759024efdbb49db404a80915be8b780 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1759024efdbb49db404a80915be8b780.url(options),
    method: 'get',
})

index1759024efdbb49db404a80915be8b780.definition = {
    methods: ["get","head"],
    url: '/hr/notifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/hr/notifications'
 */
index1759024efdbb49db404a80915be8b780.url = (options?: RouteQueryOptions) => {
    return index1759024efdbb49db404a80915be8b780.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/hr/notifications'
 */
index1759024efdbb49db404a80915be8b780.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1759024efdbb49db404a80915be8b780.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/hr/notifications'
 */
index1759024efdbb49db404a80915be8b780.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index1759024efdbb49db404a80915be8b780.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/hr/notifications'
 */
    const index1759024efdbb49db404a80915be8b780Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index1759024efdbb49db404a80915be8b780.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/hr/notifications'
 */
        index1759024efdbb49db404a80915be8b780Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index1759024efdbb49db404a80915be8b780.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/hr/notifications'
 */
        index1759024efdbb49db404a80915be8b780Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index1759024efdbb49db404a80915be8b780.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index1759024efdbb49db404a80915be8b780.form = index1759024efdbb49db404a80915be8b780Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
const indexdbe3765393a970e64fe6c6e3bc4208a4 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexdbe3765393a970e64fe6c6e3bc4208a4.url(options),
    method: 'get',
})

indexdbe3765393a970e64fe6c6e3bc4208a4.definition = {
    methods: ["get","head"],
    url: '/employee/notifications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
indexdbe3765393a970e64fe6c6e3bc4208a4.url = (options?: RouteQueryOptions) => {
    return indexdbe3765393a970e64fe6c6e3bc4208a4.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
indexdbe3765393a970e64fe6c6e3bc4208a4.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexdbe3765393a970e64fe6c6e3bc4208a4.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
indexdbe3765393a970e64fe6c6e3bc4208a4.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexdbe3765393a970e64fe6c6e3bc4208a4.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
    const indexdbe3765393a970e64fe6c6e3bc4208a4Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexdbe3765393a970e64fe6c6e3bc4208a4.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
        indexdbe3765393a970e64fe6c6e3bc4208a4Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexdbe3765393a970e64fe6c6e3bc4208a4.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:20
 * @route '/employee/notifications'
 */
        indexdbe3765393a970e64fe6c6e3bc4208a4Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexdbe3765393a970e64fe6c6e3bc4208a4.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexdbe3765393a970e64fe6c6e3bc4208a4.form = indexdbe3765393a970e64fe6c6e3bc4208a4Form

export const index = {
    '/admin/notifications': index24beda923a32e093a2b98b10d0d96642,
    '/hr/notifications': index1759024efdbb49db404a80915be8b780,
    '/employee/notifications': indexdbe3765393a970e64fe6c6e3bc4208a4,
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/admin/notifications/{noticeId}/mark-as-read'
 */
const markAsRead43e49aa9494e68011bbcabab425aebc9 = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead43e49aa9494e68011bbcabab425aebc9.url(args, options),
    method: 'post',
})

markAsRead43e49aa9494e68011bbcabab425aebc9.definition = {
    methods: ["post"],
    url: '/admin/notifications/{noticeId}/mark-as-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/admin/notifications/{noticeId}/mark-as-read'
 */
markAsRead43e49aa9494e68011bbcabab425aebc9.url = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return markAsRead43e49aa9494e68011bbcabab425aebc9.definition.url
            .replace('{noticeId}', parsedArgs.noticeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/admin/notifications/{noticeId}/mark-as-read'
 */
markAsRead43e49aa9494e68011bbcabab425aebc9.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead43e49aa9494e68011bbcabab425aebc9.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/admin/notifications/{noticeId}/mark-as-read'
 */
    const markAsRead43e49aa9494e68011bbcabab425aebc9Form = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAsRead43e49aa9494e68011bbcabab425aebc9.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/admin/notifications/{noticeId}/mark-as-read'
 */
        markAsRead43e49aa9494e68011bbcabab425aebc9Form.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAsRead43e49aa9494e68011bbcabab425aebc9.url(args, options),
            method: 'post',
        })
    
    markAsRead43e49aa9494e68011bbcabab425aebc9.form = markAsRead43e49aa9494e68011bbcabab425aebc9Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/hr/notifications/{noticeId}/mark-as-read'
 */
const markAsRead38dcf09403b6a570206389e2eabd7eb3 = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead38dcf09403b6a570206389e2eabd7eb3.url(args, options),
    method: 'post',
})

markAsRead38dcf09403b6a570206389e2eabd7eb3.definition = {
    methods: ["post"],
    url: '/hr/notifications/{noticeId}/mark-as-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/hr/notifications/{noticeId}/mark-as-read'
 */
markAsRead38dcf09403b6a570206389e2eabd7eb3.url = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return markAsRead38dcf09403b6a570206389e2eabd7eb3.definition.url
            .replace('{noticeId}', parsedArgs.noticeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/hr/notifications/{noticeId}/mark-as-read'
 */
markAsRead38dcf09403b6a570206389e2eabd7eb3.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead38dcf09403b6a570206389e2eabd7eb3.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/hr/notifications/{noticeId}/mark-as-read'
 */
    const markAsRead38dcf09403b6a570206389e2eabd7eb3Form = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAsRead38dcf09403b6a570206389e2eabd7eb3.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/hr/notifications/{noticeId}/mark-as-read'
 */
        markAsRead38dcf09403b6a570206389e2eabd7eb3Form.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAsRead38dcf09403b6a570206389e2eabd7eb3.url(args, options),
            method: 'post',
        })
    
    markAsRead38dcf09403b6a570206389e2eabd7eb3.form = markAsRead38dcf09403b6a570206389e2eabd7eb3Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
const markAsRead35860e8df51cab31a1a76083146aa4f2 = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead35860e8df51cab31a1a76083146aa4f2.url(args, options),
    method: 'post',
})

markAsRead35860e8df51cab31a1a76083146aa4f2.definition = {
    methods: ["post"],
    url: '/employee/notifications/{noticeId}/mark-as-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
markAsRead35860e8df51cab31a1a76083146aa4f2.url = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return markAsRead35860e8df51cab31a1a76083146aa4f2.definition.url
            .replace('{noticeId}', parsedArgs.noticeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
markAsRead35860e8df51cab31a1a76083146aa4f2.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAsRead35860e8df51cab31a1a76083146aa4f2.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
    const markAsRead35860e8df51cab31a1a76083146aa4f2Form = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAsRead35860e8df51cab31a1a76083146aa4f2.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAsRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:89
 * @route '/employee/notifications/{noticeId}/mark-as-read'
 */
        markAsRead35860e8df51cab31a1a76083146aa4f2Form.post = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAsRead35860e8df51cab31a1a76083146aa4f2.url(args, options),
            method: 'post',
        })
    
    markAsRead35860e8df51cab31a1a76083146aa4f2.form = markAsRead35860e8df51cab31a1a76083146aa4f2Form

export const markAsRead = {
    '/admin/notifications/{noticeId}/mark-as-read': markAsRead43e49aa9494e68011bbcabab425aebc9,
    '/hr/notifications/{noticeId}/mark-as-read': markAsRead38dcf09403b6a570206389e2eabd7eb3,
    '/employee/notifications/{noticeId}/mark-as-read': markAsRead35860e8df51cab31a1a76083146aa4f2,
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/admin/notifications/mark-all-read'
 */
const markAllRead7c28422dfa5df171ebf80366612a68fd = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllRead7c28422dfa5df171ebf80366612a68fd.url(options),
    method: 'post',
})

markAllRead7c28422dfa5df171ebf80366612a68fd.definition = {
    methods: ["post"],
    url: '/admin/notifications/mark-all-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/admin/notifications/mark-all-read'
 */
markAllRead7c28422dfa5df171ebf80366612a68fd.url = (options?: RouteQueryOptions) => {
    return markAllRead7c28422dfa5df171ebf80366612a68fd.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/admin/notifications/mark-all-read'
 */
markAllRead7c28422dfa5df171ebf80366612a68fd.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllRead7c28422dfa5df171ebf80366612a68fd.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/admin/notifications/mark-all-read'
 */
    const markAllRead7c28422dfa5df171ebf80366612a68fdForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAllRead7c28422dfa5df171ebf80366612a68fd.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/admin/notifications/mark-all-read'
 */
        markAllRead7c28422dfa5df171ebf80366612a68fdForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAllRead7c28422dfa5df171ebf80366612a68fd.url(options),
            method: 'post',
        })
    
    markAllRead7c28422dfa5df171ebf80366612a68fd.form = markAllRead7c28422dfa5df171ebf80366612a68fdForm
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/hr/notifications/mark-all-read'
 */
const markAllReadba76e3d94ca33565f36c08d66f689eca = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllReadba76e3d94ca33565f36c08d66f689eca.url(options),
    method: 'post',
})

markAllReadba76e3d94ca33565f36c08d66f689eca.definition = {
    methods: ["post"],
    url: '/hr/notifications/mark-all-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/hr/notifications/mark-all-read'
 */
markAllReadba76e3d94ca33565f36c08d66f689eca.url = (options?: RouteQueryOptions) => {
    return markAllReadba76e3d94ca33565f36c08d66f689eca.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/hr/notifications/mark-all-read'
 */
markAllReadba76e3d94ca33565f36c08d66f689eca.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllReadba76e3d94ca33565f36c08d66f689eca.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/hr/notifications/mark-all-read'
 */
    const markAllReadba76e3d94ca33565f36c08d66f689ecaForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAllReadba76e3d94ca33565f36c08d66f689eca.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/hr/notifications/mark-all-read'
 */
        markAllReadba76e3d94ca33565f36c08d66f689ecaForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAllReadba76e3d94ca33565f36c08d66f689eca.url(options),
            method: 'post',
        })
    
    markAllReadba76e3d94ca33565f36c08d66f689eca.form = markAllReadba76e3d94ca33565f36c08d66f689ecaForm
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
const markAllReade66c4d1342abceb8828bf9713ba8adde = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllReade66c4d1342abceb8828bf9713ba8adde.url(options),
    method: 'post',
})

markAllReade66c4d1342abceb8828bf9713ba8adde.definition = {
    methods: ["post"],
    url: '/employee/notifications/mark-all-read',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
markAllReade66c4d1342abceb8828bf9713ba8adde.url = (options?: RouteQueryOptions) => {
    return markAllReade66c4d1342abceb8828bf9713ba8adde.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
markAllReade66c4d1342abceb8828bf9713ba8adde.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: markAllReade66c4d1342abceb8828bf9713ba8adde.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
    const markAllReade66c4d1342abceb8828bf9713ba8addeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: markAllReade66c4d1342abceb8828bf9713ba8adde.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::markAllRead
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:105
 * @route '/employee/notifications/mark-all-read'
 */
        markAllReade66c4d1342abceb8828bf9713ba8addeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: markAllReade66c4d1342abceb8828bf9713ba8adde.url(options),
            method: 'post',
        })
    
    markAllReade66c4d1342abceb8828bf9713ba8adde.form = markAllReade66c4d1342abceb8828bf9713ba8addeForm

export const markAllRead = {
    '/admin/notifications/mark-all-read': markAllRead7c28422dfa5df171ebf80366612a68fd,
    '/hr/notifications/mark-all-read': markAllReadba76e3d94ca33565f36c08d66f689eca,
    '/employee/notifications/mark-all-read': markAllReade66c4d1342abceb8828bf9713ba8adde,
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/admin/notifications/unread-count'
 */
const unreadCount6b06129fbdd40f702df0b092ec1c6296 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unreadCount6b06129fbdd40f702df0b092ec1c6296.url(options),
    method: 'get',
})

unreadCount6b06129fbdd40f702df0b092ec1c6296.definition = {
    methods: ["get","head"],
    url: '/admin/notifications/unread-count',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/admin/notifications/unread-count'
 */
unreadCount6b06129fbdd40f702df0b092ec1c6296.url = (options?: RouteQueryOptions) => {
    return unreadCount6b06129fbdd40f702df0b092ec1c6296.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/admin/notifications/unread-count'
 */
unreadCount6b06129fbdd40f702df0b092ec1c6296.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unreadCount6b06129fbdd40f702df0b092ec1c6296.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/admin/notifications/unread-count'
 */
unreadCount6b06129fbdd40f702df0b092ec1c6296.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: unreadCount6b06129fbdd40f702df0b092ec1c6296.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/admin/notifications/unread-count'
 */
    const unreadCount6b06129fbdd40f702df0b092ec1c6296Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: unreadCount6b06129fbdd40f702df0b092ec1c6296.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/admin/notifications/unread-count'
 */
        unreadCount6b06129fbdd40f702df0b092ec1c6296Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: unreadCount6b06129fbdd40f702df0b092ec1c6296.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/admin/notifications/unread-count'
 */
        unreadCount6b06129fbdd40f702df0b092ec1c6296Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: unreadCount6b06129fbdd40f702df0b092ec1c6296.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    unreadCount6b06129fbdd40f702df0b092ec1c6296.form = unreadCount6b06129fbdd40f702df0b092ec1c6296Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/hr/notifications/unread-count'
 */
const unreadCount93cefd025a680c38345e02544582e971 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unreadCount93cefd025a680c38345e02544582e971.url(options),
    method: 'get',
})

unreadCount93cefd025a680c38345e02544582e971.definition = {
    methods: ["get","head"],
    url: '/hr/notifications/unread-count',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/hr/notifications/unread-count'
 */
unreadCount93cefd025a680c38345e02544582e971.url = (options?: RouteQueryOptions) => {
    return unreadCount93cefd025a680c38345e02544582e971.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/hr/notifications/unread-count'
 */
unreadCount93cefd025a680c38345e02544582e971.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unreadCount93cefd025a680c38345e02544582e971.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/hr/notifications/unread-count'
 */
unreadCount93cefd025a680c38345e02544582e971.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: unreadCount93cefd025a680c38345e02544582e971.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/hr/notifications/unread-count'
 */
    const unreadCount93cefd025a680c38345e02544582e971Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: unreadCount93cefd025a680c38345e02544582e971.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/hr/notifications/unread-count'
 */
        unreadCount93cefd025a680c38345e02544582e971Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: unreadCount93cefd025a680c38345e02544582e971.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/hr/notifications/unread-count'
 */
        unreadCount93cefd025a680c38345e02544582e971Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: unreadCount93cefd025a680c38345e02544582e971.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    unreadCount93cefd025a680c38345e02544582e971.form = unreadCount93cefd025a680c38345e02544582e971Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
const unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.url(options),
    method: 'get',
})

unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.definition = {
    methods: ["get","head"],
    url: '/employee/notifications/unread-count',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.url = (options?: RouteQueryOptions) => {
    return unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
    const unreadCount97cc20a07fbedf9cc1eb3db3d1e4117dForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
        unreadCount97cc20a07fbedf9cc1eb3db3d1e4117dForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::unreadCount
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:152
 * @route '/employee/notifications/unread-count'
 */
        unreadCount97cc20a07fbedf9cc1eb3db3d1e4117dForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d.form = unreadCount97cc20a07fbedf9cc1eb3db3d1e4117dForm

export const unreadCount = {
    '/admin/notifications/unread-count': unreadCount6b06129fbdd40f702df0b092ec1c6296,
    '/hr/notifications/unread-count': unreadCount93cefd025a680c38345e02544582e971,
    '/employee/notifications/unread-count': unreadCount97cc20a07fbedf9cc1eb3db3d1e4117d,
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/admin/notifications/{noticeId}'
 */
const destroyee8aa1466383c0b2912fe9aab79d6712 = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyee8aa1466383c0b2912fe9aab79d6712.url(args, options),
    method: 'delete',
})

destroyee8aa1466383c0b2912fe9aab79d6712.definition = {
    methods: ["delete"],
    url: '/admin/notifications/{noticeId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/admin/notifications/{noticeId}'
 */
destroyee8aa1466383c0b2912fe9aab79d6712.url = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroyee8aa1466383c0b2912fe9aab79d6712.definition.url
            .replace('{noticeId}', parsedArgs.noticeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/admin/notifications/{noticeId}'
 */
destroyee8aa1466383c0b2912fe9aab79d6712.delete = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyee8aa1466383c0b2912fe9aab79d6712.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/admin/notifications/{noticeId}'
 */
    const destroyee8aa1466383c0b2912fe9aab79d6712Form = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroyee8aa1466383c0b2912fe9aab79d6712.url(args, {
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
 * @route '/admin/notifications/{noticeId}'
 */
        destroyee8aa1466383c0b2912fe9aab79d6712Form.delete = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroyee8aa1466383c0b2912fe9aab79d6712.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroyee8aa1466383c0b2912fe9aab79d6712.form = destroyee8aa1466383c0b2912fe9aab79d6712Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/hr/notifications/{noticeId}'
 */
const destroy5da8ae182a061fb54e1e4196bd1e4b15 = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy5da8ae182a061fb54e1e4196bd1e4b15.url(args, options),
    method: 'delete',
})

destroy5da8ae182a061fb54e1e4196bd1e4b15.definition = {
    methods: ["delete"],
    url: '/hr/notifications/{noticeId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/hr/notifications/{noticeId}'
 */
destroy5da8ae182a061fb54e1e4196bd1e4b15.url = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy5da8ae182a061fb54e1e4196bd1e4b15.definition.url
            .replace('{noticeId}', parsedArgs.noticeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/hr/notifications/{noticeId}'
 */
destroy5da8ae182a061fb54e1e4196bd1e4b15.delete = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy5da8ae182a061fb54e1e4196bd1e4b15.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/hr/notifications/{noticeId}'
 */
    const destroy5da8ae182a061fb54e1e4196bd1e4b15Form = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy5da8ae182a061fb54e1e4196bd1e4b15.url(args, {
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
 * @route '/hr/notifications/{noticeId}'
 */
        destroy5da8ae182a061fb54e1e4196bd1e4b15Form.delete = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy5da8ae182a061fb54e1e4196bd1e4b15.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy5da8ae182a061fb54e1e4196bd1e4b15.form = destroy5da8ae182a061fb54e1e4196bd1e4b15Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/employee/notifications/{noticeId}'
 */
const destroy42109d1298c9f62cae6f6e2fa787ede0 = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy42109d1298c9f62cae6f6e2fa787ede0.url(args, options),
    method: 'delete',
})

destroy42109d1298c9f62cae6f6e2fa787ede0.definition = {
    methods: ["delete"],
    url: '/employee/notifications/{noticeId}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/employee/notifications/{noticeId}'
 */
destroy42109d1298c9f62cae6f6e2fa787ede0.url = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy42109d1298c9f62cae6f6e2fa787ede0.definition.url
            .replace('{noticeId}', parsedArgs.noticeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/employee/notifications/{noticeId}'
 */
destroy42109d1298c9f62cae6f6e2fa787ede0.delete = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy42109d1298c9f62cae6f6e2fa787ede0.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroy
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:119
 * @route '/employee/notifications/{noticeId}'
 */
    const destroy42109d1298c9f62cae6f6e2fa787ede0Form = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy42109d1298c9f62cae6f6e2fa787ede0.url(args, {
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
        destroy42109d1298c9f62cae6f6e2fa787ede0Form.delete = (args: { noticeId: string | number } | [noticeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy42109d1298c9f62cae6f6e2fa787ede0.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy42109d1298c9f62cae6f6e2fa787ede0.form = destroy42109d1298c9f62cae6f6e2fa787ede0Form

export const destroy = {
    '/admin/notifications/{noticeId}': destroyee8aa1466383c0b2912fe9aab79d6712,
    '/hr/notifications/{noticeId}': destroy5da8ae182a061fb54e1e4196bd1e4b15,
    '/employee/notifications/{noticeId}': destroy42109d1298c9f62cae6f6e2fa787ede0,
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/admin/notifications'
 */
const destroyAll24beda923a32e093a2b98b10d0d96642 = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAll24beda923a32e093a2b98b10d0d96642.url(options),
    method: 'delete',
})

destroyAll24beda923a32e093a2b98b10d0d96642.definition = {
    methods: ["delete"],
    url: '/admin/notifications',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/admin/notifications'
 */
destroyAll24beda923a32e093a2b98b10d0d96642.url = (options?: RouteQueryOptions) => {
    return destroyAll24beda923a32e093a2b98b10d0d96642.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/admin/notifications'
 */
destroyAll24beda923a32e093a2b98b10d0d96642.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAll24beda923a32e093a2b98b10d0d96642.url(options),
    method: 'delete',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/admin/notifications'
 */
    const destroyAll24beda923a32e093a2b98b10d0d96642Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroyAll24beda923a32e093a2b98b10d0d96642.url({
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
 * @route '/admin/notifications'
 */
        destroyAll24beda923a32e093a2b98b10d0d96642Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroyAll24beda923a32e093a2b98b10d0d96642.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroyAll24beda923a32e093a2b98b10d0d96642.form = destroyAll24beda923a32e093a2b98b10d0d96642Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/hr/notifications'
 */
const destroyAll1759024efdbb49db404a80915be8b780 = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAll1759024efdbb49db404a80915be8b780.url(options),
    method: 'delete',
})

destroyAll1759024efdbb49db404a80915be8b780.definition = {
    methods: ["delete"],
    url: '/hr/notifications',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/hr/notifications'
 */
destroyAll1759024efdbb49db404a80915be8b780.url = (options?: RouteQueryOptions) => {
    return destroyAll1759024efdbb49db404a80915be8b780.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/hr/notifications'
 */
destroyAll1759024efdbb49db404a80915be8b780.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAll1759024efdbb49db404a80915be8b780.url(options),
    method: 'delete',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/hr/notifications'
 */
    const destroyAll1759024efdbb49db404a80915be8b780Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroyAll1759024efdbb49db404a80915be8b780.url({
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
 * @route '/hr/notifications'
 */
        destroyAll1759024efdbb49db404a80915be8b780Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroyAll1759024efdbb49db404a80915be8b780.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroyAll1759024efdbb49db404a80915be8b780.form = destroyAll1759024efdbb49db404a80915be8b780Form
    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/employee/notifications'
 */
const destroyAlldbe3765393a970e64fe6c6e3bc4208a4 = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAlldbe3765393a970e64fe6c6e3bc4208a4.url(options),
    method: 'delete',
})

destroyAlldbe3765393a970e64fe6c6e3bc4208a4.definition = {
    methods: ["delete"],
    url: '/employee/notifications',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/employee/notifications'
 */
destroyAlldbe3765393a970e64fe6c6e3bc4208a4.url = (options?: RouteQueryOptions) => {
    return destroyAlldbe3765393a970e64fe6c6e3bc4208a4.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/employee/notifications'
 */
destroyAlldbe3765393a970e64fe6c6e3bc4208a4.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyAlldbe3765393a970e64fe6c6e3bc4208a4.url(options),
    method: 'delete',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::destroyAll
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:135
 * @route '/employee/notifications'
 */
    const destroyAlldbe3765393a970e64fe6c6e3bc4208a4Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroyAlldbe3765393a970e64fe6c6e3bc4208a4.url({
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
        destroyAlldbe3765393a970e64fe6c6e3bc4208a4Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroyAlldbe3765393a970e64fe6c6e3bc4208a4.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroyAlldbe3765393a970e64fe6c6e3bc4208a4.form = destroyAlldbe3765393a970e64fe6c6e3bc4208a4Form

export const destroyAll = {
    '/admin/notifications': destroyAll24beda923a32e093a2b98b10d0d96642,
    '/hr/notifications': destroyAll1759024efdbb49db404a80915be8b780,
    '/employee/notifications': destroyAlldbe3765393a970e64fe6c6e3bc4208a4,
}

const NotificationController = { index, markAsRead, markAllRead, unreadCount, destroy, destroyAll }

export default NotificationController