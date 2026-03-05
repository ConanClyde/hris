import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
import customF9d1a8 from './custom'
import leaveD5e2cf from './leave'
/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::custom
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
export const custom = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: custom.url(options),
    method: 'get',
})

custom.definition = {
    methods: ["get","head"],
    url: '/hr/reports/custom',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::custom
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
custom.url = (options?: RouteQueryOptions) => {
    return custom.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::custom
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
custom.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: custom.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::custom
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
custom.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: custom.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::custom
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
    const customForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: custom.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::custom
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
        customForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: custom.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\CustomReportController::custom
 * @see app/Features/Dashboard/Http/Controllers/CustomReportController.php:37
 * @route '/hr/reports/custom'
 */
        customForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: custom.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    custom.form = customForm
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::leave
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
export const leave = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leave.url(options),
    method: 'get',
})

leave.definition = {
    methods: ["get","head"],
    url: '/hr/reports/leave',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::leave
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
leave.url = (options?: RouteQueryOptions) => {
    return leave.definition.url + queryParams(options)
}

/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::leave
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
leave.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leave.url(options),
    method: 'get',
})
/**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::leave
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
leave.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: leave.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::leave
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
    const leaveForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: leave.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::leave
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
        leaveForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leave.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Leave\Http\Controllers\HR\LeaveReportsController::leave
 * @see app/Features/Leave/Http/Controllers/HR/LeaveReportsController.php:16
 * @route '/hr/reports/leave'
 */
        leaveForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leave.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    leave.form = leaveForm
const reports = {
    custom: Object.assign(custom, customF9d1a8),
leave: Object.assign(leave, leaveD5e2cf),
}

export default reports