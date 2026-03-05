import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personal
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
export const personal = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: personal.url(options),
    method: 'get',
})

personal.definition = {
    methods: ["get","head"],
    url: '/api/ai/insights/personal',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personal
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
personal.url = (options?: RouteQueryOptions) => {
    return personal.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personal
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
personal.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: personal.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personal
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
personal.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: personal.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personal
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
    const personalForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: personal.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personal
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
        personalForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: personal.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personal
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
        personalForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: personal.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    personal.form = personalForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
export const feedback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: feedback.url(options),
    method: 'get',
})

feedback.definition = {
    methods: ["get","head"],
    url: '/api/ai/insights/feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
feedback.url = (options?: RouteQueryOptions) => {
    return feedback.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
feedback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: feedback.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
feedback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: feedback.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
    const feedbackForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: feedback.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
        feedbackForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: feedback.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
        feedbackForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: feedback.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    feedback.form = feedbackForm
const insights = {
    personal: Object.assign(personal, personal),
feedback: Object.assign(feedback, feedback),
}

export default insights