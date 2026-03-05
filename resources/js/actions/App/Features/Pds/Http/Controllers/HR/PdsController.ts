import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:33
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
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:33
 * @route '/hr/pds'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:33
 * @route '/hr/pds'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:33
 * @route '/hr/pds'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:33
 * @route '/hr/pds'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:33
 * @route '/hr/pds'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::index
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:33
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
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:181
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
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:181
 * @route '/hr/pds/preview'
 */
preview.url = (options?: RouteQueryOptions) => {
    return preview.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:181
 * @route '/hr/pds/preview'
 */
preview.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:181
 * @route '/hr/pds/preview'
 */
preview.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: preview.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:181
 * @route '/hr/pds/preview'
 */
    const previewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: preview.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:181
 * @route '/hr/pds/preview'
 */
        previewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: preview.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:181
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
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::previewJson
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:119
 * @route '/hr/pds/preview-json'
 */
export const previewJson = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: previewJson.url(options),
    method: 'get',
})

previewJson.definition = {
    methods: ["get","head"],
    url: '/hr/pds/preview-json',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::previewJson
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:119
 * @route '/hr/pds/preview-json'
 */
previewJson.url = (options?: RouteQueryOptions) => {
    return previewJson.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::previewJson
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:119
 * @route '/hr/pds/preview-json'
 */
previewJson.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: previewJson.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::previewJson
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:119
 * @route '/hr/pds/preview-json'
 */
previewJson.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: previewJson.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::previewJson
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:119
 * @route '/hr/pds/preview-json'
 */
    const previewJsonForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: previewJson.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::previewJson
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:119
 * @route '/hr/pds/preview-json'
 */
        previewJsonForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: previewJson.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::previewJson
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:119
 * @route '/hr/pds/preview-json'
 */
        previewJsonForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: previewJson.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    previewJson.form = previewJsonForm
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::updateStatus
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
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
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
 * @route '/hr/pds/status'
 */
updateStatus.url = (options?: RouteQueryOptions) => {
    return updateStatus.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::updateStatus
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
 * @route '/hr/pds/status'
 */
updateStatus.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateStatus.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::updateStatus
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
 * @route '/hr/pds/status'
 */
    const updateStatusForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateStatus.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::updateStatus
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
 * @route '/hr/pds/status'
 */
        updateStatusForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateStatus.url(options),
            method: 'post',
        })
    
    updateStatus.form = updateStatusForm
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approveRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:271
 * @route '/hr/pds/revisions/{id}/approve'
 */
export const approveRevision = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approveRevision.url(args, options),
    method: 'post',
})

approveRevision.definition = {
    methods: ["post"],
    url: '/hr/pds/revisions/{id}/approve',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approveRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:271
 * @route '/hr/pds/revisions/{id}/approve'
 */
approveRevision.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return approveRevision.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approveRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:271
 * @route '/hr/pds/revisions/{id}/approve'
 */
approveRevision.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approveRevision.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approveRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:271
 * @route '/hr/pds/revisions/{id}/approve'
 */
    const approveRevisionForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: approveRevision.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approveRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:271
 * @route '/hr/pds/revisions/{id}/approve'
 */
        approveRevisionForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: approveRevision.url(args, options),
            method: 'post',
        })
    
    approveRevision.form = approveRevisionForm
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::rejectRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:478
 * @route '/hr/pds/revisions/{id}/reject'
 */
export const rejectRevision = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: rejectRevision.url(args, options),
    method: 'post',
})

rejectRevision.definition = {
    methods: ["post"],
    url: '/hr/pds/revisions/{id}/reject',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::rejectRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:478
 * @route '/hr/pds/revisions/{id}/reject'
 */
rejectRevision.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return rejectRevision.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::rejectRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:478
 * @route '/hr/pds/revisions/{id}/reject'
 */
rejectRevision.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: rejectRevision.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::rejectRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:478
 * @route '/hr/pds/revisions/{id}/reject'
 */
    const rejectRevisionForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: rejectRevision.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::rejectRevision
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:478
 * @route '/hr/pds/revisions/{id}/reject'
 */
        rejectRevisionForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: rejectRevision.url(args, options),
            method: 'post',
        })
    
    rejectRevision.form = rejectRevisionForm
const PdsController = { index, preview, previewJson, updateStatus, approveRevision, rejectRevision }

export default PdsController