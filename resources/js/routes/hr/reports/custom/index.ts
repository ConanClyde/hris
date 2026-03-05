import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::exportMethod
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:44
 * @route '/hr/reports/custom/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/hr/reports/custom/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::exportMethod
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:44
 * @route '/hr/reports/custom/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::exportMethod
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:44
 * @route '/hr/reports/custom/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::exportMethod
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:44
 * @route '/hr/reports/custom/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::exportMethod
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:44
 * @route '/hr/reports/custom/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::exportMethod
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:44
 * @route '/hr/reports/custom/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::exportMethod
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:44
 * @route '/hr/reports/custom/export'
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
const custom = {
    export: Object.assign(exportMethod, exportMethod),
}

export default custom