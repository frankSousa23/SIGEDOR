<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador API para consulta de Reportes Académicos.
 */
class ReportApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/reports",
     *     summary="Listar reportes académicos",
     *     description="Obtiene la lista de reportes emitidos en el sistema.",
     *     tags={"Reportes"},
     *     @OA\Response(
     *         response=200,
     *         description="Listado de reportes obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $reports = Report::with(['teacher', 'sede', 'area', 'category', 'dedication'])->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $reports,
        ]);
    }
}
