# Arquitectura Técnica del Sistema SIGEDOR

SIGEDOR está diseñado bajo una arquitectura modular y orientada a dominios académicos sobre el ecosistema moderno de **Laravel 11.x** y **Filament v3**, garantizando alta cohesión, desacoplamiento y cumplimiento estricto de los principios SOLID.

---

## 1. Stack Tecnológico

- **Framework Núcleo**: Laravel 11.x (PHP 8.2+ / 8.3+)
- **Panel Administrativo**: Filament v3 (Livewire 3, Alpine.js, Tailwind CSS)
- **Gestión de Identidad y Roles**: Spatie Laravel-Permission v6
- **Motor de Renderizado Documental**: DomPDF (`barryvdh/laravel-dompdf`)
- **Documentación de API**: L5-Swagger (OpenAPI 3.0 / Swagger UI)
- **Base de Datos**: MySQL 8.0+ / MariaDB 10.4+ (SQLite para testing en memoria)
- **Auditoría y Trazabilidad**: Spatie Laravel-Activitylog

---

## 2. Diagrama de Capas del Sistema

```mermaid
graph TD
    Client([Navegador / Cliente HTTP / API]) -->|HTTPS / REST| AppRouter[Enrutador Web / API]
    
    subgraph Capa de Presentación
        AppRouter -->|/admin| FilamentPanel[Panel Administrativo Filament v3]
        AppRouter -->|/| Landing[Landing Page Pública]
        AppRouter -->|/api/v1| RestApi[Controladores API Públicos]
        AppRouter -->|/api/documentation| SwaggerUi[Swagger UI]
    end

    subgraph Capa de Negocio y Seguridad
        FilamentPanel --> Policies[Políticas de Seguridad Spatie]
        FilamentPanel --> FormWizards[Formularios Wizard y Smart Selects]
        FilamentPanel --> Dossier360[Expediente 360 - RelationManagers]
        RestApi --> ResourcesDto[API Resources DTOs]
        Policies --> EloquentQueryScope[Multi-Tenant Query Scoping]
    end

    subgraph Capa de Dominio y Servicios
        FormWizards --> EloquentModels[Modelos Eloquent]
        EloquentModels --> LifecycleHooks[Hooks booted saved / deleted]
        Dossier360 --> PdfService[Motor de PDFs Institucionales UNERG]
    end

    subgraph Capa de Persistencia
        EloquentQueryScope --> DB[(Base de Datos MySQL / MariaDB)]
        LifecycleHooks --> DB
    end
```

---

## 3. Principios de Diseño y Patrones Arquitecturales

1. **Multi-Tenant Query Scoping**:
   - Cada recurso en Filament sobreescribe `getEloquentQuery()` para aplicar aislamiento territorial dinámico:
     - El rol `admin` obtiene acceso irrestricto a todas las sedes.
     - El rol `area_manager` ve acotada su consulta a los docentes y reportes de su propia sede.
     - El rol `teacher` solo accede a su registro personal.
2. **Sincronización Reactiva por Ciclo de Vida (Observer / Hooks Pattern)**:
   - En lugar de complejas llamadas distribuidas, los modelos `Category`, `Dedication` y `Site` emplean hooks `booted()` (`saved` y `deleted`) para mantener las claves foráneas de `Teacher` actualizadas automáticamente.
3. **Data Transfer Objects (DTO) en API**:
   - `TeacherPublicResource` y `ReportPublicResource` garantizan que ningún dato personal sensible (teléfonos, fechas de nacimiento, claves de usuario) sea expuesto a consumidores externos no autorizados.
4. **Segregación de Interfaces (ISP)**:
   - Los formularios extensos se estructuran en asistentes por pasos (*Wizards*) y gestores de relaciones modulares (*RelationManagers*), dividiendo la responsabilidad de cada vista.
