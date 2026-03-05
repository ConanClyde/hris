import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Notices\Http\Controllers\Api\NoticeApiController::active
 * @see app/Features/Notices/Http/Controllers/Api/NoticeApiController.php:10
 * @route '/api/v1/notices/active'
 */
export const active = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: active.url(options),
    method: 'get',
})

active.definition = {
    methods: ["get","head"],
    url: '/api/v1/notices/active',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Notices\Http\Controllers\Api\NoticeApiController::active
 * @see app/Features/Notices/Http/Controllers/Api/NoticeApiController.php:10
 * @route '/api/v1/notices/active'
 */
active.url = (options?: RouteQueryOptions) => {
    return active.definition.url + queryParams(options)
}

/**
* @see \App\Features\Notices\Http\Controllers\Api\NoticeApiController::active
 * @see app/Features/Notices/Http/Controllers/Api/NoticeApiController.php:10
 * @route '/api/v1/notices/active'
 */
active.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: active.url(options),
    method: 'get',
})
/**
* @see \App\Features\Notices\Http\Controllers\Api\NoticeApiController::active
 * @see app/Features/Notices/Http/Controllers/Api/NoticeApiController.php:10
 * @route '/api/v1/notices/active'
 */
active.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: active.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Notices\Http\Controllers\Api\NoticeApiController::active
 * @see app/Features/Notices/Http/Controllers/Api/NoticeApiController.php:10
 * @route '/api/v1/notices/active'
 */
    const activeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: active.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Notices\Http\Controllers\Api\NoticeApiController::active
 * @see app/Features/Notices/Http/Controllers/Api/NoticeApiController.php:10
 * @route '/api/v1/notices/active'
 */
        activeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: active.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Notices\Http\Controllers\Api\NoticeApiController::active
 * @see app/Features/Notices/Http/Controllers/Api/NoticeApiController.php:10
 * @route '/api/v1/notices/active'
 */
        activeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: active.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    active.form = activeForm
const NoticeApiController = { active }

export default NoticeApiController