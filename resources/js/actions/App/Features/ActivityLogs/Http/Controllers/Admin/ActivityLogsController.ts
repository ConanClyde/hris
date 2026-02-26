import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:12
 * @route '/admin/activity-logs'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/activity-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:12
 * @route '/admin/activity-logs'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:12
 * @route '/admin/activity-logs'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:12
 * @route '/admin/activity-logs'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:12
 * @route '/admin/activity-logs'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:12
 * @route '/admin/activity-logs'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:12
 * @route '/admin/activity-logs'
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
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:32
 * @route '/admin/activity-logs/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/admin/activity-logs/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:32
 * @route '/admin/activity-logs/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:32
 * @route '/admin/activity-logs/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:32
 * @route '/admin/activity-logs/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:32
 * @route '/admin/activity-logs/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:32
 * @route '/admin/activity-logs/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:32
 * @route '/admin/activity-logs/export'
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
const ActivityLogsController = { index, exportMethod, export: exportMethod }

export default ActivityLogsController