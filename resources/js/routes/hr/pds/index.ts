import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
import revisions from './revisions'
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
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::status
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
 * @route '/hr/pds/status'
 */
export const status = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: status.url(options),
    method: 'post',
})

status.definition = {
    methods: ["post"],
    url: '/hr/pds/status',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::status
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
 * @route '/hr/pds/status'
 */
status.url = (options?: RouteQueryOptions) => {
    return status.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::status
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
 * @route '/hr/pds/status'
 */
status.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: status.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::status
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
 * @route '/hr/pds/status'
 */
    const statusForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: status.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::status
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:211
 * @route '/hr/pds/status'
 */
        statusForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: status.url(options),
            method: 'post',
        })
    
    status.form = statusForm
/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
export const exportMethod = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(args, options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/hr/pds/{id}/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
exportMethod.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return exportMethod.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
exportMethod.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
exportMethod.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
    const exportMethodForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
        exportMethodForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
        exportMethodForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportMethod.form = exportMethodForm
const pds = {
    index: Object.assign(index, index),
preview: Object.assign(preview, preview),
previewJson: Object.assign(previewJson, previewJson),
status: Object.assign(status, status),
revisions: Object.assign(revisions, revisions),
export: Object.assign(exportMethod, exportMethod),
}

export default pds