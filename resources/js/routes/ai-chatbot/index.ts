import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../wayfinder'
import chatB2e4da from './chat'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chat
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:158
 * @route '/ai-chatbot/chat'
 */
export const chat = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chat.url(options),
    method: 'post',
})

chat.definition = {
    methods: ["post"],
    url: '/ai-chatbot/chat',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chat
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:158
 * @route '/ai-chatbot/chat'
 */
chat.url = (options?: RouteQueryOptions) => {
    return chat.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chat
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:158
 * @route '/ai-chatbot/chat'
 */
chat.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chat.url(options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chat
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:158
 * @route '/ai-chatbot/chat'
 */
    const chatForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: chat.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chat
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:158
 * @route '/ai-chatbot/chat'
 */
        chatForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: chat.url(options),
            method: 'post',
        })
    
    chat.form = chatForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1212
 * @route '/ai-chatbot/context'
 */
export const context = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: context.url(options),
    method: 'get',
})

context.definition = {
    methods: ["get","head"],
    url: '/ai-chatbot/context',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1212
 * @route '/ai-chatbot/context'
 */
context.url = (options?: RouteQueryOptions) => {
    return context.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1212
 * @route '/ai-chatbot/context'
 */
context.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: context.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1212
 * @route '/ai-chatbot/context'
 */
context.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: context.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1212
 * @route '/ai-chatbot/context'
 */
    const contextForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: context.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1212
 * @route '/ai-chatbot/context'
 */
        contextForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: context.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1212
 * @route '/ai-chatbot/context'
 */
        contextForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: context.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    context.form = contextForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
export const policy = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: policy.url(args, options),
    method: 'get',
})

policy.definition = {
    methods: ["get","head"],
    url: '/ai-chatbot/policy/{filename}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
policy.url = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { filename: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    filename: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        filename: args.filename,
                }

    return policy.definition.url
            .replace('{filename}', parsedArgs.filename.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
policy.get = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: policy.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
policy.head = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: policy.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
    const policyForm = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: policy.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
        policyForm.get = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: policy.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
        policyForm.head = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: policy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    policy.form = policyForm
const aiChatbot = {
    chat: Object.assign(chat, chatB2e4da),
context: Object.assign(context, context),
policy: Object.assign(policy, policy),
}

export default aiChatbot