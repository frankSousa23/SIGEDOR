<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Report",
 *     description="Modelo de Reporte Académico",
 *     @OA\Xml(
 *         name="Report"
 *     )
 * )
 */
class Report
{
    /**
     * @OA\Property(
     *     title="ID",
     *     description="ID interno",
     *     format="int64",
     *     example=1
     * )
     *
     * @var integer
     */
    private $id;

    /**
     * @OA\Property(
     *      title="Teacher CDI",
     *      description="Cédula del docente asociado",
     *      example="V-12345678"
     * )
     *
     * @var string
     */
    public $teacher_cdi;

    /**
     * @OA\Property(
     *      title="Memo Number",
     *      description="Número de memorando",
     *      example="MEMO-2023-001"
     * )
     *
     * @var string
     */
    public $memoNumber;

    /**
     * @OA\Property(
     *      title="Type Report",
     *      description="Tipo de reporte",
     *      example="Ascenso"
     * )
     *
     * @var string
     */
    public $typeReport;

    /**
     * @OA\Property(
     *      title="Report",
     *      description="Contenido o ruta del reporte",
     *      example="/reports/123.pdf"
     * )
     *
     * @var string
     */
    public $report;

    /**
     * @OA\Property(
     *      title="Email",
     *      description="Correo al cual se envió",
     *      example="correo@ejemplo.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="Info",
     *      description="Información adicional",
     *      example="Reporte generado automáticamente"
     * )
     *
     * @var string
     */
    public $info;

    /**
     * @OA\Property(
     *      title="Sede ID",
     *      description="ID de la sede asignada",
     *      format="int64",
     *      example=2
     * )
     *
     * @var integer
     */
    public $sede_id;

    /**
     * @OA\Property(
     *      title="Area ID",
     *      description="ID del área académica",
     *      format="int64",
     *      example=3
     * )
     *
     * @var integer
     */
    public $area_id;

    /**
     * @OA\Property(
     *      title="Category ID",
     *      description="ID de la categoría docente",
     *      format="int64",
     *      example=4
     * )
     *
     * @var integer
     */
    public $category_id;

    /**
     * @OA\Property(
     *      title="Dedication ID",
     *      description="ID de la dedicación docente",
     *      format="int64",
     *      example=2
     * )
     *
     * @var integer
     */
    public $dedication_id;
}
