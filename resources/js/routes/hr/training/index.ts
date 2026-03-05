import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::index
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:21
 * @route '/hr/training'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/training',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::index
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:21
 * @route '/hr/training'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::index
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:21
 * @route '/hr/training'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::index
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:21
 * @route '/hr/training'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::index
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:21
 * @route '/hr/training'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::index
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:21
 * @route '/hr/training'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::index
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:21
 * @route '/hr/training'
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
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::store
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:102
 * @route '/hr/training'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/hr/training',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::store
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:102
 * @route '/hr/training'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::store
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:102
 * @route '/hr/training'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::store
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:102
 * @route '/hr/training'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::store
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:102
 * @route '/hr/training'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::update
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:166
 * @route '/hr/training/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/hr/training/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::update
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:166
 * @route '/hr/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::update
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:166
 * @route '/hr/training/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::update
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:166
 * @route '/hr/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::update
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:166
 * @route '/hr/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:230
 * @route '/hr/training/{id}'
 */
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/hr/training/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:230
 * @route '/hr/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:230
 * @route '/hr/training/{id}'
 */
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:230
 * @route '/hr/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\HR\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/HR/TrainingController.php:230
 * @route '/hr/training/{id}'
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
const training = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default training