import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::index
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:15
 * @route '/api/v1/leave-applications'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/v1/leave-applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::index
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:15
 * @route '/api/v1/leave-applications'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::index
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:15
 * @route '/api/v1/leave-applications'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::index
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:15
 * @route '/api/v1/leave-applications'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::index
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:15
 * @route '/api/v1/leave-applications'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::index
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:15
 * @route '/api/v1/leave-applications'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::index
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:15
 * @route '/api/v1/leave-applications'
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
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::store
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:50
 * @route '/api/v1/leave-applications'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/v1/leave-applications',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::store
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:50
 * @route '/api/v1/leave-applications'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::store
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:50
 * @route '/api/v1/leave-applications'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::store
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:50
 * @route '/api/v1/leave-applications'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::store
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:50
 * @route '/api/v1/leave-applications'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::show
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:43
 * @route '/api/v1/leave-applications/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/v1/leave-applications/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::show
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:43
 * @route '/api/v1/leave-applications/{id}'
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
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::show
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:43
 * @route '/api/v1/leave-applications/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::show
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:43
 * @route '/api/v1/leave-applications/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::show
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:43
 * @route '/api/v1/leave-applications/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::show
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:43
 * @route '/api/v1/leave-applications/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::show
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:43
 * @route '/api/v1/leave-applications/{id}'
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
/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::updateStatus
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:70
 * @route '/api/v1/leave-applications/{id}/status'
 */
export const updateStatus = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updateStatus.url(args, options),
    method: 'put',
})

updateStatus.definition = {
    methods: ["put"],
    url: '/api/v1/leave-applications/{id}/status',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::updateStatus
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:70
 * @route '/api/v1/leave-applications/{id}/status'
 */
updateStatus.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return updateStatus.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::updateStatus
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:70
 * @route '/api/v1/leave-applications/{id}/status'
 */
updateStatus.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updateStatus.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::updateStatus
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:70
 * @route '/api/v1/leave-applications/{id}/status'
 */
    const updateStatusForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateStatus.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\Api\LeaveApiController::updateStatus
 * @see app/Features/Leave/Http/Controllers/Api/LeaveApiController.php:70
 * @route '/api/v1/leave-applications/{id}/status'
 */
        updateStatusForm.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateStatus.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updateStatus.form = updateStatusForm
const LeaveApiController = { index, store, show, updateStatus }

export default LeaveApiController