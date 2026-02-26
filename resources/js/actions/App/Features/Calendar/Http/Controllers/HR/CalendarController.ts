import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/calendar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:18
 * @route '/hr/calendar'
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
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:23
 * @route '/hr/calendar/events'
 */
export const events = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: events.url(options),
    method: 'get',
})

events.definition = {
    methods: ["get","head"],
    url: '/hr/calendar/events',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:23
 * @route '/hr/calendar/events'
 */
events.url = (options?: RouteQueryOptions) => {
    return events.definition.url + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:23
 * @route '/hr/calendar/events'
 */
events.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: events.url(options),
    method: 'get',
})
/**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:23
 * @route '/hr/calendar/events'
 */
events.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: events.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:23
 * @route '/hr/calendar/events'
 */
    const eventsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: events.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:23
 * @route '/hr/calendar/events'
 */
        eventsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: events.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Calendar\Http\Controllers\HR\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/HR/CalendarController.php:23
 * @route '/hr/calendar/events'
 */
        eventsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: events.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    events.form = eventsForm
const CalendarController = { index, events }

export default CalendarController