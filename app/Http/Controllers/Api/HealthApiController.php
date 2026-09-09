<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

/**
 * Controlador API para comprobación de disponibilidad y salud del servicio.
 *
 * Expone endpoints públicos para monitorización, healthcheck y verificación
 * del estado del backend de SIGEDOR.
 */
class HealthApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/ping",
     *     summary="Verificar disponibilidad y salud de la API",
     *     description="Retorna el estado operativo, marca de tiempo y versión de la API de SIGEDOR.",
     *     tags={"Sistema"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="API operativa y en línea",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="status", type="string", example="pong"),
     *             @OA\Property(property="timestamp", type="string", format="date-time", example="2026-09-09T12:00:00+00:00"),
     *             @OA\Property(property="version", type="string", example="1.0.0")
     *         )
     *     )
     * )
     */
    public function ping(): JsonResponse
    {
        return response()->json([
            'status' => 'pong',
            'timestamp' => now()->toIso8601String(),
            'version' => '1.0.0',
        ]);
    }
}
