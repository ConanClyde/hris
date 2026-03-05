import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../wayfinder'
import suggestionsA0eb50 from './suggestions'
import feedback621f6b from './feedback'
import insights from './insights'
import metrics from './metrics'
import conversations from './conversations'
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
const ai = {
    context: Object.assign(context, context),
users: Object.assign(users, users),
leaveApplications: Object.assign(leaveApplications, leaveApplications),
trainings: Object.assign(trainings, trainings),
notices: Object.assign(notices, notices),
health: Object.assign(health, health),
suggestions: Object.assign(suggestions, suggestionsA0eb50),
feedback: Object.assign(feedback, feedback621f6b),
insights: Object.assign(insights, insights),
activityLogs: Object.assign(activityLogs, activityLogs),
ingest: Object.assign(ingest, ingest),
ingestLogs: Object.assign(ingestLogs, ingestLogs),
analytics: Object.assign(analytics, analytics),
metrics: Object.assign(metrics, metrics),
policyCoverage: Object.assign(policyCoverage, policyCoverage),
enhancementFramework: Object.assign(enhancementFramework, enhancementFramework),
conversations: Object.assign(conversations, conversations),
}

export default ai