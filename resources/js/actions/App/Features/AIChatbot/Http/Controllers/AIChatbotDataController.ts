import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:41
 * @route '/api/ai/context'
 */
export const context = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: context.url(options),
    method: 'get',
})

context.definition = {
    methods: ["get","head"],
    url: '/api/ai/context',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:41
 * @route '/api/ai/context'
 */
context.url = (options?: RouteQueryOptions) => {
    return context.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:41
 * @route '/api/ai/context'
 */
context.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: context.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:41
 * @route '/api/ai/context'
 */
context.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: context.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:41
 * @route '/api/ai/context'
 */
    const contextForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: context.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:41
 * @route '/api/ai/context'
 */
        contextForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: context.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::context
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:41
 * @route '/api/ai/context'
 */
        contextForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: context.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    context.form = contextForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::users
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:155
 * @route '/api/ai/users'
 */
export const users = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(options),
    method: 'get',
})

users.definition = {
    methods: ["get","head"],
    url: '/api/ai/users',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::users
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:155
 * @route '/api/ai/users'
 */
users.url = (options?: RouteQueryOptions) => {
    return users.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::users
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:155
 * @route '/api/ai/users'
 */
users.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: users.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::users
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:155
 * @route '/api/ai/users'
 */
users.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: users.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::users
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:155
 * @route '/api/ai/users'
 */
    const usersForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: users.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::users
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:155
 * @route '/api/ai/users'
 */
        usersForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: users.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::users
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:155
 * @route '/api/ai/users'
 */
        usersForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: users.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    users.form = usersForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::leaveApplications
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:188
 * @route '/api/ai/leave-applications'
 */
export const leaveApplications = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leaveApplications.url(options),
    method: 'get',
})

leaveApplications.definition = {
    methods: ["get","head"],
    url: '/api/ai/leave-applications',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::leaveApplications
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:188
 * @route '/api/ai/leave-applications'
 */
leaveApplications.url = (options?: RouteQueryOptions) => {
    return leaveApplications.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::leaveApplications
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:188
 * @route '/api/ai/leave-applications'
 */
leaveApplications.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: leaveApplications.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::leaveApplications
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:188
 * @route '/api/ai/leave-applications'
 */
leaveApplications.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: leaveApplications.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::leaveApplications
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:188
 * @route '/api/ai/leave-applications'
 */
    const leaveApplicationsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: leaveApplications.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::leaveApplications
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:188
 * @route '/api/ai/leave-applications'
 */
        leaveApplicationsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leaveApplications.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::leaveApplications
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:188
 * @route '/api/ai/leave-applications'
 */
        leaveApplicationsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: leaveApplications.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    leaveApplications.form = leaveApplicationsForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::trainings
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:222
 * @route '/api/ai/trainings'
 */
export const trainings = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trainings.url(options),
    method: 'get',
})

trainings.definition = {
    methods: ["get","head"],
    url: '/api/ai/trainings',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::trainings
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:222
 * @route '/api/ai/trainings'
 */
trainings.url = (options?: RouteQueryOptions) => {
    return trainings.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::trainings
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:222
 * @route '/api/ai/trainings'
 */
trainings.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: trainings.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::trainings
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:222
 * @route '/api/ai/trainings'
 */
trainings.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: trainings.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::trainings
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:222
 * @route '/api/ai/trainings'
 */
    const trainingsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: trainings.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::trainings
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:222
 * @route '/api/ai/trainings'
 */
        trainingsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: trainings.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::trainings
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:222
 * @route '/api/ai/trainings'
 */
        trainingsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: trainings.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    trainings.form = trainingsForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::notices
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:256
 * @route '/api/ai/notices'
 */
export const notices = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notices.url(options),
    method: 'get',
})

notices.definition = {
    methods: ["get","head"],
    url: '/api/ai/notices',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::notices
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:256
 * @route '/api/ai/notices'
 */
notices.url = (options?: RouteQueryOptions) => {
    return notices.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::notices
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:256
 * @route '/api/ai/notices'
 */
notices.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: notices.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::notices
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:256
 * @route '/api/ai/notices'
 */
notices.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: notices.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::notices
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:256
 * @route '/api/ai/notices'
 */
    const noticesForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: notices.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::notices
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:256
 * @route '/api/ai/notices'
 */
        noticesForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notices.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::notices
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:256
 * @route '/api/ai/notices'
 */
        noticesForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: notices.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    notices.form = noticesForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::health
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:281
 * @route '/api/ai/health'
 */
export const health = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: health.url(options),
    method: 'get',
})

