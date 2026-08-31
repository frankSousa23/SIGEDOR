<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="SIGEDOR API",
 *      description="Documentación de la API del Sistema de Gestión Docente y Reportes (SIGEDOR).",
 *
 *      @OA\Contact(
 *          email="soporte@sigedor.com"
 *      ),
 *
 *      @OA\License(
 *          name="MIT",
 *          url="https://opensource.org/licenses/MIT"
 *      )
 * )
 *
 * @OA\Server(
 *      url=L5_SWAGGER_CONST_HOST,
 *      description="Servidor Principal de API"
 * )
 *
 * @OA\Get(
 *     path="/api/ping",
 *     summary="Verificar estado de la API",
 *     tags={"Health"},
 *
 *     @OA\Response(
 *         response=200,
 *         description="API funcionando correctamente",
 *
 *         @OA\JsonContent(
 *
 *             @OA\Property(property="status", type="string", example="ok")
 *         )
 *     )
 * )
 */
abstract class Controller {}
