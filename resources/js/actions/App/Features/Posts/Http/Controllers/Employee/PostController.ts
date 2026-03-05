import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::index
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:20
 * @route '/employee/posts'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/employee/posts',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::index
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:20
 * @route '/employee/posts'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::index
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:20
 * @route '/employee/posts'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::index
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:20
 * @route '/employee/posts'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::index
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:20
 * @route '/employee/posts'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::index
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:20
 * @route '/employee/posts'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::index
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:20
 * @route '/employee/posts'
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
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::react
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:142
 * @route '/employee/posts/{post}/react'
 */
export const react = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: react.url(args, options),
    method: 'post',
})

react.definition = {
    methods: ["post"],
    url: '/employee/posts/{post}/react',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::react
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:142
 * @route '/employee/posts/{post}/react'
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
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::react
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:142
 * @route '/employee/posts/{post}/react'
 */
react.post = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: react.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::react
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:142
 * @route '/employee/posts/{post}/react'
 */
    const reactForm = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: react.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::react
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:142
 * @route '/employee/posts/{post}/react'
 */
        reactForm.post = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: react.url(args, options),
            method: 'post',
        })
    
    react.form = reactForm
/**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::comment
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:179
 * @route '/employee/posts/{post}/comments'
 */
export const comment = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: comment.url(args, options),
    method: 'post',
})

comment.definition = {
    methods: ["post"],
    url: '/employee/posts/{post}/comments',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::comment
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:179
 * @route '/employee/posts/{post}/comments'
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
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::comment
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:179
 * @route '/employee/posts/{post}/comments'
 */
comment.post = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: comment.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::comment
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:179
 * @route '/employee/posts/{post}/comments'
 */
    const commentForm = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: comment.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Posts\Http\Controllers\Employee\PostController::comment
 * @see app/Features/Posts/Http/Controllers/Employee/PostController.php:179
 * @route '/employee/posts/{post}/comments'
 */
        commentForm.post = (args: { post: number | { id: number } } | [post: number | { id: number } ] | number | { id: number }, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: comment.url(args, options),
            method: 'post',
        })
    
    comment.form = commentForm
const PostController = { index, react, comment }

export default PostController