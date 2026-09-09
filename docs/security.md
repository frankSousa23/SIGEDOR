# Arquitectura de Seguridad y Control de Acceso (RBAC) en SIGEDOR

SIGEDOR implementa un modelo de seguridad por capas diseñado para entornos universitarios multi-sede. Combina autenticación estricta bajo dominio institucional, autorización granular basada en roles con **Spatie Laravel-Permission**, políticas de modelo (*Policies*), aislamiento multi-inquilino por consultas Eloquent (*Multi-Tenant Query Scoping*) y blindaje de interfaces en Filament v3.

---

## 1. Autenticación Institucional Estricta

- **Restricción de Dominio**: El acceso al panel administrativo está limitado estrictamente a cuentas con correos electrónicos bajo el dominio institucional oficial `@sigedor.com` (validado mediante middleware y reglas de autenticación).
- **Flujo de Aprobación (`is_approved`)**: Las cuentas registradas requieren aprobación explícita de un Administrador antes de que se les permita iniciar sesión.
- **Suspensión de Cuentas (`is_active`)**: Permite desactivar inmediatamente el acceso al sistema sin eliminar los registros curriculares del usuario.
- **Protección contra Fuerza Bruta**: Límite de intentos (*Rate Limiting*) en rutas web y API (`throttle:60,1`).

---

## 2. Roles del Sistema (Spatie Laravel-Permission)

El sistema opera con tres roles principales claramente definidos:

### 2.1 Super Administrador (`admin`)
- Visión y alcance global sobre todos los núcleos territoriales y áreas académicas.
- Único rol con autorización para asignar roles de sistema y conmutar el estado de activación (`is_active`) o aprobación (`is_approved`) de usuarios.
- Creación, modificación y eliminación en todos los recursos institucionales.
- Acceso a registros de auditoría y telemetría del sistema.

### 2.2 Jefe de Área (`area_manager`)
- Alcance multi-inquilino circunscrito a su Sede (`sede_id`) y Área de Conocimiento (`area_id`) asignadas.
- Supervisión del claustro docente adscrito a su sede.
- Aprobación y resolución de solicitudes de permisos de sus profesores.
- Emisión de memorandos administrativos oficiales.
- Bloqueo estricto para modificar roles o autoaprobar usuarios.

### 2.3 Docente Académico (`teacher`)
- Acceso de solo lectura a su expediente personal, escalafón docente y carga horaria asignada.
- Registro de solicitudes de permisos y licencias (forzando estado inicial `pending`).
- Consulta y descarga exclusiva de sus propios reportes y constancias emitidas (sin permisos para editar o eliminar documentos).

---

## 3. Políticas de Autorización por Modelo (Policies)

Cada recurso clave cuenta con una política dedicada registrada en el proveedor de autorización de Laravel:

| Política | Modelo | Regla Principal de Aislamiento |
|---|---|---|
| **`TeacherPolicy`** | `Teacher` | Docentes solo ven su propio expediente (`$user->teacher?->cdi === $teacher->cdi`). Jefes de área solo ven docentes de su misma sede y área. Administrador tiene acceso global. |
| **`ReportPolicy`** | `Report` | Docentes solo ven sus reportes y no pueden modificarlos ni eliminarlos. Jefes de área solo ven reportes de docentes de su sede. |
| **`CategoryPolicy`** | `Category` | Operaciones protegidas con operadores nullsafe. Jefes de área autorizados para registrar ascensos en su sede. |
| **`DedicationPolicy`** | `Dedication` | Validación de horas reglamentarias y asignación multi-sede. |
| **`PermissionTeacherPolicy`** | `PermissionTeacher` | Docentes no pueden cambiar el estado a `approved` ni modificar el campo remunerado (`is_paid`). |
| **`UserPolicy`** | `User` | Solo `admin` puede alterar roles o estados de aprobación de cuentas. |

---

## 4. Blindaje contra Escalada de Privilegios en Filament

- **Protección de ToggleColumn**: En `UserResource`, los conmutadores AJAX `is_approved` e `is_active` implementan:
  ```php
  ToggleColumn::make('is_approved')
      ->disabled(fn () => ! auth()->user()?->isAdmin())
  ```
  Esto bloquea cualquier intento de manipulación del DOM o llamadas asíncronas desde cuentas no autorizadas.
- **Campos Ocultos o Bloqueados**: En formularios de permisos y reportes, la cédula del docente solicitante se autocompleta con su perfil de sesión y se deshabilita para impedir la suplantación de identidad entre docentes.

---

## 5. Auditoría y Trazabilidad

- **`spatie/laravel-activitylog`**: Registra accesos, creación de reportes y cambios en expedientes docentes.
- **Códigos de Autenticidad Documental**: Cada reporte oficial incluye un identificador `verification_code` (`UNERG-REP-YYYY-XXXX`) único e indexado, permitiendo validar la legitimidad física o digital de constancias y memorandos emitidos.
