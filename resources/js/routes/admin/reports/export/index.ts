import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::analytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
export const analytics = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analytics.url(options),
    method: 'get',
})

analytics.definition = {
    methods: ["get","head"],
    url: '/admin/reports/export/analytics',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::analytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
analytics.url = (options?: RouteQueryOptions) => {
    return analytics.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::analytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
analytics.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analytics.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::analytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
analytics.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: analytics.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::analytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
    const analyticsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: analytics.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::analytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
        analyticsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analytics.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::analytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
        analyticsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analytics.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    analytics.form = analyticsForm
const exportMethod = {
    analytics: Object.assign(analytics, analytics),
}

export default exportMethod