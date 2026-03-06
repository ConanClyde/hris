import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:32
 * @route '/hr/onboarding'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/hr/onboarding',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:32
 * @route '/hr/onboarding'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:32
 * @route '/hr/onboarding'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:32
 * @route '/hr/onboarding'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:32
 * @route '/hr/onboarding'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:32
 * @route '/hr/onboarding'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:32
 * @route '/hr/onboarding'
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
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:65
 * @route '/hr/onboarding/{employeeId}/init'
 */
export const init = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: init.url(args, options),
    method: 'post',
})

init.definition = {
    methods: ["post"],
    url: '/hr/onboarding/{employeeId}/init',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::init
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:65
 * @route '/hr/onboarding/{employeeId}/init'
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
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:65
 * @route '/hr/onboarding/{employeeId}/init'
 */
init.post = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: init.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::init
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:65
 * @route '/hr/onboarding/{employeeId}/init'
 */
    const initForm = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: init.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::init
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:65
 * @route '/hr/onboarding/{employeeId}/init'
 */
        initForm.post = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: init.url(args, options),
            method: 'post',
        })
    
    init.form = initForm
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggle
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:81
 * @route '/hr/onboarding/toggle/{id}'
 */
export const toggle = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggle.url(args, options),
    method: 'post',
})

toggle.definition = {
    methods: ["post"],
    url: '/hr/onboarding/toggle/{id}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggle
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:81
 * @route '/hr/onboarding/toggle/{id}'
 */
toggle.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return toggle.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggle
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:81
 * @route '/hr/onboarding/toggle/{id}'
 */
toggle.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggle.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggle
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:81
 * @route '/hr/onboarding/toggle/{id}'
 */
    const toggleForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: toggle.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggle
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:81
 * @route '/hr/onboarding/toggle/{id}'
 */
        toggleForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: toggle.url(args, options),
            method: 'post',
        })
    
    toggle.form = toggleForm
const onboarding = {
    index: Object.assign(index, index),
init: Object.assign(init, init),
toggle: Object.assign(toggle, toggle),
}

export default onboarding