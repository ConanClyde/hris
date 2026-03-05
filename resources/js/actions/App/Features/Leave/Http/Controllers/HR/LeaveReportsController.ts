import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/reports/leave',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::index
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
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
const LeaveReportsController = { index, exportMethod, export: exportMethod }

export default LeaveReportsController