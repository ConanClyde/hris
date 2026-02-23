import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/calendar',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::index
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:17
 * @route '/admin/calendar'
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
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:26
 * @route '/admin/calendar/events'
 */
export const events = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: events.url(options),
    method: 'get',
})

events.definition = {
    methods: ["get","head"],
    url: '/admin/calendar/events',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:26
 * @route '/admin/calendar/events'
 */
events.url = (options?: RouteQueryOptions) => {
    return events.definition.url + queryParams(options)
}

/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:26
 * @route '/admin/calendar/events'
 */
events.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: events.url(options),
    method: 'get',
})
/**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:26
 * @route '/admin/calendar/events'
 */
events.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: events.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:26
 * @route '/admin/calendar/events'
 */
    const eventsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: events.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:26
 * @route '/admin/calendar/events'
 */
        eventsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: events.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Calendar\Http\Controllers\Admin\CalendarController::events
 * @see app/Features/Calendar/Http/Controllers/Admin/CalendarController.php:26
 * @route '/admin/calendar/events'
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