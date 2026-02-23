import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
const RedirectController4b87d2df7e3aa853f6720faea796e36c = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})

RedirectController4b87d2df7e3aa853f6720faea796e36c.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/settings',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.url = (options?: RouteQueryOptions) => {
    return RedirectController4b87d2df7e3aa853f6720faea796e36c.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
RedirectController4b87d2df7e3aa853f6720faea796e36c.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
    const RedirectController4b87d2df7e3aa853f6720faea796e36cForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/settings'
 */
        RedirectController4b87d2df7e3aa853f6720faea796e36cForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController4b87d2df7e3aa853f6720faea796e36c.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectController4b87d2df7e3aa853f6720faea796e36c.form = RedirectController4b87d2df7e3aa853f6720faea796e36cForm
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
const RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
    method: 'get',
})

RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/admin/employees',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url = (options?: RouteQueryOptions) => {
    return RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
    const RedirectControllerfe504fdd4f13044a7cbcb471d5b4210dForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
        RedirectControllerfe504fdd4f13044a7cbcb471d5b4210dForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
        RedirectControllerfe504fdd4f13044a7cbcb471d5b4210dForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
        RedirectControllerfe504fdd4f13044a7cbcb471d5b4210dForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
        RedirectControllerfe504fdd4f13044a7cbcb471d5b4210dForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
        RedirectControllerfe504fdd4f13044a7cbcb471d5b4210dForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
        RedirectControllerfe504fdd4f13044a7cbcb471d5b4210dForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/admin/employees'
 */
        RedirectControllerfe504fdd4f13044a7cbcb471d5b4210dForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d.form = RedirectControllerfe504fdd4f13044a7cbcb471d5b4210dForm
    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
const RedirectController411fb0baa81c8100b2b2b20d5d92f2cc = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
    method: 'get',
})

RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.definition = {
    methods: ["get","head","post","put","patch","delete","options"],
    url: '/hr/employees',
} satisfies RouteDefinition<["get","head","post","put","patch","delete","options"]>

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url = (options?: RouteQueryOptions) => {
    return RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.definition.url + queryParams(options)
}

/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
    method: 'get',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
    method: 'head',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
    method: 'post',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.put = (options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
    method: 'put',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.patch = (options?: RouteQueryOptions): RouteDefinition<'patch'> => ({
    url: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
    method: 'patch',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.delete = (options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
    method: 'delete',
})
/**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.options = (options?: RouteQueryOptions): RouteDefinition<'options'> => ({
    url: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
    method: 'options',
})

    /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
    const RedirectController411fb0baa81c8100b2b2b20d5d92f2ccForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
        method: 'get',
    })

            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
        RedirectController411fb0baa81c8100b2b2b20d5d92f2ccForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
        RedirectController411fb0baa81c8100b2b2b20d5d92f2ccForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
        RedirectController411fb0baa81c8100b2b2b20d5d92f2ccForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url(options),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
        RedirectController411fb0baa81c8100b2b2b20d5d92f2ccForm.put = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
        RedirectController411fb0baa81c8100b2b2b20d5d92f2ccForm.patch = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PATCH',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
        RedirectController411fb0baa81c8100b2b2b20d5d92f2ccForm.delete = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
            /**
* @see \Illuminate\Routing\RedirectController::__invoke
 * @see vendor/laravel/framework/src/Illuminate/Routing/RedirectController.php:19
 * @route '/hr/employees'
 */
        RedirectController411fb0baa81c8100b2b2b20d5d92f2ccForm.options = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'OPTIONS',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    RedirectController411fb0baa81c8100b2b2b20d5d92f2cc.form = RedirectController411fb0baa81c8100b2b2b20d5d92f2ccForm

const RedirectController = {
    '/settings': RedirectController4b87d2df7e3aa853f6720faea796e36c,
    '/admin/employees': RedirectControllerfe504fdd4f13044a7cbcb471d5b4210d,
    '/hr/employees': RedirectController411fb0baa81c8100b2b2b20d5d92f2cc,
}

export default RedirectController