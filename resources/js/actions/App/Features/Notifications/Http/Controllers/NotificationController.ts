import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
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
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
index24beda923a32e093a2b98b10d0d96642.url = (options?: RouteQueryOptions) => {
    return index24beda923a32e093a2b98b10d0d96642.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
index24beda923a32e093a2b98b10d0d96642.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index24beda923a32e093a2b98b10d0d96642.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
index24beda923a32e093a2b98b10d0d96642.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index24beda923a32e093a2b98b10d0d96642.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
    const index24beda923a32e093a2b98b10d0d96642Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index24beda923a32e093a2b98b10d0d96642.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/admin/notifications'
 */
        index24beda923a32e093a2b98b10d0d96642Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index24beda923a32e093a2b98b10d0d96642.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
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
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
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
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/hr/notifications'
 */
index1759024efdbb49db404a80915be8b780.url = (options?: RouteQueryOptions) => {
    return index1759024efdbb49db404a80915be8b780.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/hr/notifications'
 */
index1759024efdbb49db404a80915be8b780.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index1759024efdbb49db404a80915be8b780.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/hr/notifications'
 */
index1759024efdbb49db404a80915be8b780.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index1759024efdbb49db404a80915be8b780.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/hr/notifications'
 */
    const index1759024efdbb49db404a80915be8b780Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index1759024efdbb49db404a80915be8b780.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/hr/notifications'
 */
        index1759024efdbb49db404a80915be8b780Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index1759024efdbb49db404a80915be8b780.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
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
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
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
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/employee/notifications'
 */
indexdbe3765393a970e64fe6c6e3bc4208a4.url = (options?: RouteQueryOptions) => {
    return indexdbe3765393a970e64fe6c6e3bc4208a4.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/employee/notifications'
 */
indexdbe3765393a970e64fe6c6e3bc4208a4.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexdbe3765393a970e64fe6c6e3bc4208a4.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/employee/notifications'
 */
indexdbe3765393a970e64fe6c6e3bc4208a4.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexdbe3765393a970e64fe6c6e3bc4208a4.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/employee/notifications'
 */
    const indexdbe3765393a970e64fe6c6e3bc4208a4Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexdbe3765393a970e64fe6c6e3bc4208a4.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
 * @route '/employee/notifications'
 */
        indexdbe3765393a970e64fe6c6e3bc4208a4Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexdbe3765393a970e64fe6c6e3bc4208a4.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notifications\Http\Controllers\NotificationController::index
 * @see app/Features/Notifications/Http/Controllers/NotificationController.php:11
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

const NotificationController = { index }

export default NotificationController