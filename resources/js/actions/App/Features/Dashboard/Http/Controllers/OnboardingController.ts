import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:33
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
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:33
 * @route '/hr/onboarding'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:33
 * @route '/hr/onboarding'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:33
 * @route '/hr/onboarding'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:33
 * @route '/hr/onboarding'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:33
 * @route '/hr/onboarding'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::index
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:33
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
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initChecklist
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:66
 * @route '/hr/onboarding/{employeeId}/init'
 */
export const initChecklist = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initChecklist.url(args, options),
    method: 'post',
})

initChecklist.definition = {
    methods: ["post"],
    url: '/hr/onboarding/{employeeId}/init',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initChecklist
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:66
 * @route '/hr/onboarding/{employeeId}/init'
 */
initChecklist.url = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return initChecklist.definition.url
            .replace('{employeeId}', parsedArgs.employeeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initChecklist
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:66
 * @route '/hr/onboarding/{employeeId}/init'
 */
initChecklist.post = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initChecklist.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initChecklist
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:66
 * @route '/hr/onboarding/{employeeId}/init'
 */
    const initChecklistForm = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: initChecklist.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initChecklist
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:66
 * @route '/hr/onboarding/{employeeId}/init'
 */
        initChecklistForm.post = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: initChecklist.url(args, options),
            method: 'post',
        })
    
    initChecklist.form = initChecklistForm
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggleItem
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:82
 * @route '/hr/onboarding/toggle/{id}'
 */
export const toggleItem = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleItem.url(args, options),
    method: 'post',
})

toggleItem.definition = {
    methods: ["post"],
    url: '/hr/onboarding/toggle/{id}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggleItem
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:82
 * @route '/hr/onboarding/toggle/{id}'
 */
toggleItem.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return toggleItem.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggleItem
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:82
 * @route '/hr/onboarding/toggle/{id}'
 */
toggleItem.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: toggleItem.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggleItem
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:82
 * @route '/hr/onboarding/toggle/{id}'
 */
    const toggleItemForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: toggleItem.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::toggleItem
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:82
 * @route '/hr/onboarding/toggle/{id}'
 */
        toggleItemForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: toggleItem.url(args, options),
            method: 'post',
        })
    
    toggleItem.form = toggleItemForm
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::offboardingIndex
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:108
 * @route '/hr/offboarding'
 */
export const offboardingIndex = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: offboardingIndex.url(options),
    method: 'get',
})

offboardingIndex.definition = {
    methods: ["get","head"],
    url: '/hr/offboarding',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::offboardingIndex
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:108
 * @route '/hr/offboarding'
 */
offboardingIndex.url = (options?: RouteQueryOptions) => {
    return offboardingIndex.definition.url + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::offboardingIndex
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:108
 * @route '/hr/offboarding'
 */
offboardingIndex.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: offboardingIndex.url(options),
    method: 'get',
})
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::offboardingIndex
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:108
 * @route '/hr/offboarding'
 */
offboardingIndex.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: offboardingIndex.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::offboardingIndex
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:108
 * @route '/hr/offboarding'
 */
    const offboardingIndexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: offboardingIndex.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::offboardingIndex
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:108
 * @route '/hr/offboarding'
 */
        offboardingIndexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: offboardingIndex.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::offboardingIndex
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:108
 * @route '/hr/offboarding'
 */
        offboardingIndexForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: offboardingIndex.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    offboardingIndex.form = offboardingIndexForm
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:144
 * @route '/hr/offboarding/{employeeId}/init'
 */
export const initClearance = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initClearance.url(args, options),
    method: 'post',
})

initClearance.definition = {
    methods: ["post"],
    url: '/hr/offboarding/{employeeId}/init',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:144
 * @route '/hr/offboarding/{employeeId}/init'
 */
initClearance.url = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return initClearance.definition.url
            .replace('{employeeId}', parsedArgs.employeeId.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:144
 * @route '/hr/offboarding/{employeeId}/init'
 */
initClearance.post = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: initClearance.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:144
 * @route '/hr/offboarding/{employeeId}/init'
 */
    const initClearanceForm = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: initClearance.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::initClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:144
 * @route '/hr/offboarding/{employeeId}/init'
 */
        initClearanceForm.post = (args: { employeeId: string | number } | [employeeId: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: initClearance.url(args, options),
            method: 'post',
        })
    
    initClearance.form = initClearanceForm
/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::updateClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:159
 * @route '/hr/offboarding/clearance/{id}'
 */
export const updateClearance = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateClearance.url(args, options),
    method: 'post',
})

updateClearance.definition = {
    methods: ["post"],
    url: '/hr/offboarding/clearance/{id}',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::updateClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:159
 * @route '/hr/offboarding/clearance/{id}'
 */
updateClearance.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return updateClearance.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::updateClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:159
 * @route '/hr/offboarding/clearance/{id}'
 */
updateClearance.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: updateClearance.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::updateClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:159
 * @route '/hr/offboarding/clearance/{id}'
 */
    const updateClearanceForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: updateClearance.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Dashboard\Http\Controllers\OnboardingController::updateClearance
 * @see app/Features/Dashboard/Http/Controllers/OnboardingController.php:159
 * @route '/hr/offboarding/clearance/{id}'
 */
        updateClearanceForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: updateClearance.url(args, options),
            method: 'post',
        })
    
    updateClearance.form = updateClearanceForm
const OnboardingController = { index, initChecklist, toggleItem, offboardingIndex, initClearance, updateClearance }

export default OnboardingController