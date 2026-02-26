import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../wayfinder'
/**
* @see \App\Http\Controllers\AttendanceController::clockIn
 * @see app/Http/Controllers/AttendanceController.php:15
 * @route '/attendance/clock-in'
 */
export const clockIn = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clockIn.url(options),
    method: 'post',
})

clockIn.definition = {
    methods: ["post"],
    url: '/attendance/clock-in',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::clockIn
 * @see app/Http/Controllers/AttendanceController.php:15
 * @route '/attendance/clock-in'
 */
clockIn.url = (options?: RouteQueryOptions) => {
    return clockIn.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::clockIn
 * @see app/Http/Controllers/AttendanceController.php:15
 * @route '/attendance/clock-in'
 */
clockIn.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clockIn.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::clockIn
 * @see app/Http/Controllers/AttendanceController.php:15
 * @route '/attendance/clock-in'
 */
    const clockInForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: clockIn.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::clockIn
 * @see app/Http/Controllers/AttendanceController.php:15
 * @route '/attendance/clock-in'
 */
        clockInForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: clockIn.url(options),
            method: 'post',
        })
    
    clockIn.form = clockInForm
/**
* @see \App\Http\Controllers\AttendanceController::clockOut
 * @see app/Http/Controllers/AttendanceController.php:58
 * @route '/attendance/clock-out'
 */
export const clockOut = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clockOut.url(options),
    method: 'post',
})

clockOut.definition = {
    methods: ["post"],
    url: '/attendance/clock-out',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Http\Controllers\AttendanceController::clockOut
 * @see app/Http/Controllers/AttendanceController.php:58
 * @route '/attendance/clock-out'
 */
clockOut.url = (options?: RouteQueryOptions) => {
    return clockOut.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::clockOut
 * @see app/Http/Controllers/AttendanceController.php:58
 * @route '/attendance/clock-out'
 */
clockOut.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: clockOut.url(options),
    method: 'post',
})

    /**
* @see \App\Http\Controllers\AttendanceController::clockOut
 * @see app/Http/Controllers/AttendanceController.php:58
 * @route '/attendance/clock-out'
 */
    const clockOutForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: clockOut.url(options),
        method: 'post',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::clockOut
 * @see app/Http/Controllers/AttendanceController.php:58
 * @route '/attendance/clock-out'
 */
        clockOutForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: clockOut.url(options),
            method: 'post',
        })
    
    clockOut.form = clockOutForm
/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/attendance/history',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::index
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
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
const AttendanceController = { clockIn, clockOut, index }

export default AttendanceController