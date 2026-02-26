import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:17
 * @route '/hr/pds'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/pds',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:17
 * @route '/hr/pds'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:17
 * @route '/hr/pds'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:17
 * @route '/hr/pds'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:17
 * @route '/hr/pds'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:17
 * @route '/hr/pds'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:17
 * @route '/hr/pds'
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
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:83
 * @route '/hr/pds/preview'
 */
export const preview = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(options),
    method: 'get',
})

preview.definition = {
    methods: ["get","head"],
    url: '/hr/pds/preview',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:83
 * @route '/hr/pds/preview'
 */
preview.url = (options?: RouteQueryOptions) => {
    return preview.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:83
 * @route '/hr/pds/preview'
 */
preview.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:83
 * @route '/hr/pds/preview'
 */
preview.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: preview.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:83
 * @route '/hr/pds/preview'
 */
    const previewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: preview.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:83
 * @route '/hr/pds/preview'
 */
        previewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: preview.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:83
 * @route '/hr/pds/preview'
 */
        previewForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: preview.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    preview.form = previewForm
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::updateStatus
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:92
 * @route '/hr/pds/status'
 */
export const updateStatus = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateStatus.url(options),
    method: 'post',
})

updateStatus.definition = {
    methods: ["post"],
    url: '/hr/pds/status',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::updateStatus
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:92
 * @route '/hr/pds/status'
 */
updateStatus.url = (options?: RouteQueryOptions) => {
    return updateStatus.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::updateStatus
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:92
 * @route '/hr/pds/status'
 */
updateStatus.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateStatus.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::updateStatus
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:92
 * @route '/hr/pds/status'
 */
    const updateStatusForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateStatus.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::updateStatus
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:92
 * @route '/hr/pds/status'
 */
        updateStatusForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateStatus.url(options),
            method: 'post',
        })
    
    updateStatus.form = updateStatusForm
const PdsController = { index, preview, updateStatus }

export default PdsController