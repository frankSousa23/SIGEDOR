<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso API público para Reportes y Dictámenes Académicos.
 *
 * @mixin \App\Models\Report
 */
class ReportPublicResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'memoNumber' => $this->memoNumber,
            'typeReport' => $this->typeReport,
            'report' => $this->report,
            'created_at' => $this->created_at?->toIso8601String(),
            'teacher' => new TeacherPublicResource($this->whenLoaded('teacher')),
            'sede' => $this->whenLoaded('sede', function () {
                return $this->sede ? [
                    'id' => $this->sede->id,
                    'nombre' => $this->sede->nombre,
                ] : null;
            }),
            'area' => $this->whenLoaded('area', function () {
                return $this->area ? [
                    'id' => $this->area->id,
                    'nombre' => $this->area->nombre,
                ] : null;
            }),
            'category' => $this->whenLoaded('category', function () {
                return $this->category ? [
                    'id' => $this->category->id,
                    'current_category' => $this->category->current_category,
                ] : null;
            }),
            'dedication' => $this->whenLoaded('dedication', function () {
                return $this->dedication ? [
                    'id' => $this->dedication->id,
                    'name' => $this->dedication->name,
                    'type' => $this->dedication->type,
                ] : null;
            }),
        ];
    }
}
