import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parse
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:419
 * @route '/employee/pds/parse'
 */
export const parse = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: parse.url(options),
    method: 'post',
})

parse.definition = {
    methods: ["post"],
    url: '/employee/pds/parse',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parse
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:419
 * @route '/employee/pds/parse'
 */
parse.url = (options?: RouteQueryOptions) => {
    return parse.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parse
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:419
 * @route '/employee/pds/parse'
 */
parse.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: parse.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parse
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:419
 * @route '/employee/pds/parse'
 */
    const parseForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: parse.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parse
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:419
 * @route '/employee/pds/parse'
 */
        parseForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: parse.url(options),
            method: 'post',
        })
    
    parse.form = parseForm
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:41
 * @route '/employee/pds'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/employee/pds',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:41
 * @route '/employee/pds'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:41
 * @route '/employee/pds'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:41
 * @route '/employee/pds'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:41
 * @route '/employee/pds'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:41
 * @route '/employee/pds'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:41
 * @route '/employee/pds'
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
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::store
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:111
 * @route '/employee/pds'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/employee/pds',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::store
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:111
 * @route '/employee/pds'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::store
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:111
 * @route '/employee/pds'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::store
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:111
 * @route '/employee/pds'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::store
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:111
 * @route '/employee/pds'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:390
 * @route '/employee/pds/preview'
 */
export const preview = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(options),
    method: 'get',
})

preview.definition = {
    methods: ["get","head"],
    url: '/employee/pds/preview',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:390
 * @route '/employee/pds/preview'
 */
preview.url = (options?: RouteQueryOptions) => {
    return preview.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:390
 * @route '/employee/pds/preview'
 */
preview.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:390
 * @route '/employee/pds/preview'
 */
preview.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: preview.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:390
 * @route '/employee/pds/preview'
 */
    const previewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: preview.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:390
 * @route '/employee/pds/preview'
 */
        previewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: preview.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:390
 * @route '/employee/pds/preview'
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
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/employee/pds/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportMethod
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
        exportMethodForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportMethod.form = exportMethodForm
const pds = {
    parse: Object.assign(parse, parse),
index: Object.assign(index, index),
store: Object.assign(store, store),
preview: Object.assign(preview, preview),
export: Object.assign(exportMethod, exportMethod),
}

export default pds