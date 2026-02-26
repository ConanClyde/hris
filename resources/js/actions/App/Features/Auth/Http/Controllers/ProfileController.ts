import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/admin/profile'
 */
const show2b603298152ec5dd9b14768a8a90e70d = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show2b603298152ec5dd9b14768a8a90e70d.url(options),
    method: 'get',
})

show2b603298152ec5dd9b14768a8a90e70d.definition = {
    methods: ["get","head"],
    url: '/admin/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/admin/profile'
 */
show2b603298152ec5dd9b14768a8a90e70d.url = (options?: RouteQueryOptions) => {
    return show2b603298152ec5dd9b14768a8a90e70d.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/admin/profile'
 */
show2b603298152ec5dd9b14768a8a90e70d.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show2b603298152ec5dd9b14768a8a90e70d.url(options),
    method: 'get',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/admin/profile'
 */
show2b603298152ec5dd9b14768a8a90e70d.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show2b603298152ec5dd9b14768a8a90e70d.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/admin/profile'
 */
    const show2b603298152ec5dd9b14768a8a90e70dForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show2b603298152ec5dd9b14768a8a90e70d.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/admin/profile'
 */
        show2b603298152ec5dd9b14768a8a90e70dForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show2b603298152ec5dd9b14768a8a90e70d.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/admin/profile'
 */
        show2b603298152ec5dd9b14768a8a90e70dForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show2b603298152ec5dd9b14768a8a90e70d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show2b603298152ec5dd9b14768a8a90e70d.form = show2b603298152ec5dd9b14768a8a90e70dForm
    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
const showd2645a0b46c24ab47de650117e769cf7 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showd2645a0b46c24ab47de650117e769cf7.url(options),
    method: 'get',
})

showd2645a0b46c24ab47de650117e769cf7.definition = {
    methods: ["get","head"],
    url: '/hr/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
showd2645a0b46c24ab47de650117e769cf7.url = (options?: RouteQueryOptions) => {
    return showd2645a0b46c24ab47de650117e769cf7.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
showd2645a0b46c24ab47de650117e769cf7.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showd2645a0b46c24ab47de650117e769cf7.url(options),
    method: 'get',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
showd2645a0b46c24ab47de650117e769cf7.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showd2645a0b46c24ab47de650117e769cf7.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
    const showd2645a0b46c24ab47de650117e769cf7Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: showd2645a0b46c24ab47de650117e769cf7.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
        showd2645a0b46c24ab47de650117e769cf7Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showd2645a0b46c24ab47de650117e769cf7.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/hr/profile'
 */
        showd2645a0b46c24ab47de650117e769cf7Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showd2645a0b46c24ab47de650117e769cf7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    showd2645a0b46c24ab47de650117e769cf7.form = showd2645a0b46c24ab47de650117e769cf7Form
    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/employee/profile'
 */
const showf111ba1dba6158532c68538988b2b61c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showf111ba1dba6158532c68538988b2b61c.url(options),
    method: 'get',
})

