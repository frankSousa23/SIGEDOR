<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Modelo de Docente del Sistema SIGEDOR.
 *
 * Gestiona el expediente académico, datos personales, categorización,
 * dedicación horaria, asignación de sede/área y permisos de los profesores.
 *
 * @property int $id
 * @property string $cdi
 * @property string $name
 * @property string $surName
 * @property string $genre
 * @property string|null $phone
 * @property string $email
 * @property \Carbon\Carbon|null $birthDate
 * @property \Carbon\Carbon|null $datePromotion
 * @property string|null $asignaturePromotion
 * @property int $user_id
 * @property int $sede_id
 * @property int $area_id
 * @property int|null $programa_id
 * @property int|null $site_id
 * @property int|null $category_id
 * @property int|null $dedication_id
 */
class Teacher extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'teachers';

    protected $fillable = [
        'cdi',
        'name',
        'surName',
        'genre',
        'phone',
        'email',
        'birthDate',
        'datePromotion',
        'asignaturePromotion',
        'user_id',
        'sede_id',
        'area_id',
        'programa_id',
        'site_id',
        'category_id',
        'dedication_id',
    ];

    protected $casts = [
        'birthDate' => 'date',
        'datePromotion' => 'date',
        'user_id' => 'integer',
        'sede_id' => 'integer',
        'area_id' => 'integer',
        'programa_id' => 'integer',
        'site_id' => 'integer',
        'category_id' => 'integer',
        'dedication_id' => 'integer',
    ];

    /**
     * Usuario de acceso al sistema vinculado al docente.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Sede universitaria asignada.
     */
    public function sede(): BelongsTo
    {
        return $this->belongsTo(Sede::class);
    }

    /**
     * Área académica asignada.
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Programa académico asignado.
     */
    public function programa(): BelongsTo
    {
        return $this->belongsTo(Programa::class);
    }

    /**
     * Categoría docente actual por relación directa o CDI.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Categoría asociada por CDI.
     */
    public function categoryRecord(): HasOne
    {
        return $this->hasOne(Category::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Dedicación docente actual por relación directa.
     */
    public function dedication(): BelongsTo
    {
        return $this->belongsTo(Dedication::class, 'dedication_id');
    }

    /**
     * Dedicación asociada por CDI.
     */
    public function dedicationRecord(): HasOne
    {
        return $this->hasOne(Dedication::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Asignación de sede / carga horaria vinculada.
     */
    public function site(): BelongsTo
    {
        return $this->belongsTo(Site::class, 'site_id');
    }

    /**
     * Asignaciones de sede asociadas por CDI.
     */
    public function siteRecord(): HasOne
    {
        return $this->hasOne(Site::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Permisos y licencias del docente.
     */
    public function permissionsTeachers(): HasMany
    {
        return $this->hasMany(PermissionTeacher::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Reportes y memorandos generados para el docente.
     */
    public function reports(): HasMany
    {
        return $this->hasMany(Report::class, 'teacher_cdi', 'cdi');
    }

    /**
     * Nombre completo formateado (Nombre + Apellido).
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->name} {$this->surName}");
    }
}
