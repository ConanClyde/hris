import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::index
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:26
 * @route '/employee/leave-applications'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/employee/leave-applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::index
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:26
 * @route '/employee/leave-applications'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::index
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:26
 * @route '/employee/leave-applications'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::index
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:26
 * @route '/employee/leave-applications'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::index
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:26
 * @route '/employee/leave-applications'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::index
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:26
 * @route '/employee/leave-applications'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::index
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:26
 * @route '/employee/leave-applications'
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
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::store
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:67
 * @route '/employee/leave-applications'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/employee/leave-applications',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::store
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:67
 * @route '/employee/leave-applications'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::store
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:67
 * @route '/employee/leave-applications'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::store
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:67
 * @route '/employee/leave-applications'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::store
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:67
 * @route '/employee/leave-applications'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::update
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:108
 * @route '/employee/leave-applications/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/employee/leave-applications/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::update
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:108
 * @route '/employee/leave-applications/{id}'
 */
update.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::update
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:108
 * @route '/employee/leave-applications/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::update
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:108
 * @route '/employee/leave-applications/{id}'
 */
    const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::update
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:108
 * @route '/employee/leave-applications/{id}'
 */
        updateForm.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::destroy
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:152
 * @route '/employee/leave-applications/{id}'
 */
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/employee/leave-applications/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::destroy
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:152
 * @route '/employee/leave-applications/{id}'
 */
destroy.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::destroy
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:152
 * @route '/employee/leave-applications/{id}'
 */
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::destroy
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:152
 * @route '/employee/leave-applications/{id}'
 */
    const destroyForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::destroy
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:152
 * @route '/employee/leave-applications/{id}'
 */
        destroyForm.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::exportMethod
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:166
 * @route '/employee/leave-applications/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/employee/leave-applications/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::exportMethod
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:166
 * @route '/employee/leave-applications/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::exportMethod
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:166
 * @route '/employee/leave-applications/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::exportMethod
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:166
 * @route '/employee/leave-applications/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::exportMethod
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:166
 * @route '/employee/leave-applications/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::exportMethod
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:166
 * @route '/employee/leave-applications/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Leave\Http\Controllers\Employee\LeaveController::exportMethod
 * @see app/Features/Leave/Http/Controllers/Employee/LeaveController.php:166
 * @route '/employee/leave-applications/export'
 */
        exportMethodForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportMethod.form = exportMethodForm
const leaveApplications = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
export: Object.assign(exportMethod, exportMethod),
}

export default leaveApplications