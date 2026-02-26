import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
export const update = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(options),
    method: 'put',
})

update.definition = {
    methods: ["put","patch"],
    url: '/admin/profile',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
update.url = (options?: RouteQueryOptions) => {
    return update.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
update.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(options),
    method: 'put',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
update.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update.url(options),
    method: 'patch',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
    const updateForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
        updateForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
        updateForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::password
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
export const password = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: password.url(options),
    method: 'post',
})

password.definition = {
    methods: ["post"],
    url: '/admin/profile/password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::password
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
password.url = (options?: RouteQueryOptions) => {
    return password.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::password
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
password.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: password.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::password
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
    const passwordForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: password.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::password
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
        passwordForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: password.url(options),
            method: 'post',
        })
    
    password.form = passwordForm
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::deleteMethod
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
export const deleteMethod = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteMethod.url(options),
    method: 'delete',
})

deleteMethod.definition = {
    methods: ["delete"],
    url: '/admin/profile',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::deleteMethod
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
deleteMethod.url = (options?: RouteQueryOptions) => {
    return deleteMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::deleteMethod
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
deleteMethod.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: deleteMethod.url(options),
    method: 'delete',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::deleteMethod
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
    const deleteMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: deleteMethod.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::deleteMethod
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
        deleteMethodForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: deleteMethod.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    deleteMethod.form = deleteMethodForm
const profile = {
    update: Object.assign(update, update),
password: Object.assign(password, password),
delete: Object.assign(deleteMethod, deleteMethod),
}

export default profile