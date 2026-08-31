## 1. Enrutamiento y Landing Page

- [x] 1.1 Modificar `routes/web.php` para asegurar que la ruta `/` retorne la vista `welcome` y verificar accediendo al root del servidor local.
- [x] 1.2 Actualizar `resources/views/welcome.blade.php` para modernizar la interfaz pública, añadiendo botones de acceso a `/admin/login` y verificar su correcto funcionamiento visualmente.

## 2. Personalización Visual (Filament Theme)

- [x] 2.1 Ejecutar `php artisan make:filament-theme` para generar la estructura del tema personalizado y verificar la creación de los archivos CSS en `resources/css/filament`.
- [x] 2.2 Registrar el nuevo tema en `app/Providers/Filament/AdminPanelProvider.php` usando `->theme()` y verificar que el panel siga cargando sin errores.
- [x] 2.3 Modificar las variables Tailwind del tema para unificar los colores corporativos y compilar con `npm run build` (verificar cambios visuales en el panel).

## 3. Feedback Interactivo (Micro-animaciones y UX)

- [x] 3.1 Revisar los recursos principales (TeacherResource, UserResource) para inyectar notificaciones de éxito (`Notification::make()->success()`) post-guardado, y verificar creando un registro de prueba.
- [x] 3.2 Verificar globalmente que todas las vistas cargan con el nuevo espaciado/tipografía sin conflictos de contraste.
