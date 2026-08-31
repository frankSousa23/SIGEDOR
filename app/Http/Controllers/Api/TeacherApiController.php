<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador API para consulta y gestión de Docentes.
 */
class TeacherApiController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/teachers",
     *     summary="Listar docentes del sistema",
     *     description="Obtiene la lista paginada de docentes con sus relaciones institucionales.",
     *     tags={"Docentes"},
     *     @OA\Parameter(
     *         name="sede_id",
     *         in="query",
     *         description="Filtrar por ID de sede",
     *         required=false,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Listado de docentes obtenido exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $query = Teacher::with(['sede', 'area', 'programa', 'category', 'dedication']);

        if ($request->filled('sede_id')) {
            $query->where('sede_id', $request->input('sede_id'));
        }

        $teachers = $query->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $teachers,
        ]);
    }
}

