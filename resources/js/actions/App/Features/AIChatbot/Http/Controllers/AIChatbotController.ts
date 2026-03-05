import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chat
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:152
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
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:152
 * @route '/ai-chatbot/chat'
 */
chat.url = (options?: RouteQueryOptions) => {
    return chat.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chat
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:152
 * @route '/ai-chatbot/chat'
 */
chat.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chat.url(options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chat
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:152
 * @route '/ai-chatbot/chat'
 */
    const chatForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: chat.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chat
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:152
 * @route '/ai-chatbot/chat'
 */
        chatForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: chat.url(options),
            method: 'post',
        })
    
    chat.form = chatForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chatStream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
export const chatStream = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chatStream.url(options),
    method: 'post',
})

chatStream.definition = {
    methods: ["post"],
    url: '/ai-chatbot/chat/stream',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chatStream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
chatStream.url = (options?: RouteQueryOptions) => {
    return chatStream.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chatStream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
chatStream.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: chatStream.url(options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chatStream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
    const chatStreamForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: chatStream.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::chatStream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
        chatStreamForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: chatStream.url(options),
            method: 'post',
        })
    
    chatStream.form = chatStreamForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1206
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
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1206
 * @route '/ai-chatbot/context'
 */
context.url = (options?: RouteQueryOptions) => {
    return context.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1206
 * @route '/ai-chatbot/context'
 */
context.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: context.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1206
 * @route '/ai-chatbot/context'
 */
context.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: context.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1206
 * @route '/ai-chatbot/context'
 */
    const contextForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: context.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1206
 * @route '/ai-chatbot/context'
 */
        contextForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: context.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:1206
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
const AIChatbotController = { chat, chatStream, context }

export default AIChatbotController