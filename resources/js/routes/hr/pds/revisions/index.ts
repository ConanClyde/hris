import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../wayfinder'
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approve
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:292
 * @route '/hr/pds/revisions/{id}/approve'
 */
export const approve = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

approve.definition = {
    methods: ["post"],
    url: '/hr/pds/revisions/{id}/approve',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approve
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:292
 * @route '/hr/pds/revisions/{id}/approve'
 */
approve.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return approve.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approve
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:292
 * @route '/hr/pds/revisions/{id}/approve'
 */
approve.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: approve.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approve
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:292
 * @route '/hr/pds/revisions/{id}/approve'
 */
    const approveForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: approve.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::approve
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:292
 * @route '/hr/pds/revisions/{id}/approve'
 */
        approveForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: approve.url(args, options),
            method: 'post',
        })
    
    approve.form = approveForm
/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::reject
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:509
 * @route '/hr/pds/revisions/{id}/reject'
 */
export const reject = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reject.url(args, options),
    method: 'post',
})

reject.definition = {
    methods: ["post"],
    url: '/hr/pds/revisions/{id}/reject',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::reject
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:509
 * @route '/hr/pds/revisions/{id}/reject'
 */
reject.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return reject.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::reject
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:509
 * @route '/hr/pds/revisions/{id}/reject'
 */
reject.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: reject.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::reject
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:509
 * @route '/hr/pds/revisions/{id}/reject'
 */
    const rejectForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: reject.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Pds\Http\Controllers\HR\PdsController::reject
 * @see app/Features/Pds/Http/Controllers/HR/PdsController.php:509
 * @route '/hr/pds/revisions/{id}/reject'
 */
        rejectForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: reject.url(args, options),
            method: 'post',
        })
    
    reject.form = rejectForm
const revisions = {
    approve: Object.assign(approve, approve),
reject: Object.assign(reject, reject),
}

export default revisions