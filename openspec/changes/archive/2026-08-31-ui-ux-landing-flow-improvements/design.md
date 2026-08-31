## Context

La lógica de negocio y las políticas de acceso del sistema SIGEDOR están estables (ver proposal.md), pero la interfaz pública (Landing Page) y la estética del panel administrativo (Filament) requieren refinamiento para ofrecer una experiencia cohesiva. Actualmente, Filament gestiona el backend visual, por lo que cualquier mejora debe integrarse orgánicamente a su ecosistema de Tailwind CSS y Livewire.

## Goals / Non-Goals

**Goals:**
- Asegurar que la ruta `/` renderice una Landing Page moderna que actúe como portada pública del sistema.
- Mejorar la estética del panel de Filament creando un tema personalizado (colores, fuentes, espaciados).
- Habilitar feedback en tiempo real mediante notificaciones visuales (Toasts) para todas las acciones CRUD.

**Non-Goals:**
- No se reescribirá la arquitectura del panel administrativo. Se continuará usando Filament v3.
- No se modificarán las políticas de seguridad (Policies) ni los scopes territoriales existentes.

## Decisions

### 1. Enrutamiento y Landing Page
- **Decisión:** Configurar `routes/web.php` para retornar la vista `welcome` en la ruta `/`, con enlaces directos a `/admin/login`.
- **Alternativas:** Forzar redirección directa a `/admin` (descartado porque elimina la cara pública institucional del proyecto).

### 2. Personalización Visual (UI)
- **Decisión:** Utilizar el comando nativo `php artisan make:filament-theme` para generar un tema personalizado basado en Tailwind. Esto permite sobreescribir variables de color primario (ej. colores institucionales de la universidad) y tipografía sin alterar el núcleo de Filament.
- **Alternativas:** Inyectar CSS crudo con `!important` (descartado por mala práctica y problemas de mantenibilidad a futuro).

### 3. Feedback Visual (UX)
- **Decisión:** Estandarizar el uso de `Notification::make()->success()->send()` de Filament en todas las acciones de los controladores/recursos para proveer micro-animaciones (Toasts) al usuario.
- **Alternativas:** Alertas de JavaScript puro o modales bloqueantes (descartado por interrumpir el flujo del usuario).

## Risks / Trade-offs

- **[Riesgo] Compilación de Assets:** Modificar el tema de Filament requiere compilar los assets de Tailwind. Si no se ejecuta `npm run build`, los estilos no se reflejarán en producción.
  - *Mitigación:* Documentar claramente el proceso de compilación de assets (Vite) como paso obligatorio tras el despliegue.
