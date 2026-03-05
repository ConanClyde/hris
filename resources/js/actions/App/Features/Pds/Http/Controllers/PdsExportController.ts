import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportHr
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
export const exportHr = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportHr.url(args, options),
    method: 'get',
})

exportHr.definition = {
    methods: ["get","head"],
    url: '/hr/pds/{id}/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportHr
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
exportHr.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return exportHr.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportHr
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
exportHr.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportHr.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportHr
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
exportHr.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportHr.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportHr
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
    const exportHrForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportHr.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportHr
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
        exportHrForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportHr.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportHr
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:49
 * @route '/hr/pds/{id}/export'
 */
        exportHrForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportHr.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportHr.form = exportHrForm
/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportEmployee
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
export const exportEmployee = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportEmployee.url(options),
    method: 'get',
})

exportEmployee.definition = {
    methods: ["get","head"],
    url: '/employee/pds/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportEmployee
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
exportEmployee.url = (options?: RouteQueryOptions) => {
    return exportEmployee.definition.url + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportEmployee
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
exportEmployee.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportEmployee.url(options),
    method: 'get',
})
/**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportEmployee
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
exportEmployee.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportEmployee.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportEmployee
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
    const exportEmployeeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportEmployee.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportEmployee
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
        exportEmployeeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportEmployee.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Pds\Http\Controllers\PdsExportController::exportEmployee
 * @see app/Features/Pds/Http/Controllers/PdsExportController.php:29
 * @route '/employee/pds/export'
 */
        exportEmployeeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportEmployee.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportEmployee.form = exportEmployeeForm
const PdsExportController = { exportHr, exportEmployee }

export default PdsExportController