import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../wayfinder'
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::index
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:14
 * @route '/api/ai/conversations'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/ai/conversations',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::index
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:14
 * @route '/api/ai/conversations'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::index
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:14
 * @route '/api/ai/conversations'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::index
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:14
 * @route '/api/ai/conversations'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::index
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:14
 * @route '/api/ai/conversations'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::index
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:14
 * @route '/api/ai/conversations'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::index
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:14
 * @route '/api/ai/conversations'
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
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:55
 * @route '/api/ai/conversations'
 */
export const store = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

store.definition = {
    methods: ["post"],
    url: '/api/ai/conversations',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:55
 * @route '/api/ai/conversations'
 */
store.url = (options?: RouteQueryOptions) => {
    return store.definition.url + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:55
 * @route '/api/ai/conversations'
 */
store.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: store.url(options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:55
 * @route '/api/ai/conversations'
 */
    const storeForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: store.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::store
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:55
 * @route '/api/ai/conversations'
 */
        storeForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: store.url(options),
            method: 'post',
        })
    
    store.form = storeForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::show
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:83
 * @route '/api/ai/conversations/{id}'
 */
export const show = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})

show.definition = {
    methods: ["get","head"],
    url: '/api/ai/conversations/{id}',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::show
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:83
 * @route '/api/ai/conversations/{id}'
 */
show.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return show.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::show
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:83
 * @route '/api/ai/conversations/{id}'
 */
show.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: show.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::show
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:83
 * @route '/api/ai/conversations/{id}'
 */
show.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: show.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::show
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:83
 * @route '/api/ai/conversations/{id}'
 */
    const showForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: show.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::show
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:83
 * @route '/api/ai/conversations/{id}'
 */
        showForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::show
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:83
 * @route '/api/ai/conversations/{id}'
 */
        showForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: show.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    show.form = showForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::update
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:120
 * @route '/api/ai/conversations/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/api/ai/conversations/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::update
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:120
 * @route '/api/ai/conversations/{id}'
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
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::update
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:120
 * @route '/api/ai/conversations/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::update
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:120
 * @route '/api/ai/conversations/{id}'
 */
    const updateForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: update.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'PUT',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::update
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:120
 * @route '/api/ai/conversations/{id}'
 */
        updateForm.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: update.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'PUT',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    update.form = updateForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::destroy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:153
 * @route '/api/ai/conversations/{id}'
 */
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/api/ai/conversations/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::destroy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:153
 * @route '/api/ai/conversations/{id}'
 */
destroy.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return destroy.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::destroy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:153
 * @route '/api/ai/conversations/{id}'
 */
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::destroy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:153
 * @route '/api/ai/conversations/{id}'
 */
    const destroyForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: destroy.url(args, {
                    [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                        _method: 'DELETE',
                        ...(options?.query ?? options?.mergeQuery ?? {}),
                    }
                }),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::destroy
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:153
 * @route '/api/ai/conversations/{id}'
 */
        destroyForm.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: destroy.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'DELETE',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'post',
        })
    
    destroy.form = destroyForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::archive
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:173
 * @route '/api/ai/conversations/{id}/archive'
 */
export const archive = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: archive.url(args, options),
    method: 'post',
})

archive.definition = {
    methods: ["post"],
    url: '/api/ai/conversations/{id}/archive',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::archive
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:173
 * @route '/api/ai/conversations/{id}/archive'
 */
archive.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return archive.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::archive
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:173
 * @route '/api/ai/conversations/{id}/archive'
 */
archive.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: archive.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::archive
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:173
 * @route '/api/ai/conversations/{id}/archive'
 */
    const archiveForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: archive.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::archive
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:173
 * @route '/api/ai/conversations/{id}/archive'
 */
        archiveForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: archive.url(args, options),
            method: 'post',
        })
    
    archive.form = archiveForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::restore
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:199
 * @route '/api/ai/conversations/{id}/restore'
 */
export const restore = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: restore.url(args, options),
    method: 'post',
})

restore.definition = {
    methods: ["post"],
    url: '/api/ai/conversations/{id}/restore',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::restore
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:199
 * @route '/api/ai/conversations/{id}/restore'
 */
restore.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return restore.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::restore
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:199
 * @route '/api/ai/conversations/{id}/restore'
 */
restore.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: restore.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::restore
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:199
 * @route '/api/ai/conversations/{id}/restore'
 */
    const restoreForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: restore.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::restore
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:199
 * @route '/api/ai/conversations/{id}/restore'
 */
        restoreForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: restore.url(args, options),
            method: 'post',
        })
    
    restore.form = restoreForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::storeMessage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
export const storeMessage = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeMessage.url(args, options),
    method: 'post',
})

storeMessage.definition = {
    methods: ["post"],
    url: '/api/ai/conversations/{id}/messages',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::storeMessage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
storeMessage.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return storeMessage.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::storeMessage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
storeMessage.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: storeMessage.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::storeMessage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
    const storeMessageForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: storeMessage.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::storeMessage
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:225
 * @route '/api/ai/conversations/{id}/messages'
 */
        storeMessageForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: storeMessage.url(args, options),
            method: 'post',
        })
    
    storeMessage.form = storeMessageForm
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recentMessages
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
export const recentMessages = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: recentMessages.url(args, options),
    method: 'get',
})

recentMessages.definition = {
    methods: ["get","head"],
    url: '/api/ai/conversations/{id}/messages/recent',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recentMessages
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
recentMessages.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return recentMessages.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recentMessages
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
recentMessages.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: recentMessages.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recentMessages
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
recentMessages.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: recentMessages.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recentMessages
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
    const recentMessagesForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: recentMessages.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recentMessages
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
        recentMessagesForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: recentMessages.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\AIChatbot\Http\Controllers\AIChatbotConversationController::recentMessages
 * @see app/Features/AIChatbot/Http/Controllers/AIChatbotConversationController.php:272
 * @route '/api/ai/conversations/{id}/messages/recent'
 */
        recentMessagesForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: recentMessages.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    recentMessages.form = recentMessagesForm
const AIChatbotConversationController = { index, store, show, update, destroy, archive, restore, storeMessage, recentMessages }

export default AIChatbotConversationController