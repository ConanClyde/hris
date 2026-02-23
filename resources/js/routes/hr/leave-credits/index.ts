import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:12
 * @route '/hr/leave-credits'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/leave-credits',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:12
 * @route '/hr/leave-credits'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:12
 * @route '/hr/leave-credits'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:12
 * @route '/hr/leave-credits'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:12
 * @route '/hr/leave-credits'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:12
 * @route '/hr/leave-credits'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:12
 * @route '/hr/leave-credits'
 */
        indexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index.form = indexForm
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::show
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:30
 * @route '/hr/leave-credits/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/hr/leave-credits/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::show
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:30
 * @route '/hr/leave-credits/{id}'
 */
show.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return show.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::show
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:30
 * @route '/hr/leave-credits/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::show
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:30
 * @route '/hr/leave-credits/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::show
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:30
 * @route '/hr/leave-credits/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::show
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:30
 * @route '/hr/leave-credits/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveCreditController::show
 * @see app/Features/Leave/Http/Controllers/HR/LeaveCreditController.php:30
 * @route '/hr/leave-credits/{id}'
 */
        showForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
const leaveCredits = {
    index: Object.assign(index, index),
show: Object.assign(show, show),
}

export default leaveCredits