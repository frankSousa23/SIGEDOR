# Tareas de Implementación: Mejoras Secuenciales SIGEDOR

## 1. Fase 1: Formularios Inteligentes y Reactividad UX

- [x] 1.1 Implementar reactividad ->live() y ->afterStateUpdated() en TeacherResource para autocompletar nombre, apellido, correo, sede y área al elegir usuario y verificar en navegador
- [x] 1.2 Implementar selector dependiente en cascada para filtrar  rea_id y programa_id según el sede_id seleccionado y verificar comportamiento reactivo
- [x] 1.3 Implementar helper visual de elegibilidad de escalafón docente en CategoryResource y verificar cálculo de fechas

## 2. Fase 2: Widgets y Analítica en Dashboard

- [x] 2.1 Crear el widget de gráfico de dona TeacherDistributionChart en  pp/Filament/Widgets/ y verificar renderizado con datos de prueba
- [x] 2.2 Crear el widget de gráfico de barras SedeStatsChart para comparar volumen docente por sede y verificar que respete roles
- [x] 2.3 Registrar los nuevos widgets en AdminPanelProvider y verificar en el escritorio principal

## 3. Fase 3: Exportación Masiva a Hojas de Cálculo

- [x] 3.1 Añadir acción masiva de exportación a CSV en TeacherResource mediante streaming de respuesta y verificar descarga de archivo
- [x] 3.2 Añadir acción masiva de exportación a CSV en ReportResource y verificar columnas exportadas

## 4. Fase 4: Endpoints API RESTful y Documentación OpenAPI

- [x] 4.1 Crear  pp/Http/Controllers/Api/TeacherApiController.php con endpoint GET /api/v1/teachers y anotaciones @OA
- [x] 4.2 Crear  pp/Http/Controllers/Api/ReportApiController.php con endpoint GET /api/v1/reports y anotaciones @OA
- [x] 4.3 Registrar rutas en outes/api.php y ejecutar php artisan l5-swagger:generate para verificar generación del JSON y vista Swagger

## 5. Fase 5: Verificación y Testing Automatizado

- [x] 5.1 Crear pruebas de integración en 	ests/Feature/ApiTest.php para validar los endpoints y respuestas JSON
- [x] 5.2 Ejecutar la suite completa de Pest ( endor/bin/pest) y verificar que todas las pruebas pasen al 100%
