import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
/**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::show
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:13
 * @route '/force-change-password'
 */
export const show = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/force-change-password',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::show
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:13
 * @route '/force-change-password'
 */
show.url = (options?: RouteQueryOptions) => {
    return show.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::show
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:13
 * @route '/force-change-password'
 */
show.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(options),
    method: 'get',
})
/**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::show
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:13
 * @route '/force-change-password'
 */
show.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::show
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:13
 * @route '/force-change-password'
 */
    const showForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::show
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:13
 * @route '/force-change-password'
 */
        showForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::show
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:13
 * @route '/force-change-password'
 */
        showForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::update
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:18
 * @route '/force-change-password'
 */
export const update = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/force-change-password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::update
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:18
 * @route '/force-change-password'
 */
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::update
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:18
 * @route '/force-change-password'
 */
update.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::update
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:18
 * @route '/force-change-password'
 */
    const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ForceChangePasswordController::update
 * @see app/Features/Auth/Http/Controllers/ForceChangePasswordController.php:18
 * @route '/force-change-password'
 */
        updateForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(options),
            method: 'post',
        })
    
    update.form = updateForm
const forcePassword = {
    show: Object.assign(show, show),
update: Object.assign(update, update),
}

export default forcePassword