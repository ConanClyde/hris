import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:15
 * @route '/admin/reports'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:15
 * @route '/admin/reports'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:15
 * @route '/admin/reports'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:15
 * @route '/admin/reports'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:15
 * @route '/admin/reports'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:15
 * @route '/admin/reports'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:15
 * @route '/admin/reports'
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
const ReportsController = { index }

export default ReportsController