import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::index
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:23
 * @route '/hr/posts'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/posts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::index
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:23
 * @route '/hr/posts'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::index
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:23
 * @route '/hr/posts'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::index
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:23
 * @route '/hr/posts'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::index
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:23
 * @route '/hr/posts'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::index
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:23
 * @route '/hr/posts'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::index
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:23
 * @route '/hr/posts'
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
* @see \App\Features\Posts\Http\Controllers\HR\PostController::store
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:158
 * @route '/hr/posts'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/hr/posts',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::store
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:158
 * @route '/hr/posts'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::store
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:158
 * @route '/hr/posts'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::store
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:158
 * @route '/hr/posts'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::store
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:158
 * @route '/hr/posts'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::react
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:227
 * @route '/hr/posts/{post}/react'
 */
export const react = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: react.url(args, options),
    method: 'post',
})

react.definition = {
    methods: ["post"],
    url: '/hr/posts/{post}/react',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::react
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:227
 * @route '/hr/posts/{post}/react'
 */
react.url = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { post: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { post: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    post: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        post: typeof args.post === 'object'
                ? args.post.id
                : args.post,
                }

    return react.definition.url
            .replace('{post}', parsedArgs.post.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::react
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:227
 * @route '/hr/posts/{post}/react'
 */
react.post = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: react.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::react
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:227
 * @route '/hr/posts/{post}/react'
 */
    const reactForm = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: react.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::react
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:227
 * @route '/hr/posts/{post}/react'
 */
        reactForm.post = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: react.url(args, options),
            method: 'post',
        })
    
    react.form = reactForm
/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::comment
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:263
 * @route '/hr/posts/{post}/comments'
 */
export const comment = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: comment.url(args, options),
    method: 'post',
})

comment.definition = {
    methods: ["post"],
    url: '/hr/posts/{post}/comments',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::comment
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:263
 * @route '/hr/posts/{post}/comments'
 */
comment.url = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { post: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { post: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    post: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        post: typeof args.post === 'object'
                ? args.post.id
                : args.post,
                }

    return comment.definition.url
            .replace('{post}', parsedArgs.post.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::comment
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:263
 * @route '/hr/posts/{post}/comments'
 */
comment.post = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: comment.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::comment
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:263
 * @route '/hr/posts/{post}/comments'
 */
    const commentForm = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: comment.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::comment
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:263
 * @route '/hr/posts/{post}/comments'
 */
        commentForm.post = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: comment.url(args, options),
            method: 'post',
        })
    
    comment.form = commentForm
/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::update
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:199
 * @route '/hr/posts/{post}'
 */
export const update = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/hr/posts/{post}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::update
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:199
 * @route '/hr/posts/{post}'
 */
update.url = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { post: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { post: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    post: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        post: typeof args.post === 'object'
                ? args.post.id
                : args.post,
                }

    return update.definition.url
            .replace('{post}', parsedArgs.post.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::update
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:199
 * @route '/hr/posts/{post}'
 */
update.put = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::update
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:199
 * @route '/hr/posts/{post}'
 */
    const updateForm = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::update
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:199
 * @route '/hr/posts/{post}'
 */
        updateForm.put = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
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
* @see \App\Features\Posts\Http\Controllers\HR\PostController::destroy
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:220
 * @route '/hr/posts/{post}'
 */
export const destroy = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/hr/posts/{post}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::destroy
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:220
 * @route '/hr/posts/{post}'
 */
destroy.url = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { post: args }
    }

            if (typeof args === 'object' && !Array.isArray(args) && 'id' in args) {
            args = { post: args.id }
        }
    
    if (Array.isArray(args)) {
        args = {
                    post: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        post: typeof args.post === 'object'
                ? args.post.id
                : args.post,
                }

    return destroy.definition.url
            .replace('{post}', parsedArgs.post.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::destroy
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:220
 * @route '/hr/posts/{post}'
 */
destroy.delete = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::destroy
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:220
 * @route '/hr/posts/{post}'
 */
    const destroyForm = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Posts\Http\Controllers\HR\PostController::destroy
 * @see app/Features/Posts/Http/Controllers/HR/PostController.php:220
 * @route '/hr/posts/{post}'
 */
        destroyForm.delete = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
const posts = {
    index: Object.assign(index, index),
store: Object.assign(store, store),
react: Object.assign(react, react),
comment: Object.assign(comment, comment),
update: Object.assign(update, update),
destroy: Object.assign(destroy, destroy),
}

export default posts