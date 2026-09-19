import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';

export default defineConfig({
  plugins: [
    react(),
    {
      name: 'sigedor-api-mock',
      configureServer(server) {
        server.middlewares.use((req, res, next) => {
          if (req.url === '/api/ping') {
            res.setHeader('Content-Type', 'application/json');
            res.end(JSON.stringify({
              status: 'pong',
              timestamp: new Date().toISOString(),
              version: '1.0.0'
            }));
            return;
          }
          if (req.url?.startsWith('/api/v1/teachers')) {
            res.setHeader('Content-Type', 'application/json');
            res.end(JSON.stringify({
              status: 'success',
              data: {
                current_page: 1,
                data: [
                  {
                    id: 1,
                    cdi: '14987654',
                    name: 'Prof. Carlos',
                    surName: 'Mendoza',
                    genre: 'M',
                    email: 'docente@sigedor.com',
                    sede: { id: 1, nombre: 'Sede Central/San Juan de los Morros' },
                    area: { id: 1, nombre: 'Ingeniería de sistemas' },
                    category: { current_category: 'Titular' },
                    dedication: { name: 'Dedicación Exclusiva', type: 'EX', hours: 38 }
                  },
                  {
                    id: 2,
                    cdi: '12345678',
                    name: 'Dra. Carmen Alicia',
                    surName: 'Silva',
                    genre: 'F',
                    email: 'areamanager.salud@sigedor.com',
                    sede: { id: 1, nombre: 'Sede Central/San Juan de los Morros' },
                    area: { id: 2, nombre: 'Ciencias de la salud' },
                    category: { current_category: 'Titular' },
                    dedication: { name: 'Dedicación Exclusiva', type: 'EX', hours: 40 }
                  }
                ],
                first_page_url: '/api/v1/teachers?page=1',
                from: 1,
                last_page: 1,
                per_page: 15,
                to: 2,
                total: 2
              }
            }));
            return;
          }
          if (req.url?.startsWith('/api/v1/reports')) {
            res.setHeader('Content-Type', 'application/json');
            res.end(JSON.stringify({
              status: 'success',
              data: {
                current_page: 1,
                data: [
                  {
                    id: 1,
                    verification_code: 'UNERG-REP-2026-0001',
                    memoNumber: 'MEMO-UNERG-001/2026',
                    status: 'issued',
                    typeReport: 'Constancia de Trabajo',
                    created_at: '2026-09-18 10:30:00',
                    teacher: {
                      id: 1,
                      cdi: '14987654',
                      name: 'Prof. Carlos Mendoza'
                    },
                    sede: { id: 1, nombre: 'Sede Central/San Juan de los Morros' }
                  }
                ],
                first_page_url: '/api/v1/reports?page=1',
                from: 1,
                last_page: 1,
                per_page: 15,
                to: 1,
                total: 1
              }
            }));
            return;
          }
          next();
        });
      }
    }
  ],
  server: {
    host: '0.0.0.0',
    port: 3000,
  },
});
