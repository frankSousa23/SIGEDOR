<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Report",
 *     description="Modelo de Reporte Académico",
 *
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
     * @var int
     */
    private $id;

    /**
     * @OA\Property(
     *      title="Verification Code",
     *      description="Código único de verificación y autenticidad documental institucional",
     *      example="UNERG-REP-2026-ABCD"
     * )
     *
     * @var string
     */
    public $verification_code;

    /**
     * @OA\Property(
     *      title="Status",
     *      description="Estado del reporte (draft, issued, archived)",
     *      example="issued",
     *      enum={"draft", "issued", "archived"}
     * )
     *
     * @var string
     */
    public $status;

    /**
     * @OA\Property(
     *      title="Created By",
     *      description="ID del usuario emisor del documento",
     *      format="int64",
     *      example=1
     * )
     *
     * @var int
     */
    public $created_by;

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
     * @var int
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
     * @var int
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
     * @var int
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
     * @var int
     */
    public $dedication_id;
}
