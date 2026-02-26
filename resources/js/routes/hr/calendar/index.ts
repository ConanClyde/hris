import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
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
const calendar = {
    events: Object.assign(events, events),
}

export default calendar