health.definition = {
    methods: ["get","head"],
    url: '/api/ai/health',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::health
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:281
 * @route '/api/ai/health'
 */
health.url = (options?: RouteQueryOptions) => {
    return health.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::health
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:281
 * @route '/api/ai/health'
 */
health.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: health.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::health
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:281
 * @route '/api/ai/health'
 */
health.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: health.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::health
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:281
 * @route '/api/ai/health'
 */
    const healthForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: health.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::health
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:281
 * @route '/api/ai/health'
 */
        healthForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: health.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::health
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:281
 * @route '/api/ai/health'
 */
        healthForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: health.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    health.form = healthForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestions
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:825
 * @route '/api/ai/suggestions'
 */
export const suggestions = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: suggestions.url(options),
    method: 'get',
})

suggestions.definition = {
    methods: ["get","head"],
    url: '/api/ai/suggestions',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestions
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:825
 * @route '/api/ai/suggestions'
 */
suggestions.url = (options?: RouteQueryOptions) => {
    return suggestions.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestions
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:825
 * @route '/api/ai/suggestions'
 */
suggestions.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: suggestions.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestions
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:825
 * @route '/api/ai/suggestions'
 */
suggestions.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: suggestions.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestions
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:825
 * @route '/api/ai/suggestions'
 */
    const suggestionsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: suggestions.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestions
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:825
 * @route '/api/ai/suggestions'
 */
        suggestionsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: suggestions.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestions
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:825
 * @route '/api/ai/suggestions'
 */
        suggestionsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: suggestions.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    suggestions.form = suggestionsForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestionAnswer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
export const suggestionAnswer = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: suggestionAnswer.url(options),
    method: 'post',
})

suggestionAnswer.definition = {
    methods: ["post"],
    url: '/api/ai/suggestions/answer',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestionAnswer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
suggestionAnswer.url = (options?: RouteQueryOptions) => {
    return suggestionAnswer.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestionAnswer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
suggestionAnswer.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: suggestionAnswer.url(options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestionAnswer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
    const suggestionAnswerForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: suggestionAnswer.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::suggestionAnswer
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:845
 * @route '/api/ai/suggestions/answer'
 */
        suggestionAnswerForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: suggestionAnswer.url(options),
            method: 'post',
        })
    
    suggestionAnswer.form = suggestionAnswerForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:454
 * @route '/api/ai/feedback'
 */
export const feedback = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: feedback.url(options),
    method: 'post',
})

feedback.definition = {
    methods: ["post"],
    url: '/api/ai/feedback',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:454
 * @route '/api/ai/feedback'
 */
feedback.url = (options?: RouteQueryOptions) => {
    return feedback.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:454
 * @route '/api/ai/feedback'
 */
feedback.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: feedback.url(options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:454
 * @route '/api/ai/feedback'
 */
    const feedbackForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: feedback.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:454
 * @route '/api/ai/feedback'
 */
        feedbackForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: feedback.url(options),
            method: 'post',
        })
    
    feedback.form = feedbackForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalInsights
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
export const personalInsights = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: personalInsights.url(options),
    method: 'get',
})

personalInsights.definition = {
    methods: ["get","head"],
    url: '/api/ai/insights/personal',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalInsights
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
personalInsights.url = (options?: RouteQueryOptions) => {
    return personalInsights.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalInsights
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
personalInsights.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: personalInsights.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalInsights
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
personalInsights.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: personalInsights.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalInsights
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
    const personalInsightsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: personalInsights.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalInsights
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
        personalInsightsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: personalInsights.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalInsights
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:954
 * @route '/api/ai/insights/personal'
 */
        personalInsightsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: personalInsights.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    personalInsights.form = personalInsightsForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
export const personalFeedback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: personalFeedback.url(options),
    method: 'get',
})

personalFeedback.definition = {
    methods: ["get","head"],
    url: '/api/ai/insights/feedback',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
personalFeedback.url = (options?: RouteQueryOptions) => {
    return personalFeedback.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
personalFeedback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: personalFeedback.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
personalFeedback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: personalFeedback.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
    const personalFeedbackForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: personalFeedback.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
        personalFeedbackForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: personalFeedback.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::personalFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:1016
 * @route '/api/ai/insights/feedback'
 */
        personalFeedbackForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: personalFeedback.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    personalFeedback.form = personalFeedbackForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
export const policy = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: policy.url(args, options),
    method: 'get',
})

