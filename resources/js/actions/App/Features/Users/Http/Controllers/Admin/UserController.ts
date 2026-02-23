import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
const indexde7b92f5d57ab3be25571f27f05793f8 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexde7b92f5d57ab3be25571f27f05793f8.url(options),
    method: 'get',
})

indexde7b92f5d57ab3be25571f27f05793f8.definition = {
    methods: ["get","head"],
    url: '/admin/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
indexde7b92f5d57ab3be25571f27f05793f8.url = (options?: RouteQueryOptions) => {
    return indexde7b92f5d57ab3be25571f27f05793f8.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
indexde7b92f5d57ab3be25571f27f05793f8.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexde7b92f5d57ab3be25571f27f05793f8.url(options),
    method: 'get',
})
/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
indexde7b92f5d57ab3be25571f27f05793f8.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexde7b92f5d57ab3be25571f27f05793f8.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
    const indexde7b92f5d57ab3be25571f27f05793f8Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexde7b92f5d57ab3be25571f27f05793f8.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
        indexde7b92f5d57ab3be25571f27f05793f8Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexde7b92f5d57ab3be25571f27f05793f8.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/admin/users'
 */
        indexde7b92f5d57ab3be25571f27f05793f8Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexde7b92f5d57ab3be25571f27f05793f8.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexde7b92f5d57ab3be25571f27f05793f8.form = indexde7b92f5d57ab3be25571f27f05793f8Form
    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
const index5d4b82f6576226cd2673896e07073a7a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index5d4b82f6576226cd2673896e07073a7a.url(options),
    method: 'get',
})

index5d4b82f6576226cd2673896e07073a7a.definition = {
    methods: ["get","head"],
    url: '/hr/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
index5d4b82f6576226cd2673896e07073a7a.url = (options?: RouteQueryOptions) => {
    return index5d4b82f6576226cd2673896e07073a7a.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
index5d4b82f6576226cd2673896e07073a7a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index5d4b82f6576226cd2673896e07073a7a.url(options),
    method: 'get',
})
/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
index5d4b82f6576226cd2673896e07073a7a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index5d4b82f6576226cd2673896e07073a7a.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
    const index5d4b82f6576226cd2673896e07073a7aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index5d4b82f6576226cd2673896e07073a7a.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
        index5d4b82f6576226cd2673896e07073a7aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index5d4b82f6576226cd2673896e07073a7a.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::index
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:14
 * @route '/hr/users'
 */
        index5d4b82f6576226cd2673896e07073a7aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index5d4b82f6576226cd2673896e07073a7a.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index5d4b82f6576226cd2673896e07073a7a.form = index5d4b82f6576226cd2673896e07073a7aForm

export const index = {
    '/admin/users': indexde7b92f5d57ab3be25571f27f05793f8,
    '/hr/users': index5d4b82f6576226cd2673896e07073a7a,
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/admin/users'
 */
const storede7b92f5d57ab3be25571f27f05793f8 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storede7b92f5d57ab3be25571f27f05793f8.url(options),
    method: 'post',
})

storede7b92f5d57ab3be25571f27f05793f8.definition = {
    methods: ["post"],
    url: '/admin/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/admin/users'
 */
storede7b92f5d57ab3be25571f27f05793f8.url = (options?: RouteQueryOptions) => {
    return storede7b92f5d57ab3be25571f27f05793f8.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/admin/users'
 */
storede7b92f5d57ab3be25571f27f05793f8.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storede7b92f5d57ab3be25571f27f05793f8.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/admin/users'
 */
    const storede7b92f5d57ab3be25571f27f05793f8Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: storede7b92f5d57ab3be25571f27f05793f8.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/admin/users'
 */
        storede7b92f5d57ab3be25571f27f05793f8Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: storede7b92f5d57ab3be25571f27f05793f8.url(options),
            method: 'post',
        })
    
    storede7b92f5d57ab3be25571f27f05793f8.form = storede7b92f5d57ab3be25571f27f05793f8Form
    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
const store5d4b82f6576226cd2673896e07073a7a = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store5d4b82f6576226cd2673896e07073a7a.url(options),
    method: 'post',
})

