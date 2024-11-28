<html><head><base href="/" />
    <meta charset="UTF-8">
    <title>404 - Página no encontrada - SIGEDOR</title>
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
        justify-content: center;
        color: var(--text);
        padding: 2rem;
      }

      .error-container {
        text-align: center;
        max-width: 600px;
        background: var(--primary);
        padding: 3rem;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
      }

      .error-code {
        font-size: 8rem;
        font-weight: bold;
        color: var(--accent);
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        margin-bottom: 1rem;
        animation: pulse 2s infinite;
      }

      @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
      }

      .error-message {
        font-size: 1.5rem;
        margin-bottom: 2rem;
      }

      .error-description {
        font-size: 1.1rem;
        margin-bottom: 2rem;
        opacity: 0.8;
        line-height: 1.6;
      }

      .home-link {
        display: inline-block;
        padding: 1rem 2rem;
        background: var(--accent);
        color: white;
        text-decoration: none;
        border-radius: 5px;
        font-weight: bold;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
      }

      .home-link:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
      }

      .error-icon {
        width: 120px;
        height: 120px;
        margin: 2rem auto;
        fill: var(--accent);
      }
    </style>
    </head>
    <body>
      <div class="error-container">
        <svg class="error-icon" viewBox="0 0 24 24">
          <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
        </svg>
        <div class="error-code">404</div>
        <h1 class="error-message">Página no encontrada</h1>
        <p class="error-description">
          Lo sentimos, la página que está buscando no existe o ha sido movida.
          Por favor, verifique la URL o regrese a la página principal de SIGEDOR.
        </p>
        <a href="/" class="home-link">Volver</a>
      </div>

      <script>
        window.livewire = new Livewire();
      </script>
    </body>
    </html>