policy.definition = {
    methods: ["get","head"],
    url: '/ai-chatbot/policy/{filename}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
policy.url = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions) => {
    if (typeof args === 'string' || typeof args === 'number') {
        args = { filename: args }
    }

    
    if (Array.isArray(args)) {
        args = {
                    filename: args[0],
                }
    }

    args = applyUrlDefaults(args)

    const parsedArgs = {
                        filename: args.filename,
                }

    return policy.definition.url
            .replace('{filename}', parsedArgs.filename.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
policy.get = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: policy.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
policy.head = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: policy.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
    const policyForm = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: policy.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
        policyForm.get = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: policy.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:78
 * @route '/ai-chatbot/policy/{filename}'
 */
        policyForm.head = (args: { filename: string | number } | [filename: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: policy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    policy.form = policyForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::activityLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:896
 * @route '/api/ai/activity-logs'
 */
export const activityLogs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: activityLogs.url(options),
    method: 'get',
})

activityLogs.definition = {
    methods: ["get","head"],
    url: '/api/ai/activity-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::activityLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:896
 * @route '/api/ai/activity-logs'
 */
activityLogs.url = (options?: RouteQueryOptions) => {
    return activityLogs.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::activityLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:896
 * @route '/api/ai/activity-logs'
 */
activityLogs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: activityLogs.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::activityLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:896
 * @route '/api/ai/activity-logs'
 */
activityLogs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: activityLogs.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::activityLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:896
 * @route '/api/ai/activity-logs'
 */
    const activityLogsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: activityLogs.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::activityLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:896
 * @route '/api/ai/activity-logs'
 */
        activityLogsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: activityLogs.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::activityLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:896
 * @route '/api/ai/activity-logs'
 */
        activityLogsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: activityLogs.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    activityLogs.form = activityLogsForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingest
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:335
 * @route '/api/ai/ingest'
 */
export const ingest = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ingest.url(options),
    method: 'post',
})

ingest.definition = {
    methods: ["post"],
    url: '/api/ai/ingest',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingest
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:335
 * @route '/api/ai/ingest'
 */
ingest.url = (options?: RouteQueryOptions) => {
    return ingest.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingest
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:335
 * @route '/api/ai/ingest'
 */
ingest.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: ingest.url(options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingest
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:335
 * @route '/api/ai/ingest'
 */
    const ingestForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: ingest.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingest
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:335
 * @route '/api/ai/ingest'
 */
        ingestForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: ingest.url(options),
            method: 'post',
        })
    
    ingest.form = ingestForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingestLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:421
 * @route '/api/ai/ingest-logs'
 */
export const ingestLogs = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ingestLogs.url(options),
    method: 'get',
})

ingestLogs.definition = {
    methods: ["get","head"],
    url: '/api/ai/ingest-logs',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingestLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:421
 * @route '/api/ai/ingest-logs'
 */
ingestLogs.url = (options?: RouteQueryOptions) => {
    return ingestLogs.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingestLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:421
 * @route '/api/ai/ingest-logs'
 */
ingestLogs.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: ingestLogs.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingestLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:421
 * @route '/api/ai/ingest-logs'
 */
ingestLogs.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: ingestLogs.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingestLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:421
 * @route '/api/ai/ingest-logs'
 */
    const ingestLogsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: ingestLogs.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingestLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:421
 * @route '/api/ai/ingest-logs'
 */
        ingestLogsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ingestLogs.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::ingestLogs
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:421
 * @route '/api/ai/ingest-logs'
 */
        ingestLogsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: ingestLogs.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    ingestLogs.form = ingestLogsForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
export const exportFeedback = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportFeedback.url(options),
    method: 'get',
})

exportFeedback.definition = {
    methods: ["get","head"],
    url: '/api/ai/feedback/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
exportFeedback.url = (options?: RouteQueryOptions) => {
    return exportFeedback.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
exportFeedback.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportFeedback.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
exportFeedback.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportFeedback.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
    const exportFeedbackForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportFeedback.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
        exportFeedbackForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportFeedback.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportFeedback
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:502
 * @route '/api/ai/feedback/export'
 */
        exportFeedbackForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportFeedback.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportFeedback.form = exportFeedbackForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedbackSummary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
export const feedbackSummary = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: feedbackSummary.url(options),
    method: 'get',
})

feedbackSummary.definition = {
    methods: ["get","head"],
    url: '/api/ai/feedback/summary',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedbackSummary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
feedbackSummary.url = (options?: RouteQueryOptions) => {
    return feedbackSummary.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedbackSummary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
feedbackSummary.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: feedbackSummary.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedbackSummary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
feedbackSummary.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: feedbackSummary.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedbackSummary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
    const feedbackSummaryForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: feedbackSummary.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedbackSummary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
        feedbackSummaryForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: feedbackSummary.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::feedbackSummary
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:574
 * @route '/api/ai/feedback/summary'
 */
        feedbackSummaryForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: feedbackSummary.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    feedbackSummary.form = feedbackSummaryForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::analytics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:603
 * @route '/api/ai/analytics'
 */
export const analytics = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analytics.url(options),
    method: 'get',
})

analytics.definition = {
    methods: ["get","head"],
    url: '/api/ai/analytics',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::analytics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:603
 * @route '/api/ai/analytics'
 */
analytics.url = (options?: RouteQueryOptions) => {
    return analytics.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::analytics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:603
 * @route '/api/ai/analytics'
 */
analytics.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: analytics.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::analytics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:603
 * @route '/api/ai/analytics'
 */
analytics.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: analytics.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::analytics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:603
 * @route '/api/ai/analytics'
 */
    const analyticsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: analytics.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::analytics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:603
 * @route '/api/ai/analytics'
 */
        analyticsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analytics.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::analytics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:603
 * @route '/api/ai/analytics'
 */
        analyticsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: analytics.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    analytics.form = analyticsForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMetrics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
export const exportMetrics = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMetrics.url(options),
    method: 'get',
})

exportMetrics.definition = {
    methods: ["get","head"],
    url: '/api/ai/metrics/export',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMetrics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
exportMetrics.url = (options?: RouteQueryOptions) => {
    return exportMetrics.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMetrics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
exportMetrics.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: exportMetrics.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMetrics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
exportMetrics.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: exportMetrics.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMetrics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
    const exportMetricsForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: exportMetrics.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMetrics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
        exportMetricsForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMetrics.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::exportMetrics
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:693
 * @route '/api/ai/metrics/export'
 */
        exportMetricsForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: exportMetrics.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    exportMetrics.form = exportMetricsForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policyCoverage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:762
 * @route '/api/ai/policy-coverage'
 */
export const policyCoverage = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: policyCoverage.url(options),
    method: 'get',
})

policyCoverage.definition = {
    methods: ["get","head"],
    url: '/api/ai/policy-coverage',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policyCoverage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:762
 * @route '/api/ai/policy-coverage'
 */
policyCoverage.url = (options?: RouteQueryOptions) => {
    return policyCoverage.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policyCoverage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:762
 * @route '/api/ai/policy-coverage'
 */
policyCoverage.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: policyCoverage.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policyCoverage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:762
 * @route '/api/ai/policy-coverage'
 */
policyCoverage.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: policyCoverage.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policyCoverage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:762
 * @route '/api/ai/policy-coverage'
 */
    const policyCoverageForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: policyCoverage.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policyCoverage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:762
 * @route '/api/ai/policy-coverage'
 */
        policyCoverageForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: policyCoverage.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::policyCoverage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:762
 * @route '/api/ai/policy-coverage'
 */
        policyCoverageForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: policyCoverage.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    policyCoverage.form = policyCoverageForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::enhancementFramework
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:395
 * @route '/api/ai/enhancement-framework'
 */
export const enhancementFramework = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: enhancementFramework.url(options),
    method: 'get',
})

enhancementFramework.definition = {
    methods: ["get","head"],
    url: '/api/ai/enhancement-framework',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::enhancementFramework
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:395
 * @route '/api/ai/enhancement-framework'
 */
enhancementFramework.url = (options?: RouteQueryOptions) => {
    return enhancementFramework.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::enhancementFramework
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:395
 * @route '/api/ai/enhancement-framework'
 */
enhancementFramework.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: enhancementFramework.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::enhancementFramework
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:395
 * @route '/api/ai/enhancement-framework'
 */
enhancementFramework.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: enhancementFramework.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::enhancementFramework
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:395
 * @route '/api/ai/enhancement-framework'
 */
    const enhancementFrameworkForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: enhancementFramework.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::enhancementFramework
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:395
 * @route '/api/ai/enhancement-framework'
 */
        enhancementFrameworkForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: enhancementFramework.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotDataController::enhancementFramework
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotDataController.php:395
 * @route '/api/ai/enhancement-framework'
 */
        enhancementFrameworkForm.head = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: enhancementFramework.url({
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    enhancementFramework.form = enhancementFrameworkForm
const AIChatbotDataController = { context, users, leaveApplications, trainings, notices, health, suggestions, suggestionAnswer, feedback, personalInsights, personalFeedback, policy, activityLogs, ingest, ingestLogs, exportFeedback, feedbackSummary, analytics, exportMetrics, policyCoverage, enhancementFramework }

export default AIChatbotDataController