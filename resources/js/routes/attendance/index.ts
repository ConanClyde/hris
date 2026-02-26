import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../wayfinder'
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
* @see \App\Http\Controllers\AttendanceController::history
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
export const history = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(options),
    method: 'get',
})

history.definition = {
    methods: ["get","head"],
    url: '/attendance/history',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Http\Controllers\AttendanceController::history
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
history.url = (options?: RouteQueryOptions) => {
    return history.definition.url + queryParams(options)
}

/**
* @see \App\Http\Controllers\AttendanceController::history
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
history.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: history.url(options),
    method: 'get',
})
/**
* @see \App\Http\Controllers\AttendanceController::history
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
history.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: history.url(options),
    method: 'head',
})

    /**
* @see \App\Http\Controllers\AttendanceController::history
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
    const historyForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: history.url(options),
        method: 'get',
    })

            /**
* @see \App\Http\Controllers\AttendanceController::history
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
        historyForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: history.url(options),
            method: 'get',
        })
            /**
* @see \App\Http\Controllers\AttendanceController::history
 * @see app/Http/Controllers/AttendanceController.php:102
 * @route '/attendance/history'
 */
        historyForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: history.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    history.form = historyForm
const attendance = {
    clockIn: Object.assign(clockIn, clockIn),
clockOut: Object.assign(clockOut, clockOut),
history: Object.assign(history, history),
}

export default attendance