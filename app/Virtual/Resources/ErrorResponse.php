<?php

namespace App\Virtual\Resources;

/**
 * @OA\Schema(
 *     title="ErrorResponse",
 *     description="Estructura estándar de respuesta de error",
 *     @OA\Xml(
 *         name="ErrorResponse"
 *     )
 * )
 */
class ErrorResponse
{
    /**
     * @OA\Property(
     *     title="Message",
     *     description="Mensaje de error",
     *     example="El recurso solicitado no fue encontrado."
     * )
     *
     * @var string
     */
    public $message;

    /**
     * @OA\Property(
     *     title="Errors",
     *     description="Detalle de errores (por ejemplo, validación de campos)",
     *     type="object",
     *     additionalProperties={
     *         "type":"array",
     *         "items":{
     *             "type":"string"
     *         }
     *     },
     *     example={"field_name": {"The field_name is required."}},
     *     nullable=true
     * )
     *
     * @var object|null
     */
    public $errors;
}
