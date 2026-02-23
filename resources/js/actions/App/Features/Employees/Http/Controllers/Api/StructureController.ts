import { queryParams, type RouteQueryOptions, type RouteDefinition, type RouteFormDefinition } from './../../../../../../../wayfinder'
/**
* @see \App\Features\Employees\Http\Controllers\Api\StructureController::index
 * @see app/Features/Employees/Http/Controllers/Api/StructureController.php:11
 * @route '/api/v1/organizational-structure'
 */
export const index = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})

index.definition = {
    methods: ["get","head"],
    url: '/api/v1/organizational-structure',
} satisfies RouteDefinition<["get","head"]>

/**
* @see \App\Features\Employees\Http\Controllers\Api\StructureController::index
 * @see app/Features/Employees/Http/Controllers/Api/StructureController.php:11
 * @route '/api/v1/organizational-structure'
 */
index.url = (options?: RouteQueryOptions) => {
    return index.definition.url + queryParams(options)
}

/**
* @see \App\Features\Employees\Http\Controllers\Api\StructureController::index
 * @see app/Features/Employees/Http/Controllers/Api/StructureController.php:11
 * @route '/api/v1/organizational-structure'
 */
index.get = (options?: RouteQueryOptions): RouteDefinition<'get'> => ({
    url: index.url(options),
    method: 'get',
})
/**
* @see \App\Features\Employees\Http\Controllers\Api\StructureController::index
 * @see app/Features/Employees/Http/Controllers/Api/StructureController.php:11
 * @route '/api/v1/organizational-structure'
 */
index.head = (options?: RouteQueryOptions): RouteDefinition<'head'> => ({
    url: index.url(options),
    method: 'head',
})

    /**
* @see \App\Features\Employees\Http\Controllers\Api\StructureController::index
 * @see app/Features/Employees/Http/Controllers/Api/StructureController.php:11
 * @route '/api/v1/organizational-structure'
 */
    const indexForm = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
        action: index.url(options),
        method: 'get',
    })

            /**
* @see \App\Features\Employees\Http\Controllers\Api\StructureController::index
 * @see app/Features/Employees/Http/Controllers/Api/StructureController.php:11
 * @route '/api/v1/organizational-structure'
 */
        indexForm.get = (options?: RouteQueryOptions): RouteFormDefinition<'get'> => ({
            action: index.url(options),
            method: 'get',
        })
            /**
* @see \App\Features\Employees\Http\Controllers\Api\StructureController::index
 * @see app/Features/Employees/Http/Controllers/Api/StructureController.php:11
 * @route '/api/v1/organizational-structure'
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
const StructureController = { index }

export default StructureController