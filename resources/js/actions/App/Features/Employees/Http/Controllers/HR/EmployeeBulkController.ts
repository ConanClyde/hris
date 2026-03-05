import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::downloadTemplate
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:23
 * @route '/hr/employees/template'
 */
export const downloadTemplate = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})

downloadTemplate.definition = {
    methods: ["get","head"],
    url: '/hr/employees/template',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::downloadTemplate
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:23
 * @route '/hr/employees/template'
 */
downloadTemplate.url = (options?: RouteQueryOptions) => {
    return downloadTemplate.definition.url + queryParams(options)
}

/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::downloadTemplate
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:23
 * @route '/hr/employees/template'
 */
downloadTemplate.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: downloadTemplate.url(options),
    method: 'get',
})
/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::downloadTemplate
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:23
 * @route '/hr/employees/template'
 */
downloadTemplate.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: downloadTemplate.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::downloadTemplate
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:23
 * @route '/hr/employees/template'
 */
    const downloadTemplateForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: downloadTemplate.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::downloadTemplate
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:23
 * @route '/hr/employees/template'
 */
        downloadTemplateForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadTemplate.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::downloadTemplate
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:23
 * @route '/hr/employees/template'
 */
        downloadTemplateForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: downloadTemplate.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    downloadTemplate.form = downloadTemplateForm
/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::importMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:39
 * @route '/hr/employees/import'
 */
export const importMethod = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

importMethod.definition = {
    methods: ["post"],
    url: '/hr/employees/import',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::importMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:39
 * @route '/hr/employees/import'
 */
importMethod.url = (options?: RouteQueryOptions) => {
    return importMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::importMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:39
 * @route '/hr/employees/import'
 */
importMethod.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: importMethod.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::importMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:39
 * @route '/hr/employees/import'
 */
    const importMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: importMethod.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::importMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:39
 * @route '/hr/employees/import'
 */
        importMethodForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: importMethod.url(options),
            method: 'post',
        })
    
    importMethod.form = importMethodForm
/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::exportMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:83
 * @route '/hr/employees/export'
 */
export const exportMethod = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})

exportMethod.definition = {
    methods: ["get","head"],
    url: '/hr/employees/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::exportMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:83
 * @route '/hr/employees/export'
 */
exportMethod.url = (options?: RouteQueryOptions) => {
    return exportMethod.definition.url + queryParams(options)
}

/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::exportMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:83
 * @route '/hr/employees/export'
 */
exportMethod.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMethod.url(options),
    method: 'get',
})
/**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::exportMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:83
 * @route '/hr/employees/export'
 */
exportMethod.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMethod.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::exportMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:83
 * @route '/hr/employees/export'
 */
    const exportMethodForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMethod.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::exportMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:83
 * @route '/hr/employees/export'
 */
        exportMethodForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMethod.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Employees\Http\Controllers\HR\EmployeeBulkController::exportMethod
 * @see app/Features/Employees/Http/Controllers/HR/EmployeeBulkController.php:83
 * @route '/hr/employees/export'
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
const EmployeeBulkController = { downloadTemplate, importMethod, exportMethod, import: importMethod, export: exportMethod }

export default EmployeeBulkController