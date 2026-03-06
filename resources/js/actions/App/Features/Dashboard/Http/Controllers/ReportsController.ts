import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/admin/reports'
 */
const indexf32b5bd5940752871d5bf97794f0c32e = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexf32b5bd5940752871d5bf97794f0c32e.url(options),
    method: 'get',
})

indexf32b5bd5940752871d5bf97794f0c32e.definition = {
    methods: ["get","head"],
    url: '/admin/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/admin/reports'
 */
indexf32b5bd5940752871d5bf97794f0c32e.url = (options?: RouteQueryOptions) => {
    return indexf32b5bd5940752871d5bf97794f0c32e.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/admin/reports'
 */
indexf32b5bd5940752871d5bf97794f0c32e.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: indexf32b5bd5940752871d5bf97794f0c32e.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/admin/reports'
 */
indexf32b5bd5940752871d5bf97794f0c32e.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: indexf32b5bd5940752871d5bf97794f0c32e.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/admin/reports'
 */
    const indexf32b5bd5940752871d5bf97794f0c32eForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: indexf32b5bd5940752871d5bf97794f0c32e.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/admin/reports'
 */
        indexf32b5bd5940752871d5bf97794f0c32eForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexf32b5bd5940752871d5bf97794f0c32e.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/admin/reports'
 */
        indexf32b5bd5940752871d5bf97794f0c32eForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: indexf32b5bd5940752871d5bf97794f0c32e.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    indexf32b5bd5940752871d5bf97794f0c32e.form = indexf32b5bd5940752871d5bf97794f0c32eForm
    /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/hr/reports'
 */
const index0655b0808d255515b17e19c035419a6b = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index0655b0808d255515b17e19c035419a6b.url(options),
    method: 'get',
})

index0655b0808d255515b17e19c035419a6b.definition = {
    methods: ["get","head"],
    url: '/hr/reports',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/hr/reports'
 */
index0655b0808d255515b17e19c035419a6b.url = (options?: RouteQueryOptions) => {
    return index0655b0808d255515b17e19c035419a6b.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/hr/reports'
 */
index0655b0808d255515b17e19c035419a6b.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index0655b0808d255515b17e19c035419a6b.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/hr/reports'
 */
index0655b0808d255515b17e19c035419a6b.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index0655b0808d255515b17e19c035419a6b.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/hr/reports'
 */
    const index0655b0808d255515b17e19c035419a6bForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index0655b0808d255515b17e19c035419a6b.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/hr/reports'
 */
        index0655b0808d255515b17e19c035419a6bForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index0655b0808d255515b17e19c035419a6b.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::index
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:21
 * @route '/hr/reports'
 */
        index0655b0808d255515b17e19c035419a6bForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index0655b0808d255515b17e19c035419a6b.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    index0655b0808d255515b17e19c035419a6b.form = index0655b0808d255515b17e19c035419a6bForm

export const index = {
    '/admin/reports': indexf32b5bd5940752871d5bf97794f0c32e,
    '/hr/reports': index0655b0808d255515b17e19c035419a6b,
}

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::exportAdminAnalytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
export const exportAdminAnalytics = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportAdminAnalytics.url(options),
    method: 'get',
})

exportAdminAnalytics.definition = {
    methods: ["get","head"],
    url: '/admin/reports/export/analytics',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::exportAdminAnalytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
exportAdminAnalytics.url = (options?: RouteQueryOptions) => {
    return exportAdminAnalytics.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::exportAdminAnalytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
exportAdminAnalytics.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportAdminAnalytics.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::exportAdminAnalytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
exportAdminAnalytics.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportAdminAnalytics.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::exportAdminAnalytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
    const exportAdminAnalyticsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportAdminAnalytics.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::exportAdminAnalytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
        exportAdminAnalyticsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportAdminAnalytics.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\ReportsController::exportAdminAnalytics
 * @see app/Features/Dashboard/Http/Controllers/ReportsController.php:428
 * @route '/admin/reports/export/analytics'
 */
        exportAdminAnalyticsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportAdminAnalytics.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportAdminAnalytics.form = exportAdminAnalyticsForm
const ReportsController = { index, exportAdminAnalytics }

export default ReportsController