showf111ba1dba6158532c68538988b2b61c.definition = {
    methods: ["get","head"],
    url: '/employee/profile',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/employee/profile'
 */
showf111ba1dba6158532c68538988b2b61c.url = (options?: RouteQueryOptions) => {
    return showf111ba1dba6158532c68538988b2b61c.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/employee/profile'
 */
showf111ba1dba6158532c68538988b2b61c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: showf111ba1dba6158532c68538988b2b61c.url(options),
    method: 'get',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/employee/profile'
 */
showf111ba1dba6158532c68538988b2b61c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: showf111ba1dba6158532c68538988b2b61c.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/employee/profile'
 */
    const showf111ba1dba6158532c68538988b2b61cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: showf111ba1dba6158532c68538988b2b61c.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/employee/profile'
 */
        showf111ba1dba6158532c68538988b2b61cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showf111ba1dba6158532c68538988b2b61c.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::show
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:17
 * @route '/employee/profile'
 */
        showf111ba1dba6158532c68538988b2b61cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: showf111ba1dba6158532c68538988b2b61c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    showf111ba1dba6158532c68538988b2b61c.form = showf111ba1dba6158532c68538988b2b61cForm

export const show = {
    '/admin/profile': show2b603298152ec5dd9b14768a8a90e70d,
    '/hr/profile': showd2645a0b46c24ab47de650117e769cf7,
    '/employee/profile': showf111ba1dba6158532c68538988b2b61c,
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
const update2b603298152ec5dd9b14768a8a90e70d = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update2b603298152ec5dd9b14768a8a90e70d.url(options),
    method: 'put',
})

update2b603298152ec5dd9b14768a8a90e70d.definition = {
    methods: ["put","patch"],
    url: '/admin/profile',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
update2b603298152ec5dd9b14768a8a90e70d.url = (options?: RouteQueryOptions) => {
    return update2b603298152ec5dd9b14768a8a90e70d.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
update2b603298152ec5dd9b14768a8a90e70d.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update2b603298152ec5dd9b14768a8a90e70d.url(options),
    method: 'put',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
update2b603298152ec5dd9b14768a8a90e70d.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: update2b603298152ec5dd9b14768a8a90e70d.url(options),
    method: 'patch',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/admin/profile'
 */
    const update2b603298152ec5dd9b14768a8a90e70dForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update2b603298152ec5dd9b14768a8a90e70d.url({
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
        update2b603298152ec5dd9b14768a8a90e70dForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update2b603298152ec5dd9b14768a8a90e70d.url({
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
        update2b603298152ec5dd9b14768a8a90e70dForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update2b603298152ec5dd9b14768a8a90e70d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update2b603298152ec5dd9b14768a8a90e70d.form = update2b603298152ec5dd9b14768a8a90e70dForm
    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/hr/profile'
 */
const updated2645a0b46c24ab47de650117e769cf7 = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updated2645a0b46c24ab47de650117e769cf7.url(options),
    method: 'put',
})

updated2645a0b46c24ab47de650117e769cf7.definition = {
    methods: ["put","patch"],
    url: '/hr/profile',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/hr/profile'
 */
updated2645a0b46c24ab47de650117e769cf7.url = (options?: RouteQueryOptions) => {
    return updated2645a0b46c24ab47de650117e769cf7.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/hr/profile'
 */
updated2645a0b46c24ab47de650117e769cf7.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updated2645a0b46c24ab47de650117e769cf7.url(options),
    method: 'put',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/hr/profile'
 */
updated2645a0b46c24ab47de650117e769cf7.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updated2645a0b46c24ab47de650117e769cf7.url(options),
    method: 'patch',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/hr/profile'
 */
    const updated2645a0b46c24ab47de650117e769cf7Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updated2645a0b46c24ab47de650117e769cf7.url({
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
 * @route '/hr/profile'
 */
        updated2645a0b46c24ab47de650117e769cf7Form.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updated2645a0b46c24ab47de650117e769cf7.url({
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
 * @route '/hr/profile'
 */
        updated2645a0b46c24ab47de650117e769cf7Form.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updated2645a0b46c24ab47de650117e769cf7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updated2645a0b46c24ab47de650117e769cf7.form = updated2645a0b46c24ab47de650117e769cf7Form
    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/employee/profile'
 */
const updatef111ba1dba6158532c68538988b2b61c = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updatef111ba1dba6158532c68538988b2b61c.url(options),
    method: 'put',
})

updatef111ba1dba6158532c68538988b2b61c.definition = {
    methods: ["put","patch"],
    url: '/employee/profile',
} satisfies RouteDefinition<["put","patch"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/employee/profile'
 */
updatef111ba1dba6158532c68538988b2b61c.url = (options?: RouteQueryOptions) => {
    return updatef111ba1dba6158532c68538988b2b61c.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/employee/profile'
 */
updatef111ba1dba6158532c68538988b2b61c.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updatef111ba1dba6158532c68538988b2b61c.url(options),
    method: 'put',
})
/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/employee/profile'
 */
updatef111ba1dba6158532c68538988b2b61c.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: updatef111ba1dba6158532c68538988b2b61c.url(options),
    method: 'patch',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::update
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:62
 * @route '/employee/profile'
 */
    const updatef111ba1dba6158532c68538988b2b61cForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updatef111ba1dba6158532c68538988b2b61c.url({
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
 * @route '/employee/profile'
 */
        updatef111ba1dba6158532c68538988b2b61cForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updatef111ba1dba6158532c68538988b2b61c.url({
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
 * @route '/employee/profile'
 */
        updatef111ba1dba6158532c68538988b2b61cForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updatef111ba1dba6158532c68538988b2b61c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updatef111ba1dba6158532c68538988b2b61c.form = updatef111ba1dba6158532c68538988b2b61cForm

export const update = {
    '/admin/profile': update2b603298152ec5dd9b14768a8a90e70d,
    '/hr/profile': updated2645a0b46c24ab47de650117e769cf7,
    '/employee/profile': updatef111ba1dba6158532c68538988b2b61c,
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
const changePasswordf80fad1a7f3fe5580dfd4f25459504ed = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: changePasswordf80fad1a7f3fe5580dfd4f25459504ed.url(options),
    method: 'post',
})

changePasswordf80fad1a7f3fe5580dfd4f25459504ed.definition = {
    methods: ["post"],
    url: '/admin/profile/password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
changePasswordf80fad1a7f3fe5580dfd4f25459504ed.url = (options?: RouteQueryOptions) => {
    return changePasswordf80fad1a7f3fe5580dfd4f25459504ed.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
changePasswordf80fad1a7f3fe5580dfd4f25459504ed.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: changePasswordf80fad1a7f3fe5580dfd4f25459504ed.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
    const changePasswordf80fad1a7f3fe5580dfd4f25459504edForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: changePasswordf80fad1a7f3fe5580dfd4f25459504ed.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/admin/profile/password'
 */
        changePasswordf80fad1a7f3fe5580dfd4f25459504edForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: changePasswordf80fad1a7f3fe5580dfd4f25459504ed.url(options),
            method: 'post',
        })
    
    changePasswordf80fad1a7f3fe5580dfd4f25459504ed.form = changePasswordf80fad1a7f3fe5580dfd4f25459504edForm
    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/hr/profile/password'
 */
const changePassword59c7bb994b3b9689495243f852195800 = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: changePassword59c7bb994b3b9689495243f852195800.url(options),
    method: 'post',
})

changePassword59c7bb994b3b9689495243f852195800.definition = {
    methods: ["post"],
    url: '/hr/profile/password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/hr/profile/password'
 */
changePassword59c7bb994b3b9689495243f852195800.url = (options?: RouteQueryOptions) => {
    return changePassword59c7bb994b3b9689495243f852195800.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/hr/profile/password'
 */
changePassword59c7bb994b3b9689495243f852195800.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: changePassword59c7bb994b3b9689495243f852195800.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/hr/profile/password'
 */
    const changePassword59c7bb994b3b9689495243f852195800Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: changePassword59c7bb994b3b9689495243f852195800.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/hr/profile/password'
 */
        changePassword59c7bb994b3b9689495243f852195800Form.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: changePassword59c7bb994b3b9689495243f852195800.url(options),
            method: 'post',
        })
    
    changePassword59c7bb994b3b9689495243f852195800.form = changePassword59c7bb994b3b9689495243f852195800Form
    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/employee/profile/password'
 */
const changePassword8c74fa418f5b1aa2dc1372d932c84fba = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: changePassword8c74fa418f5b1aa2dc1372d932c84fba.url(options),
    method: 'post',
})

changePassword8c74fa418f5b1aa2dc1372d932c84fba.definition = {
    methods: ["post"],
    url: '/employee/profile/password',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/employee/profile/password'
 */
changePassword8c74fa418f5b1aa2dc1372d932c84fba.url = (options?: RouteQueryOptions) => {
    return changePassword8c74fa418f5b1aa2dc1372d932c84fba.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/employee/profile/password'
 */
changePassword8c74fa418f5b1aa2dc1372d932c84fba.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: changePassword8c74fa418f5b1aa2dc1372d932c84fba.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/employee/profile/password'
 */
    const changePassword8c74fa418f5b1aa2dc1372d932c84fbaForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: changePassword8c74fa418f5b1aa2dc1372d932c84fba.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::changePassword
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:119
 * @route '/employee/profile/password'
 */
        changePassword8c74fa418f5b1aa2dc1372d932c84fbaForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: changePassword8c74fa418f5b1aa2dc1372d932c84fba.url(options),
            method: 'post',
        })
    
    changePassword8c74fa418f5b1aa2dc1372d932c84fba.form = changePassword8c74fa418f5b1aa2dc1372d932c84fbaForm

export const changePassword = {
    '/admin/profile/password': changePasswordf80fad1a7f3fe5580dfd4f25459504ed,
    '/hr/profile/password': changePassword59c7bb994b3b9689495243f852195800,
    '/employee/profile/password': changePassword8c74fa418f5b1aa2dc1372d932c84fba,
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
const destroy2b603298152ec5dd9b14768a8a90e70d = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy2b603298152ec5dd9b14768a8a90e70d.url(options),
    method: 'delete',
})

destroy2b603298152ec5dd9b14768a8a90e70d.definition = {
    methods: ["delete"],
    url: '/admin/profile',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
destroy2b603298152ec5dd9b14768a8a90e70d.url = (options?: RouteQueryOptions) => {
    return destroy2b603298152ec5dd9b14768a8a90e70d.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
destroy2b603298152ec5dd9b14768a8a90e70d.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy2b603298152ec5dd9b14768a8a90e70d.url(options),
    method: 'delete',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
    const destroy2b603298152ec5dd9b14768a8a90e70dForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy2b603298152ec5dd9b14768a8a90e70d.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/admin/profile'
 */
        destroy2b603298152ec5dd9b14768a8a90e70dForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy2b603298152ec5dd9b14768a8a90e70d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy2b603298152ec5dd9b14768a8a90e70d.form = destroy2b603298152ec5dd9b14768a8a90e70dForm
    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/hr/profile'
 */
const destroyd2645a0b46c24ab47de650117e769cf7 = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyd2645a0b46c24ab47de650117e769cf7.url(options),
    method: 'delete',
})

destroyd2645a0b46c24ab47de650117e769cf7.definition = {
    methods: ["delete"],
    url: '/hr/profile',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/hr/profile'
 */
destroyd2645a0b46c24ab47de650117e769cf7.url = (options?: RouteQueryOptions) => {
    return destroyd2645a0b46c24ab47de650117e769cf7.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/hr/profile'
 */
destroyd2645a0b46c24ab47de650117e769cf7.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyd2645a0b46c24ab47de650117e769cf7.url(options),
    method: 'delete',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/hr/profile'
 */
    const destroyd2645a0b46c24ab47de650117e769cf7Form = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroyd2645a0b46c24ab47de650117e769cf7.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/hr/profile'
 */
        destroyd2645a0b46c24ab47de650117e769cf7Form.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroyd2645a0b46c24ab47de650117e769cf7.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroyd2645a0b46c24ab47de650117e769cf7.form = destroyd2645a0b46c24ab47de650117e769cf7Form
    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/employee/profile'
 */
const destroyf111ba1dba6158532c68538988b2b61c = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyf111ba1dba6158532c68538988b2b61c.url(options),
    method: 'delete',
})

destroyf111ba1dba6158532c68538988b2b61c.definition = {
    methods: ["delete"],
    url: '/employee/profile',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/employee/profile'
 */
destroyf111ba1dba6158532c68538988b2b61c.url = (options?: RouteQueryOptions) => {
    return destroyf111ba1dba6158532c68538988b2b61c.definition.url + queryParams(options)
}

/**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/employee/profile'
 */
destroyf111ba1dba6158532c68538988b2b61c.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroyf111ba1dba6158532c68538988b2b61c.url(options),
    method: 'delete',
})

    /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/employee/profile'
 */
    const destroyf111ba1dba6158532c68538988b2b61cForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroyf111ba1dba6158532c68538988b2b61c.url({
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Auth\Http\Controllers\ProfileController::destroy
 * @see app/Features/Auth/Http/Controllers/ProfileController.php:137
 * @route '/employee/profile'
 */
        destroyf111ba1dba6158532c68538988b2b61cForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroyf111ba1dba6158532c68538988b2b61c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroyf111ba1dba6158532c68538988b2b61c.form = destroyf111ba1dba6158532c68538988b2b61cForm

export const destroy = {
    '/admin/profile': destroy2b603298152ec5dd9b14768a8a90e70d,
    '/hr/profile': destroyd2645a0b46c24ab47de650117e769cf7,
    '/employee/profile': destroyf111ba1dba6158532c68538988b2b61c,
}

const ProfileController = { show, update, changePassword, destroy }

export default ProfileController