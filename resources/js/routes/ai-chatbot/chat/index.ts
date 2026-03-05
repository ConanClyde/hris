import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::stream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
export const stream = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: stream.url(options),
    method: 'post',
})

stream.definition = {
    methods: ["post"],
    url: '/ai-chatbot/chat/stream',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::stream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
stream.url = (options?: RouteQueryOptions) => {
    return stream.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::stream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
stream.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: stream.url(options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::stream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
    const streamForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: stream.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotController::stream
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotController.php:378
 * @route '/ai-chatbot/chat/stream'
 */
        streamForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: stream.url(options),
            method: 'post',
        })
    
    stream.form = streamForm
const chat = {
    stream: Object.assign(stream, stream),
}

export default chat