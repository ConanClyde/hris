import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/api/ai/feedback/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
        exportMethodForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportMethod.form = exportMethodForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::summary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
export const summary = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: summary.url(options),
    method: 'get',
})

summary.definition = {
    methods: ["get","head"],
    url: '/api/ai/feedback/summary',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::summary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
summary.url = (options?: RouteQueryOptions) => {
    return summary.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::summary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
summary.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: summary.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::summary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
summary.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: summary.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::summary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
    const summaryForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: summary.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::summary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
        summaryForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: summary.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::summary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
        summaryForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: summary.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    summary.form = summaryForm
const feedback = {
    export: Object.assign(exportMethod, exportMethod),
summary: Object.assign(summary, summary),
}

export default feedback