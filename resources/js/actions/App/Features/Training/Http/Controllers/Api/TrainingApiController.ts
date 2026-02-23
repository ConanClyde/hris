import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::index
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:14
 * @route '/api/v1/trainings'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/v1/trainings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::index
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:14
 * @route '/api/v1/trainings'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::index
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:14
 * @route '/api/v1/trainings'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::index
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:14
 * @route '/api/v1/trainings'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::index
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:14
 * @route '/api/v1/trainings'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::index
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:14
 * @route '/api/v1/trainings'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::index
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:14
 * @route '/api/v1/trainings'
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
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::updateStatus
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:45
 * @route '/api/v1/trainings/{id}/status'
 */
export const updateStatus = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updateStatus.url(args, options),
    method: 'put',
})

updateStatus.definition = {
    methods: ["put"],
    url: '/api/v1/trainings/{id}/status',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::updateStatus
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:45
 * @route '/api/v1/trainings/{id}/status'
 */
updateStatus.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { id: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    id: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        id: args.id,
                }

    return updateStatus.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::updateStatus
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:45
 * @route '/api/v1/trainings/{id}/status'
 */
updateStatus.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: updateStatus.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::updateStatus
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:45
 * @route '/api/v1/trainings/{id}/status'
 */
    const updateStatusForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateStatus.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\Training\Http\Controllers\Api\TrainingApiController::updateStatus
 * @see app/Features/Training/Http/Controllers/Api/TrainingApiController.php:45
 * @route '/api/v1/trainings/{id}/status'
 */
        updateStatusForm.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateStatus.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    updateStatus.form = updateStatusForm
const TrainingApiController = { index, updateStatus }

export default TrainingApiController