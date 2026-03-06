import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition, applyUrlDefaults } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::index
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:21
 * @route '/admin/backup'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/admin/backup',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::index
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:21
 * @route '/admin/backup'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::index
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:21
 * @route '/admin/backup'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::index
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:21
 * @route '/admin/backup'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::index
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:21
 * @route '/admin/backup'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::index
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:21
 * @route '/admin/backup'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::index
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:21
 * @route '/admin/backup'
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
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::run
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:34
 * @route '/admin/backup/run'
 */
export const run = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: run.url(options),
    method: 'post',
})

run.definition = {
    methods: ["post"],
    url: '/admin/backup/run',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::run
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:34
 * @route '/admin/backup/run'
 */
run.url = (options?: RouteQueryOptions) => {
    return run.definition.url + queryParams(options)
}

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::run
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:34
 * @route '/admin/backup/run'
 */
run.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: run.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::run
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:34
 * @route '/admin/backup/run'
 */
    const runForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: run.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::run
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:34
 * @route '/admin/backup/run'
 */
        runForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: run.url(options),
            method: 'post',
        })
    
    run.form = runForm
/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::upload
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:59
 * @route '/admin/backup/upload'
 */
export const upload = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(options),
    method: 'post',
})

upload.definition = {
    methods: ["post"],
    url: '/admin/backup/upload',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::upload
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:59
 * @route '/admin/backup/upload'
 */
upload.url = (options?: RouteQueryOptions) => {
    return upload.definition.url + queryParams(options)
}

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::upload
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:59
 * @route '/admin/backup/upload'
 */
upload.post = (options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: upload.url(options),
    method: 'post',
})

    /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::upload
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:59
 * @route '/admin/backup/upload'
 */
    const uploadForm = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: upload.url(options),
        method: 'post',
    })

            /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::upload
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:59
 * @route '/admin/backup/upload'
 */
        uploadForm.post = (options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: upload.url(options),
            method: 'post',
        })
    
    upload.form = uploadForm
/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::download
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:96
 * @route '/admin/backup/{id}/download'
 */
export const download = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})

download.definition = {
    methods: ["get","head"],
    url: '/admin/backup/{id}/download',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::download
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:96
 * @route '/admin/backup/{id}/download'
 */
download.url = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions) => {
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

    return download.definition.url
            .replace('{id}', parsedArgs.id.toString())
            .replace(/\/+$/, '') + queryParams(options)
}

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::download
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:96
 * @route '/admin/backup/{id}/download'
 */
download.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: download.url(args, options),
    method: 'get',
})
/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::download
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:96
 * @route '/admin/backup/{id}/download'
 */
download.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: download.url(args, options),
    method: 'head',
})

    /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::download
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:96
 * @route '/admin/backup/{id}/download'
 */
    const downloadForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: download.url(args, options),
        method: 'get',
    })

            /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::download
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:96
 * @route '/admin/backup/{id}/download'
 */
        downloadForm.get = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, options),
            method: 'get',
        })
            /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::download
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:96
 * @route '/admin/backup/{id}/download'
 */
        downloadForm.head = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: download.url(args, {
                        [options?.mergeQuery ? 'mergeQuery' : 'query']: {
                            _method: 'HEAD',
                            ...(options?.query ?? options?.mergeQuery ?? {}),
                        }
                    }),
            method: 'get',
        })
    
    download.form = downloadForm
/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::restore
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:109
 * @route '/admin/backup/{id}/restore'
 */
export const restore = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: restore.url(args, options),
    method: 'post',
})

restore.definition = {
    methods: ["post"],
    url: '/admin/backup/{id}/restore',
} satisfies RouteDefinition<["post"]>

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::restore
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:109
 * @route '/admin/backup/{id}/restore'
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
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::restore
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:109
 * @route '/admin/backup/{id}/restore'
 */
restore.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'post'> => ({
    url: restore.url(args, options),
    method: 'post',
})

    /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::restore
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:109
 * @route '/admin/backup/{id}/restore'
 */
    const restoreForm = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
        action: restore.url(args, options),
        method: 'post',
    })

            /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::restore
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:109
 * @route '/admin/backup/{id}/restore'
 */
        restoreForm.post = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteFormDefinition<'post'> => ({
            action: restore.url(args, options),
            method: 'post',
        })
    
    restore.form = restoreForm
/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::update
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:114
 * @route '/admin/backup/{id}'
 */
export const update = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

update.definition = {
    methods: ["put"],
    url: '/admin/backup/{id}',
} satisfies RouteDefinition<["put"]>

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::update
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:114
 * @route '/admin/backup/{id}'
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
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::update
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:114
 * @route '/admin/backup/{id}'
 */
update.put = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'put'> => ({
    url: update.url(args, options),
    method: 'put',
})

    /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::update
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:114
 * @route '/admin/backup/{id}'
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
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::update
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:114
 * @route '/admin/backup/{id}'
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
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::destroy
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:127
 * @route '/admin/backup/{id}'
 */
export const destroy = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

destroy.definition = {
    methods: ["delete"],
    url: '/admin/backup/{id}',
} satisfies RouteDefinition<["delete"]>

/**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::destroy
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:127
 * @route '/admin/backup/{id}'
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
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::destroy
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:127
 * @route '/admin/backup/{id}'
 */
destroy.delete = (args: { id: string | number } | [id: string | number ] | string | number, options?: RouteQueryOptions): RouteDefinition<'delete'> => ({
    url: destroy.url(args, options),
    method: 'delete',
})

    /**
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::destroy
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:127
 * @route '/admin/backup/{id}'
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
* @see \App\Features\Backup\Http\Controllers\Admin\BackupController::destroy
 * @see app/Features/Backup/Http/Controllers/Admin/BackupController.php:127
 * @route '/admin/backup/{id}'
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
const BackupController = { index, run, upload, download, restore, update, destroy }

export default BackupController