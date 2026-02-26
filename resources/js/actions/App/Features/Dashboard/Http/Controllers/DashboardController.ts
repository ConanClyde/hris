import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::admin
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
export const admin = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: admin.url(options),
    method: 'get',
})

admin.definition = {
    methods: ["get","head"],
    url: '/admin/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::admin
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
admin.url = (options?: RouteQueryOptions) => {
    return admin.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::admin
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
admin.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: admin.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::admin
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
admin.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: admin.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::admin
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
    const adminForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: admin.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::admin
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
        adminForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: admin.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::admin
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:15
 * @route '/admin/dashboard'
 */
        adminForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: admin.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    admin.form = adminForm
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::hr
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
export const hr = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: hr.url(options),
    method: 'get',
})

hr.definition = {
    methods: ["get","head"],
    url: '/hr/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::hr
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
hr.url = (options?: RouteQueryOptions) => {
    return hr.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::hr
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
hr.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: hr.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::hr
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
hr.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: hr.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::hr
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
    const hrForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: hr.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::hr
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
        hrForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: hr.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::hr
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:51
 * @route '/hr/dashboard'
 */
        hrForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: hr.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    hr.form = hrForm
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::employee
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:82
 * @route '/employee/dashboard'
 */
export const employee = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: employee.url(options),
    method: 'get',
})

employee.definition = {
    methods: ["get","head"],
    url: '/employee/dashboard',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::employee
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:82
 * @route '/employee/dashboard'
 */
employee.url = (options?: RouteQueryOptions) => {
    return employee.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::employee
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:82
 * @route '/employee/dashboard'
 */
employee.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: employee.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::employee
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:82
 * @route '/employee/dashboard'
 */
employee.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: employee.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::employee
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:82
 * @route '/employee/dashboard'
 */
    const employeeForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: employee.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::employee
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:82
 * @route '/employee/dashboard'
 */
        employeeForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: employee.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\DashboardController::employee
 * @see app/Features/Dashboard/Http/Controllers/DashboardController.php:82
 * @route '/employee/dashboard'
 */
        employeeForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: employee.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    employee.form = employeeForm
const DashboardController = { admin, hr, employee }

export default DashboardController