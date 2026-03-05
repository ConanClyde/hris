import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::exportMethod
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:141
 * @route '/hr/reports/leave/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/hr/reports/leave/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::exportMethod
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:141
 * @route '/hr/reports/leave/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::exportMethod
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:141
 * @route '/hr/reports/leave/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::exportMethod
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:141
 * @route '/hr/reports/leave/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::exportMethod
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:141
 * @route '/hr/reports/leave/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::exportMethod
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:141
 * @route '/hr/reports/leave/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::exportMethod
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:141
 * @route '/hr/reports/leave/export'
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
const leave = {
    export: Object.assign(exportMethod, exportMethod),
}

export default leave