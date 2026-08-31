<?php

namespace App\Virtual\Models;

/**
 * @OA\Schema(
 *     title="Teacher",
 *     description="Modelo de Docente",
 *     @OA\Xml(
 *         name="Teacher"
 *     )
 * )
 */
class Teacher
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
     *      title="CDI",
     *      description="Cédula de Identidad",
     *      example="V-12345678"
     * )
     *
     * @var string
     */
    public $cdi;

    /**
     * @OA\Property(
     *      title="Name",
     *      description="Nombre del docente",
     *      example="Juan"
     * )
     *
     * @var string
     */
    public $name;

    /**
     * @OA\Property(
     *      title="Surname",
     *      description="Apellido del docente",
     *      example="Pérez"
     * )
     *
     * @var string
     */
    public $surName;

    /**
     * @OA\Property(
     *      title="Genre",
     *      description="Género",
     *      example="Masculino"
     * )
     *
     * @var string
     */
    public $genre;

    /**
     * @OA\Property(
     *      title="Phone",
     *      description="Teléfono de contacto",
     *      example="+584121234567"
     * )
     *
     * @var string
     */
    public $phone;

    /**
     * @OA\Property(
     *      title="Email",
     *      description="Correo electrónico institucional o personal",
     *      example="juan.perez@sigedor.com"
     * )
     *
     * @var string
     */
    public $email;

    /**
     * @OA\Property(
     *      title="Birth Date",
     *      description="Fecha de nacimiento",
     *      example="1980-01-01",
     *      format="date"
     * )
     *
     * @var string
     */
    public $birthDate;

    /**
     * @OA\Property(
     *      title="Date Promotion",
     *      description="Fecha del último ascenso",
     *      example="2020-05-15",
     *      format="date"
     * )
     *
     * @var string
     */
    public $datePromotion;

    /**
     * @OA\Property(
     *      title="Asignature Promotion",
     *      description="Asignatura de ascenso",
     *      example="Matemáticas I"
     * )
     *
     * @var string
     */
    public $asignaturePromotion;

    /**
     * @OA\Property(
     *      title="User ID",
     *      description="ID del usuario asociado",
     *      format="int64",
     *      example=10
     * )
     *
     * @var integer
     */
    public $user_id;

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
     *      title="Programa ID",
     *      description="ID del programa académico",
     *      format="int64",
     *      example=1
     * )
     *
     * @var integer
     */
    public $programa_id;

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
