import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import attachment from './attachment'
/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::index
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:13
 * @route '/employee/training'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/employee/training',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::index
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:13
 * @route '/employee/training'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::index
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:13
 * @route '/employee/training'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::index
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:13
 * @route '/employee/training'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::index
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:13
 * @route '/employee/training'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::index
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:13
 * @route '/employee/training'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::index
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:13
 * @route '/employee/training'
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
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::store
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:32
 * @route '/employee/training'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/employee/training',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::store
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:32
 * @route '/employee/training'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::store
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:32
 * @route '/employee/training'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::store
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:32
 * @route '/employee/training'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::store
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:32
 * @route '/employee/training'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::update
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:53
 * @route '/employee/training/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/employee/training/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::update
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:53
 * @route '/employee/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::update
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:53
 * @route '/employee/training/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::update
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:53
 * @route '/employee/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::update
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:53
 * @route '/employee/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:70
 * @route '/employee/training/{id}'
 */
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/employee/training/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:70
 * @route '/employee/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:70
 * @route '/employee/training/{id}'
 */
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:70
 * @route '/employee/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::destroy
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:70
 * @route '/employee/training/{id}'
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
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::exportMethod
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:77
 * @route '/employee/training/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/employee/training/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::exportMethod
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:77
 * @route '/employee/training/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::exportMethod
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:77
 * @route '/employee/training/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::exportMethod
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:77
 * @route '/employee/training/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::exportMethod
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:77
 * @route '/employee/training/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::exportMethod
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:77
 * @route '/employee/training/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Training\Http\Controllers\Employee\TrainingController::exportMethod
 * @see app/Features/Training/Http/Controllers/Employee/TrainingController.php:77
 * @route '/employee/training/export'
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
const training = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
export: Object.assign(exportMethod, exportMethod),
attachment: Object.assign(attachment, attachment),
}

export default training