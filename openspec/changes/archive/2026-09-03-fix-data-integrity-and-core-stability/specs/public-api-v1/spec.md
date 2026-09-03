## ADDED Requirements

### Requirement: Endpoint de Verificación de Salud (Health Check)
El sistema DEBE proveer un endpoint público GET /api/ping que permita verificar la disponibilidad operativa y conectividad de la API, retornando estado 200 y mensaje de confirmación de salud.

#### Scenario: Petición de chequeo de salud
- **WHEN** un cliente HTTP o monitor de disponibilidad envía una solicitud GET a /api/ping
- **THEN** el sistema retorna código HTTP 200 con un objeto JSON indicando `status: "pong"` o confirmación de operatividad

### Requirement: Limitación de Tasa de Peticiones (Rate Limiting)
Los endpoints públicos de la API DEBEN estar protegidos mediante middleware de limitación de tasa (`throttle`) para prevenir abusos, denegación de servicio o scraping automatizado no autorizado.

#### Scenario: Exceso de solicitudes en ventana de tiempo
- **WHEN** un cliente supera el umbral permitido de peticiones por minuto en las rutas de la API
- **THEN** el sistema bloquea temporalmente las peticiones subsiguientes respondiendo con código HTTP 429 Too Many Requests
