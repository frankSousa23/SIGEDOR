<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Recurso API público para Docentes.
 *
 * Protege datos personales identificables (PII) omitiendo deliberadamente
 * números telefónicos, fechas de nacimiento e identificadores de usuario del sistema.
 *
 * @mixin \App\Models\Teacher
 */
class TeacherPublicResource extends JsonResource
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
            'cdi' => $this->cdi,
            'name' => $this->name,
            'surName' => $this->surName,
            'genre' => $this->genre,
            'email' => $this->email,
            'datePromotion' => $this->datePromotion?->format('Y-m-d'),
            'asignaturePromotion' => $this->asignaturePromotion,
            'sede' => $this->whenLoaded('sede', function () {
                return [
                    'id' => $this->sede->id,
                    'nombre' => $this->sede->nombre,
                ];
            }),
            'area' => $this->whenLoaded('area', function () {
                return [
                    'id' => $this->area->id,
                    'nombre' => $this->area->nombre,
                ];
            }),
            'programa' => $this->whenLoaded('programa', function () {
                return $this->programa ? [
                    'id' => $this->programa->id,
                    'nombre' => $this->programa->nombre,
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
                    'hours' => $this->dedication->hours,
                ] : null;
            }),
        ];
    }
}
