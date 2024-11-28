<html><head><base href="/" />
    <meta charset="UTF-8">
    <title>SIGEDOR - Sistema de Gestión para Docentes Universitarios</title>
    <!-- Agregando Livewire -->
    <script src="https://cdn.jsdelivr.net/gh/livewire/livewire@v2.x.x/dist/livewire.js"></script>
    <link href="https://cdn.jsdelivr.net/gh/livewire/livewire@v2.x.x/dist/livewire.css" rel="stylesheet" />

    <style>
      :root {
        --primary: #1a1a1a;
        --accent: #3498db;
        --light: #f8f9fa;
        --dark: #121212;
        --text: #e0e0e0;
      }

      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: var(--dark);
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        align-items: center;
        color: var(--text);
      }

      .hero {
        background: var(--primary);
        color: var(--text);
        width: 100%;
        padding: 4rem 2rem;
        text-align: center;
      }

      .hero h1 {
        font-size: 2.5rem;
        margin-bottom: 1rem;
      }

      .hero p {
        font-size: 1.2rem;
        max-width: 800px;
        margin: 0 auto;
        line-height: 1.6;
      }

      .dashboard-link {
        display: inline-block;
        margin-top: 2rem;
        padding: 1rem 2rem;
        background: var(--accent);
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .dashboard-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
      }

      .image-placeholder {
        width: 100%;
        height: 300px;
        background: var(--primary);
        margin: 2rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--text);
        font-style: italic;
      }

      .features {
        display: flex;
        flex-direction: column;
        gap: 2rem;
        padding: 4rem 2rem;
        max-width: 800px;
        width: 100%;
      }

      .feature {
        padding: 2rem;
        background: var(--primary);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        transition: transform 0.3s ease;
      }

      .feature:hover {
        transform: translateX(10px);
      }

      .feature svg {
        width: 50px;
        height: 50px;
        margin-bottom: 1rem;
        fill: var(--accent);
      }

      .feature h3 {
        color: var(--text);
        margin-bottom: 1rem;
      }

      .feature p {
        color: var(--text);
        line-height: 1.6;
        opacity: 0.8;
      }
    </style>
    </head>
    <body>
      <div>
        <section class="hero">
          <h1>SIGEDOR</h1>
          <h2>Sistema de Gestión para Docentes Universitarios</h2>
          <p>Su plataforma integral para la gestión de carrera docente, enfocada en los procesos de ascenso y permanencia en la institución.</p>
          <a href="/dashboard" class="dashboard-link">Ir al Escritorio</a>
        </section>

        <div class="features">
          <div class="feature">
            <svg viewBox="0 0 24 24">
              <path d="M19 3h-4.18C14.4 1.84 13.3 1 12 1c-1.3 0-2.4.84-2.82 2H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 0c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm-2 14l-4-4 1.41-1.41L10 14.17l6.59-6.59L18 9l-8 8z"/>
            </svg>
            <h3>Ascensos Universitarios</h3>
            <p>Gestione su proceso de ascenso académico de manera eficiente. Consulte requisitos, cargue documentación y realice seguimiento a sus solicitudes en tiempo real.</p>
          </div>

          <div class="feature">
            <svg viewBox="0 0 24 24">
              <path d="M20 6h-4V4c0-1.1-.9-2-2-2h-4c-1.1 0-2 .9-2 2v2H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V8c0-1.1-.9-2-2-2zm-8-2h4v2h-4V4zm0 12H8v-2h4v2zm6 0h-4v-2h4v2zm0-4H8v-2h10v2z"/>
            </svg>
            <h3>Docentes Fijos</h3>
            <p>Información detallada sobre beneficios, responsabilidades y oportunidades disponibles para docentes con cargo fijo en la institución.</p>
          </div>

          <div class="feature">
            <svg viewBox="0 0 24 24">
              <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H5V5h14v14zm-7-2h2V7h-4v2h2z"/>
            </svg>
            <h3>Reportes y Estadísticas</h3>
            <p>Acceda a informes detallados sobre su trayectoria académica, publicaciones y logros profesionales dentro de la institución.</p>
          </div>
        </div>
      </div>

      <script>
        // Inicialización de Livewire
        window.livewire = new Livewire();
      </script>
    </body>
    </html>
