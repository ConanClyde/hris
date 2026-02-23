import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::index
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:12
 * @route '/hr/notices'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/notices',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::index
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:12
 * @route '/hr/notices'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::index
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:12
 * @route '/hr/notices'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::index
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:12
 * @route '/hr/notices'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::index
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:12
 * @route '/hr/notices'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::index
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:12
 * @route '/hr/notices'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::index
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:12
 * @route '/hr/notices'
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
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::create
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:21
 * @route '/hr/notices/create'
 */
export const create = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})

create.definition = {
    methods: ["get","head"],
    url: '/hr/notices/create',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::create
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:21
 * @route '/hr/notices/create'
 */
create.url = (options?: RouteQueryOptions) => {
    return create.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::create
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:21
 * @route '/hr/notices/create'
 */
create.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: create.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::create
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:21
 * @route '/hr/notices/create'
 */
create.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: create.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::create
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:21
 * @route '/hr/notices/create'
 */
    const createForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: create.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::create
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:21
 * @route '/hr/notices/create'
 */
        createForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::create
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:21
 * @route '/hr/notices/create'
 */
        createForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: create.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    create.form = createForm
/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::store
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:26
 * @route '/hr/notices'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/hr/notices',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::store
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:26
 * @route '/hr/notices'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::store
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:26
 * @route '/hr/notices'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::store
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:26
 * @route '/hr/notices'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::store
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:26
 * @route '/hr/notices'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::edit
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:47
 * @route '/hr/notices/{id}/edit'
 */
export const edit = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})

edit.definition = {
    methods: ["get","head"],
    url: '/hr/notices/{id}/edit',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::edit
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:47
 * @route '/hr/notices/{id}/edit'
 */
edit.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return edit.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::edit
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:47
 * @route '/hr/notices/{id}/edit'
 */
edit.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: edit.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::edit
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:47
 * @route '/hr/notices/{id}/edit'
 */
edit.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: edit.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::edit
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:47
 * @route '/hr/notices/{id}/edit'
 */
    const editForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: edit.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::edit
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:47
 * @route '/hr/notices/{id}/edit'
 */
        editForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::edit
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:47
 * @route '/hr/notices/{id}/edit'
 */
        editForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: edit.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    edit.form = editForm
/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::update
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:52
 * @route '/hr/notices/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/hr/notices/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::update
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:52
 * @route '/hr/notices/{id}'
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
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::update
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:52
 * @route '/hr/notices/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::update
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:52
 * @route '/hr/notices/{id}'
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
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::update
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:52
 * @route '/hr/notices/{id}'
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
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::destroy
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:75
 * @route '/hr/notices/{id}'
 */
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/hr/notices/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::destroy
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:75
 * @route '/hr/notices/{id}'
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
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::destroy
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:75
 * @route '/hr/notices/{id}'
 */
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::destroy
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:75
 * @route '/hr/notices/{id}'
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
* @see \App\Features\Notices\Http\Controllers\HR\NoticeController::destroy
 * @see app/Features/Notices/Http/Controllers/HR/NoticeController.php:75
 * @route '/hr/notices/{id}'
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
const NoticeController = { index, create, store, edit, update, destroy }

export default NoticeController