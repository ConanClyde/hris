import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::answer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
export const answer = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: answer.url(options),
    method: 'post',
})

answer.definition = {
    methods: ["post"],
    url: '/api/ai/suggestions/answer',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::answer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
answer.url = (options?: RouteQueryOptions) => {
    return answer.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::answer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
answer.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: answer.url(options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::answer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
    const answerForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: answer.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::answer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
        answerForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: answer.url(options),
            method: 'post',
        })
    
    answer.form = answerForm
const suggestions = {
    answer: Object.assign(answer, answer),
}

export default suggestions