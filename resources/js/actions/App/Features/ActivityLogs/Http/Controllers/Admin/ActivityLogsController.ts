import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:14
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
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:14
 * @route '/admin/activity-logs'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:14
 * @route '/admin/activity-logs'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:14
 * @route '/admin/activity-logs'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:14
 * @route '/admin/activity-logs'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:14
 * @route '/admin/activity-logs'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::index
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:14
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
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:133
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
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:133
 * @route '/admin/activity-logs/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:133
 * @route '/admin/activity-logs/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:133
 * @route '/admin/activity-logs/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:133
 * @route '/admin/activity-logs/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:133
 * @route '/admin/activity-logs/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::exportMethod
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:133
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
/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/hr/activity-logs'
 */
const userIndex30e4780a579e671f39fd773e8f08895a = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: userIndex30e4780a579e671f39fd773e8f08895a.url(options),
    method: 'get',
})

userIndex30e4780a579e671f39fd773e8f08895a.definition = {
    methods: ["get","head"],
    url: '/hr/activity-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/hr/activity-logs'
 */
userIndex30e4780a579e671f39fd773e8f08895a.url = (options?: RouteQueryOptions) => {
    return userIndex30e4780a579e671f39fd773e8f08895a.definition.url + queryParams(options)
}

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/hr/activity-logs'
 */
userIndex30e4780a579e671f39fd773e8f08895a.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: userIndex30e4780a579e671f39fd773e8f08895a.url(options),
    method: 'get',
})
/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/hr/activity-logs'
 */
userIndex30e4780a579e671f39fd773e8f08895a.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: userIndex30e4780a579e671f39fd773e8f08895a.url(options),
    method: 'head',
})

    /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/hr/activity-logs'
 */
    const userIndex30e4780a579e671f39fd773e8f08895aForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: userIndex30e4780a579e671f39fd773e8f08895a.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/hr/activity-logs'
 */
        userIndex30e4780a579e671f39fd773e8f08895aForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: userIndex30e4780a579e671f39fd773e8f08895a.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/hr/activity-logs'
 */
        userIndex30e4780a579e671f39fd773e8f08895aForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: userIndex30e4780a579e671f39fd773e8f08895a.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    userIndex30e4780a579e671f39fd773e8f08895a.form = userIndex30e4780a579e671f39fd773e8f08895aForm
    /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/employee/activity-logs'
 */
const userIndexe4ed724e8962859b51a30b765a7b8626 = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: userIndexe4ed724e8962859b51a30b765a7b8626.url(options),
    method: 'get',
})

userIndexe4ed724e8962859b51a30b765a7b8626.definition = {
    methods: ["get","head"],
    url: '/employee/activity-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/employee/activity-logs'
 */
userIndexe4ed724e8962859b51a30b765a7b8626.url = (options?: RouteQueryOptions) => {
    return userIndexe4ed724e8962859b51a30b765a7b8626.definition.url + queryParams(options)
}

/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/employee/activity-logs'
 */
userIndexe4ed724e8962859b51a30b765a7b8626.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: userIndexe4ed724e8962859b51a30b765a7b8626.url(options),
    method: 'get',
})
/**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/employee/activity-logs'
 */
userIndexe4ed724e8962859b51a30b765a7b8626.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: userIndexe4ed724e8962859b51a30b765a7b8626.url(options),
    method: 'head',
})

    /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/employee/activity-logs'
 */
    const userIndexe4ed724e8962859b51a30b765a7b8626Form = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: userIndexe4ed724e8962859b51a30b765a7b8626.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/employee/activity-logs'
 */
        userIndexe4ed724e8962859b51a30b765a7b8626Form.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: userIndexe4ed724e8962859b51a30b765a7b8626.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\ActivityLogs\Http\Controllers\Admin\ActivityLogsController::userIndex
 * @see app/Features/ActivityLogs/Http/Controllers/Admin/ActivityLogsController.php:70
 * @route '/employee/activity-logs'
 */
        userIndexe4ed724e8962859b51a30b765a7b8626Form.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: userIndexe4ed724e8962859b51a30b765a7b8626.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    userIndexe4ed724e8962859b51a30b765a7b8626.form = userIndexe4ed724e8962859b51a30b765a7b8626Form

export const userIndex = {
    '/hr/activity-logs': userIndex30e4780a579e671f39fd773e8f08895a,
    '/employee/activity-logs': userIndexe4ed724e8962859b51a30b765a7b8626,
}

const ActivityLogsController = { index, exportMethod, userIndex, export: exportMethod }

export default ActivityLogsController