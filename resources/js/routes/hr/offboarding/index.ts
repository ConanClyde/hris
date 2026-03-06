import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:107
 * @route '/hr/offboarding'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/offboarding',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:107
 * @route '/hr/offboarding'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:107
 * @route '/hr/offboarding'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:107
 * @route '/hr/offboarding'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:107
 * @route '/hr/offboarding'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:107
 * @route '/hr/offboarding'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:107
 * @route '/hr/offboarding'
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
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::init
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:145
 * @route '/hr/offboarding/{employeeId}/init'
 */
export const init = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: init.url(args, options),
    method: 'post',
})

init.definition = {
    methods: ["post"],
    url: '/hr/offboarding/{employeeId}/init',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::init
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:145
 * @route '/hr/offboarding/{employeeId}/init'
 */
init.url = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { employeeId: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    employeeId: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        employeeId: args.employeeId,
                }

    return init.definition.url
            .replace('{employeeId}', parsedArgs.employeeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::init
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:145
 * @route '/hr/offboarding/{employeeId}/init'
 */
init.post = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: init.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::init
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:145
 * @route '/hr/offboarding/{employeeId}/init'
 */
    const initForm = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: init.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::init
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:145
 * @route '/hr/offboarding/{employeeId}/init'
 */
        initForm.post = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: init.url(args, options),
            method: 'post',
        })
    
    init.form = initForm
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::update
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:160
 * @route '/hr/offboarding/clearance/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

update.definition = {
    methods: ["post"],
    url: '/hr/offboarding/clearance/{id}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::update
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:160
 * @route '/hr/offboarding/clearance/{id}'
 */
update.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return update.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::update
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:160
 * @route '/hr/offboarding/clearance/{id}'
 */
update.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: update.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::update
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:160
 * @route '/hr/offboarding/clearance/{id}'
 */
    const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::update
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:160
 * @route '/hr/offboarding/clearance/{id}'
 */
        updateForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, options),
            method: 'post',
        })
    
    update.form = updateForm
const offboarding = {
    index: Object.assign(index, index),
init: Object.assign(init, init),
update: Object.assign(update, update),
}

export default offboarding