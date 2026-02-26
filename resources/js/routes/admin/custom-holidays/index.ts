import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:19
 * @route '/admin/custom-holidays'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/custom-holidays',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:19
 * @route '/admin/custom-holidays'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:19
 * @route '/admin/custom-holidays'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:19
 * @route '/admin/custom-holidays'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:19
 * @route '/admin/custom-holidays'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:19
 * @route '/admin/custom-holidays'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:19
 * @route '/admin/custom-holidays'
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
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::store
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:24
 * @route '/admin/custom-holidays'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/admin/custom-holidays',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::store
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:24
 * @route '/admin/custom-holidays'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::store
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:24
 * @route '/admin/custom-holidays'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::store
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:24
 * @route '/admin/custom-holidays'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::store
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:24
 * @route '/admin/custom-holidays'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::update
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:53
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
export const update = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/custom-holidays/{custom_holiday}',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::update
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:53
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
update.url = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { custom_holiday: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    custom_holiday: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        custom_holiday: args.custom_holiday,
                }

    return update.definition.url
            .replace('{custom_holiday}', parsedArgs.custom_holiday.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::update
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:53
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
update.put = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})
/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::update
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:53
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
update.patch = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(args, options),
    method: 'patch',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::update
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:53
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
    const updateForm = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::update
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:53
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
        updateForm.put = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::update
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:53
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
        updateForm.patch = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::destroy
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:86
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
export const destroy = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/custom-holidays/{custom_holiday}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::destroy
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:86
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
destroy.url = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { custom_holiday: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    custom_holiday: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        custom_holiday: args.custom_holiday,
                }

    return destroy.definition.url
            .replace('{custom_holiday}', parsedArgs.custom_holiday.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::destroy
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:86
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
destroy.delete = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::destroy
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:86
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
    const destroyForm = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CustomHolidayController::destroy
 * @see app/Features/Calendar/Http/Controllers/Admin/CustomHolidayController.php:86
 * @route '/admin/custom-holidays/{custom_holiday}'
 */
        destroyForm.delete = (args: { custom_holiday: string | number } | [custom_holiday: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const customHolidays = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default customHolidays