store5d4b82f6576226cd2673896e07073a7a.definition = {
    methods: ["post"],
    url: '/hr/users',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
store5d4b82f6576226cd2673896e07073a7a.url = (options?: RouteQueryOptions) => {
    return store5d4b82f6576226cd2673896e07073a7a.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
store5d4b82f6576226cd2673896e07073a7a.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store5d4b82f6576226cd2673896e07073a7a.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
    const store5d4b82f6576226cd2673896e07073a7aForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store5d4b82f6576226cd2673896e07073a7a.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::store
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:56
 * @route '/hr/users'
 */
        store5d4b82f6576226cd2673896e07073a7aForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store5d4b82f6576226cd2673896e07073a7a.url(options),
            method: 'post',
        })
    
    store5d4b82f6576226cd2673896e07073a7a.form = store5d4b82f6576226cd2673896e07073a7aForm

export const store = {
    '/admin/users': storede7b92f5d57ab3be25571f27f05793f8,
    '/hr/users': store5d4b82f6576226cd2673896e07073a7a,
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/admin/users/{id}'
 */
const update10d1efffed943b02995f330502adb2de = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update10d1efffed943b02995f330502adb2de.url(args, options),
    method: 'put',
})

update10d1efffed943b02995f330502adb2de.definition = {
    methods: ["put"],
    url: '/admin/users/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/admin/users/{id}'
 */
update10d1efffed943b02995f330502adb2de.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return update10d1efffed943b02995f330502adb2de.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/admin/users/{id}'
 */
update10d1efffed943b02995f330502adb2de.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update10d1efffed943b02995f330502adb2de.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/admin/users/{id}'
 */
    const update10d1efffed943b02995f330502adb2deForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update10d1efffed943b02995f330502adb2de.url(args, {
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
 * @route '/admin/users/{id}'
 */
        update10d1efffed943b02995f330502adb2deForm.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update10d1efffed943b02995f330502adb2de.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update10d1efffed943b02995f330502adb2de.form = update10d1efffed943b02995f330502adb2deForm
    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/hr/users/{id}'
 */
const update6e1cc9cecdcb23d5aee5a287f88b7628 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update6e1cc9cecdcb23d5aee5a287f88b7628.url(args, options),
    method: 'put',
})

update6e1cc9cecdcb23d5aee5a287f88b7628.definition = {
    methods: ["put"],
    url: '/hr/users/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/hr/users/{id}'
 */
update6e1cc9cecdcb23d5aee5a287f88b7628.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return update6e1cc9cecdcb23d5aee5a287f88b7628.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/hr/users/{id}'
 */
update6e1cc9cecdcb23d5aee5a287f88b7628.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update6e1cc9cecdcb23d5aee5a287f88b7628.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::update
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:84
 * @route '/hr/users/{id}'
 */
    const update6e1cc9cecdcb23d5aee5a287f88b7628Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update6e1cc9cecdcb23d5aee5a287f88b7628.url(args, {
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
        update6e1cc9cecdcb23d5aee5a287f88b7628Form.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update6e1cc9cecdcb23d5aee5a287f88b7628.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update6e1cc9cecdcb23d5aee5a287f88b7628.form = update6e1cc9cecdcb23d5aee5a287f88b7628Form

export const update = {
    '/admin/users/{id}': update10d1efffed943b02995f330502adb2de,
    '/hr/users/{id}': update6e1cc9cecdcb23d5aee5a287f88b7628,
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/admin/users/{id}'
 */
const destroy10d1efffed943b02995f330502adb2de = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy10d1efffed943b02995f330502adb2de.url(args, options),
    method: 'delete',
})

destroy10d1efffed943b02995f330502adb2de.definition = {
    methods: ["delete"],
    url: '/admin/users/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/admin/users/{id}'
 */
destroy10d1efffed943b02995f330502adb2de.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy10d1efffed943b02995f330502adb2de.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/admin/users/{id}'
 */
destroy10d1efffed943b02995f330502adb2de.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy10d1efffed943b02995f330502adb2de.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/admin/users/{id}'
 */
    const destroy10d1efffed943b02995f330502adb2deForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy10d1efffed943b02995f330502adb2de.url(args, {
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
 * @route '/admin/users/{id}'
 */
        destroy10d1efffed943b02995f330502adb2deForm.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy10d1efffed943b02995f330502adb2de.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy10d1efffed943b02995f330502adb2de.form = destroy10d1efffed943b02995f330502adb2deForm
    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/hr/users/{id}'
 */
const destroy6e1cc9cecdcb23d5aee5a287f88b7628 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy6e1cc9cecdcb23d5aee5a287f88b7628.url(args, options),
    method: 'delete',
})

destroy6e1cc9cecdcb23d5aee5a287f88b7628.definition = {
    methods: ["delete"],
    url: '/hr/users/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/hr/users/{id}'
 */
destroy6e1cc9cecdcb23d5aee5a287f88b7628.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy6e1cc9cecdcb23d5aee5a287f88b7628.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/hr/users/{id}'
 */
destroy6e1cc9cecdcb23d5aee5a287f88b7628.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy6e1cc9cecdcb23d5aee5a287f88b7628.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::destroy
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:111
 * @route '/hr/users/{id}'
 */
    const destroy6e1cc9cecdcb23d5aee5a287f88b7628Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy6e1cc9cecdcb23d5aee5a287f88b7628.url(args, {
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
        destroy6e1cc9cecdcb23d5aee5a287f88b7628Form.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy6e1cc9cecdcb23d5aee5a287f88b7628.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy6e1cc9cecdcb23d5aee5a287f88b7628.form = destroy6e1cc9cecdcb23d5aee5a287f88b7628Form

export const destroy = {
    '/admin/users/{id}': destroy10d1efffed943b02995f330502adb2de,
    '/hr/users/{id}': destroy6e1cc9cecdcb23d5aee5a287f88b7628,
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/admin/users/{id}/toggle-status'
 */
const toggleStatus7b5caff9587ee14c41204ce4fba7e028 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggleStatus7b5caff9587ee14c41204ce4fba7e028.url(args, options),
    method: 'patch',
})

toggleStatus7b5caff9587ee14c41204ce4fba7e028.definition = {
    methods: ["patch"],
    url: '/admin/users/{id}/toggle-status',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/admin/users/{id}/toggle-status'
 */
toggleStatus7b5caff9587ee14c41204ce4fba7e028.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return toggleStatus7b5caff9587ee14c41204ce4fba7e028.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/admin/users/{id}/toggle-status'
 */
toggleStatus7b5caff9587ee14c41204ce4fba7e028.patch = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggleStatus7b5caff9587ee14c41204ce4fba7e028.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/admin/users/{id}/toggle-status'
 */
    const toggleStatus7b5caff9587ee14c41204ce4fba7e028Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: toggleStatus7b5caff9587ee14c41204ce4fba7e028.url(args, {
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
 * @route '/admin/users/{id}/toggle-status'
 */
        toggleStatus7b5caff9587ee14c41204ce4fba7e028Form.patch = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: toggleStatus7b5caff9587ee14c41204ce4fba7e028.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    toggleStatus7b5caff9587ee14c41204ce4fba7e028.form = toggleStatus7b5caff9587ee14c41204ce4fba7e028Form
    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/hr/users/{id}/toggle-status'
 */
const toggleStatusf1484736d17262b7ca802e5ae8abe435 = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggleStatusf1484736d17262b7ca802e5ae8abe435.url(args, options),
    method: 'patch',
})

toggleStatusf1484736d17262b7ca802e5ae8abe435.definition = {
    methods: ["patch"],
    url: '/hr/users/{id}/toggle-status',
} satisfies RouteDefinition<["patch"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/hr/users/{id}/toggle-status'
 */
toggleStatusf1484736d17262b7ca802e5ae8abe435.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return toggleStatusf1484736d17262b7ca802e5ae8abe435.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/hr/users/{id}/toggle-status'
 */
toggleStatusf1484736d17262b7ca802e5ae8abe435.patch = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: toggleStatusf1484736d17262b7ca802e5ae8abe435.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::toggleStatus
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:119
 * @route '/hr/users/{id}/toggle-status'
 */
    const toggleStatusf1484736d17262b7ca802e5ae8abe435Form = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: toggleStatusf1484736d17262b7ca802e5ae8abe435.url(args, {
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
        toggleStatusf1484736d17262b7ca802e5ae8abe435Form.patch = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: toggleStatusf1484736d17262b7ca802e5ae8abe435.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    toggleStatusf1484736d17262b7ca802e5ae8abe435.form = toggleStatusf1484736d17262b7ca802e5ae8abe435Form

export const toggleStatus = {
    '/admin/users/{id}/toggle-status': toggleStatus7b5caff9587ee14c41204ce4fba7e028,
    '/hr/users/{id}/toggle-status': toggleStatusf1484736d17262b7ca802e5ae8abe435,
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/admin/users/bulk-action'
 */
const bulkAction3c13f11221ea4c376260ad015f496f07 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAction3c13f11221ea4c376260ad015f496f07.url(options),
    method: 'post',
})

bulkAction3c13f11221ea4c376260ad015f496f07.definition = {
    methods: ["post"],
    url: '/admin/users/bulk-action',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/admin/users/bulk-action'
 */
bulkAction3c13f11221ea4c376260ad015f496f07.url = (options?: RouteQueryOptions) => {
    return bulkAction3c13f11221ea4c376260ad015f496f07.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/admin/users/bulk-action'
 */
bulkAction3c13f11221ea4c376260ad015f496f07.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAction3c13f11221ea4c376260ad015f496f07.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/admin/users/bulk-action'
 */
    const bulkAction3c13f11221ea4c376260ad015f496f07Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkAction3c13f11221ea4c376260ad015f496f07.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/admin/users/bulk-action'
 */
        bulkAction3c13f11221ea4c376260ad015f496f07Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkAction3c13f11221ea4c376260ad015f496f07.url(options),
            method: 'post',
        })
    
    bulkAction3c13f11221ea4c376260ad015f496f07.form = bulkAction3c13f11221ea4c376260ad015f496f07Form
    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
const bulkAction406e877c8716eac7ce7f5ef2c6dbf63c = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAction406e877c8716eac7ce7f5ef2c6dbf63c.url(options),
    method: 'post',
})

bulkAction406e877c8716eac7ce7f5ef2c6dbf63c.definition = {
    methods: ["post"],
    url: '/hr/users/bulk-action',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
bulkAction406e877c8716eac7ce7f5ef2c6dbf63c.url = (options?: RouteQueryOptions) => {
    return bulkAction406e877c8716eac7ce7f5ef2c6dbf63c.definition.url + queryParams(options)
}

/**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
bulkAction406e877c8716eac7ce7f5ef2c6dbf63c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: bulkAction406e877c8716eac7ce7f5ef2c6dbf63c.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
    const bulkAction406e877c8716eac7ce7f5ef2c6dbf63cForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: bulkAction406e877c8716eac7ce7f5ef2c6dbf63c.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Users\Http\Controllers\Admin\UserController::bulkAction
 * @see app/Features/Users/Http/Controllers/Admin/UserController.php:129
 * @route '/hr/users/bulk-action'
 */
        bulkAction406e877c8716eac7ce7f5ef2c6dbf63cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: bulkAction406e877c8716eac7ce7f5ef2c6dbf63c.url(options),
            method: 'post',
        })
    
    bulkAction406e877c8716eac7ce7f5ef2c6dbf63c.form = bulkAction406e877c8716eac7ce7f5ef2c6dbf63cForm

export const bulkAction = {
    '/admin/users/bulk-action': bulkAction3c13f11221ea4c376260ad015f496f07,
    '/hr/users/bulk-action': bulkAction406e877c8716eac7ce7f5ef2c6dbf63c,
}

const UserController = { index, store, update, destroy, toggleStatus, bulkAction }

export default UserController