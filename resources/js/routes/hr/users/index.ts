import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
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
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/hr/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/hr/users/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/hr/users/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/hr/users/{id}'
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
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/hr/users/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/hr/users/{id}'
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
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/hr/users/{id}'
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
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/hr/users/{id}'
 */
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/hr/users/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/hr/users/{id}'
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
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/hr/users/{id}'
 */
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/hr/users/{id}'
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
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/hr/users/{id}'
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
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/hr/users/{id}/toggle-status'
 */
export const toggleStatus = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggleStatus.url(args, options),
    method: 'patch',
})

toggleStatus.definition = {
    methods: ["patch"],
    url: '/hr/users/{id}/toggle-status',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/hr/users/{id}/toggle-status'
 */
toggleStatus.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return toggleStatus.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/hr/users/{id}/toggle-status'
 */
toggleStatus.patch = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggleStatus.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/hr/users/{id}/toggle-status'
 */
    const toggleStatusForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: toggleStatus.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PATCH',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/hr/users/{id}/toggle-status'
 */
        toggleStatusForm.patch = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: toggleStatus.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    toggleStatus.form = toggleStatusForm
/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulk_action
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
export const bulk_action = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulk_action.url(options),
    method: 'post',
})

bulk_action.definition = {
    methods: ["post"],
    url: '/hr/users/bulk-action',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulk_action
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
bulk_action.url = (options?: RouteQueryOptions) => {
    return bulk_action.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulk_action
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
bulk_action.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulk_action.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulk_action
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
    const bulk_actionForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulk_action.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulk_action
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
        bulk_actionForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulk_action.url(options),
            method: 'post',
        })
    
    bulk_action.form = bulk_actionForm
const users = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
toggleStatus: Object.assign(toggleStatus, toggleStatus),
bulk_action: Object.assign(bulk_action, bulk_action),
}

export default users