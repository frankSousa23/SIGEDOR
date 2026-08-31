<?php

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *     title="PaginationMeta",
 *     description="Metadatos de paginación de Laravel",
 *     @OA\Xml(
 *         name="PaginationMeta"
 *     )
 * )
 */
class PaginationMeta
{
    /**
     * @OA\Property(
     *     title="Current Page",
     *     description="Página actual",
     *     format="int32",
     *     example=1
     * )
     *
     * @var integer
     */
    public $current_page;

    /**
     * @OA\Property(
     *     title="First Page URL",
     *     description="URL de la primera página",
     *     example="http://localhost/api/v1/resource?page=1"
     * )
     *
     * @var string
     */
    public $first_page_url;

    /**
     * @OA\Property(
     *     title="From",
     *     description="Desde el elemento número",
     *     format="int32",
     *     example=1
     * )
     *
     * @var integer
     */
    public $from;

    /**
     * @OA\Property(
     *     title="Last Page",
     *     description="Última página disponible",
     *     format="int32",
     *     example=5
     * )
     *
     * @var integer
     */
    public $last_page;

    /**
     * @OA\Property(
     *     title="Last Page URL",
     *     description="URL de la última página",
     *     example="http://localhost/api/v1/resource?page=5"
     * )
     *
     * @var string
     */
    public $last_page_url;

    /**
     * @OA\Property(
     *     title="Next Page URL",
     *     description="URL de la siguiente página",
     *     example="http://localhost/api/v1/resource?page=2",
     *     nullable=true
     * )
     *
     * @var string|null
     */
    public $next_page_url;

    /**
     * @OA\Property(
     *     title="Path",
     *     description="Ruta base del recurso",
     *     example="http://localhost/api/v1/resource"
     * )
     *
     * @var string
     */
    public $path;

    /**
     * @OA\Property(
     *     title="Per Page",
     *     description="Elementos por página",
     *     format="int32",
     *     example=15
     * )
     *
     * @var integer
     */
    public $per_page;

    /**
     * @OA\Property(
     *     title="Prev Page URL",
     *     description="URL de la página anterior",
     *     example=null,
     *     nullable=true
     * )
     *
     * @var string|null
     */
    public $prev_page_url;

    /**
     * @OA\Property(
     *     title="To",
     *     description="Hasta el elemento número",
     *     format="int32",
     *     example=15
     * )
     *
     * @var integer
     */
    public $to;

    /**
     * @OA\Property(
     *     title="Total",
     *     description="Total de elementos",
     *     format="int32",
     *     example=75
     * )
     *
     * @var integer
     */
    public $total;

    /**
     * @OA\Property(
     *     title="Links",
     *     description="Enlaces de paginación",
     *     type="array",
     *     @OA\Items(
     *         type="object",
     *         @OA\Property(property="url", type="string", nullable=true, example="http://localhost/api/v1/resource?page=1"),
     *         @OA\Property(property="label", type="string", example="1"),
     *         @OA\Property(property="active", type="boolean", example=true)
     *     )
     * )
     *
     * @var array
     */
    public $links;
}
