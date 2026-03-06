import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::index
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:15
 * @route '/admin/performance'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/performance',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::index
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:15
 * @route '/admin/performance'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::index
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:15
 * @route '/admin/performance'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::index
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:15
 * @route '/admin/performance'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::index
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:15
 * @route '/admin/performance'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::index
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:15
 * @route '/admin/performance'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::index
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:15
 * @route '/admin/performance'
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
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::diagnostics
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:115
 * @route '/admin/performance/diagnostics'
 */
export const diagnostics = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: diagnostics.url(options),
    method: 'post',
})

diagnostics.definition = {
    methods: ["post"],
    url: '/admin/performance/diagnostics',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::diagnostics
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:115
 * @route '/admin/performance/diagnostics'
 */
diagnostics.url = (options?: RouteQueryOptions) => {
    return diagnostics.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::diagnostics
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:115
 * @route '/admin/performance/diagnostics'
 */
diagnostics.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: diagnostics.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::diagnostics
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:115
 * @route '/admin/performance/diagnostics'
 */
    const diagnosticsForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: diagnostics.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\PerformanceController::diagnostics
 * @see app/Features/Dashboard/Http/Controllers/PerformanceController.php:115
 * @route '/admin/performance/diagnostics'
 */
        diagnosticsForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: diagnostics.url(options),
            method: 'post',
        })
    
    diagnostics.form = diagnosticsForm
const PerformanceController = { index, diagnostics }

export default PerformanceController