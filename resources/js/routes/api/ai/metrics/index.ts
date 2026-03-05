import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/api/ai/metrics/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMethod
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
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
const metrics = {
    export: Object.assign(exportMethod, exportMethod),
}

export default metrics