import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../wayfinder'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
export const store = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/ai/conversations/{id}/messages',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
store.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return store.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
store.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
    const storeForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
        storeForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(args, options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recent
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
export const recent = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: recent.url(args, options),
    method: 'get',
})

recent.definition = {
    methods: ["get","head"],
    url: '/api/ai/conversations/{id}/messages/recent',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recent
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
recent.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return recent.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recent
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
recent.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: recent.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recent
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
recent.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: recent.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recent
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
    const recentForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: recent.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recent
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
        recentForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: recent.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recent
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
        recentForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: recent.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    recent.form = recentForm
const messages = {
    store: Object.assign(store, store),
recent: Object.assign(recent, recent),
}

export default messages