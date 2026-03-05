import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::index
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/reports/custom',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::index
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::index
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::index
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::index
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::index
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::index
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
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
const CustomReportController = { index, exportMethod, export: exportMethod }

export default CustomReportController