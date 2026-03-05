import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parsePdf
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:380
 * @route '/employee/pds/parse'
 */
export const parsePdf = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: parsePdf.url(options),
    method: 'post',
})

parsePdf.definition = {
    methods: ["post"],
    url: '/employee/pds/parse',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parsePdf
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:380
 * @route '/employee/pds/parse'
 */
parsePdf.url = (options?: RouteQueryOptions) => {
    return parsePdf.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parsePdf
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:380
 * @route '/employee/pds/parse'
 */
parsePdf.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: parsePdf.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parsePdf
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:380
 * @route '/employee/pds/parse'
 */
    const parsePdfForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: parsePdf.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::parsePdf
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:380
 * @route '/employee/pds/parse'
 */
        parsePdfForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: parsePdf.url(options),
            method: 'post',
        })
    
    parsePdf.form = parsePdfForm
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:37
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
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:37
 * @route '/employee/pds'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:37
 * @route '/employee/pds'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:37
 * @route '/employee/pds'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:37
 * @route '/employee/pds'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:37
 * @route '/employee/pds'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::index
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:37
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
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:107
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
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:107
 * @route '/employee/pds'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::store
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:107
 * @route '/employee/pds'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::store
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:107
 * @route '/employee/pds'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::store
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:107
 * @route '/employee/pds'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:351
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
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:351
 * @route '/employee/pds/preview'
 */
preview.url = (options?: RouteQueryOptions) => {
    return preview.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:351
 * @route '/employee/pds/preview'
 */
preview.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: preview.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:351
 * @route '/employee/pds/preview'
 */
preview.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: preview.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:351
 * @route '/employee/pds/preview'
 */
    const previewForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: preview.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:351
 * @route '/employee/pds/preview'
 */
        previewForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: preview.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\Employee\PdsController::preview
 * @see app/Features/Pds/Http/Controllers/Employee/PdsController.php:351
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
const PdsController = { parsePdf, index, store, preview }

export default PdsController