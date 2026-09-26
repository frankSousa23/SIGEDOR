import { defineConfig } from 'vite';
import react from '@vitejs/plugin-react';
import fs from 'fs';
import path from 'path';

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
              version: '2.0.0',
              institution: 'Universidad Nacional Experimental Rómulo Gallegos (UNERG)'
            }));
            return;
          }

          if (req.url === '/api/v1/openapi.json' || req.url === '/openapi.json' || req.url === '/swagger.json') {
            res.setHeader('Content-Type', 'application/json');
            try {
              const spec = fs.readFileSync(path.resolve('./public/openapi.json'), 'utf-8');
              res.end(spec);
            } catch (e) {
              res.end(JSON.stringify({ status: 'error', message: 'OpenAPI spec file not found' }));
            }
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
                    cdi: '10101001',
                    name: 'Oliver',
                    surName: 'Peña',
                    genre: 'M',
                    email: 'oliver.pena@sigedor.com',
                    sede: { id: 1, nombre: 'Sede Central/San Juan de los Morros' },
                    area: { id: 1, nombre: 'Ingeniería de sistemas' },
                    category: { current_category: 'Asociado' },
                    dedication: { name: 'Tiempo Completo', type: 'TC', hours: 30 }
                  },
                  {
                    id: 2,
                    cdi: '10101002',
                    name: 'Raquel',
                    surName: 'Giménez',
                    genre: 'F',
                    email: 'raquel.gimenez@sigedor.com',
                    sede: { id: 1, nombre: 'Sede Central/San Juan de los Morros' },
                    area: { id: 2, nombre: 'Ciencias de la salud' },
                    category: { current_category: 'Titular' },
                    dedication: { name: 'Exclusiva', type: 'DE', hours: 36 }
                  },
                  {
                    id: 3,
                    cdi: '10101003',
                    name: 'Martín',
                    surName: 'Vera',
                    genre: 'M',
                    email: 'martin.vera@sigedor.com',
                    sede: { id: 2, nombre: 'Calabozo/Guárico' },
                    area: { id: 3, nombre: 'Ingeniería agronómica' },
                    category: { current_category: 'Titular' },
                    dedication: { name: 'Exclusiva', type: 'DE', hours: 36 }
                  }
                ],
                first_page_url: '/api/v1/teachers?page=1',
                from: 1,
                last_page: 2,
                per_page: 15,
                to: 3,
                total: 30
              }
            }));
            return;
          }

          if (req.url?.startsWith('/api/v1/categories')) {
            res.setHeader('Content-Type', 'application/json');
            res.end(JSON.stringify({
              status: 'success',
              data: [
                { id: 1, teacher_cdi: '10101001', current_category: 'Asociado', preTitle: 'Ingeniero en Informática', lastTitle: 'MSc. en Computación' },
                { id: 2, teacher_cdi: '10101002', current_category: 'Titular', preTitle: 'Médico Cirujano', lastTitle: 'Dra. en Ciencias Médicas' },
                { id: 3, teacher_cdi: '10101003', current_category: 'Titular', preTitle: 'Ingeniero Agrónomo', lastTitle: 'Dr. en Biotecnología' }
              ]
            }));
            return;
          }

          if (req.url?.startsWith('/api/v1/dedications')) {
            res.setHeader('Content-Type', 'application/json');
            res.end(JSON.stringify({
              status: 'success',
              data: [
                { id: 1, teacher_cdi: '10101001', name: 'Tiempo Completo', hours: 30, denotation: '30 TC' },
                { id: 2, teacher_cdi: '10101002', name: 'Exclusiva', hours: 36, denotation: '35-36 DE' },
                { id: 3, teacher_cdi: '10101006', name: 'Tiempo Convencional', hours: 6, denotation: '2-7 TCV' },
                { id: 4, teacher_cdi: '10101005', name: 'Medio Tiempo', hours: 18, denotation: '18 MT' }
              ]
            }));
            return;
          }

          if (req.url?.startsWith('/api/v1/sites')) {
            res.setHeader('Content-Type', 'application/json');
            res.end(JSON.stringify({
              status: 'success',
              data: [
                { id: 1, teacher_cdi: '10101001', sede: 'Sede Central/San Juan de los Morros', info: 'Cátedra de Algoritmos', uc: 4, weekHours: 6 },
                { id: 2, teacher_cdi: '10101002', sede: 'Sede Central/San Juan de los Morros', info: 'Cátedra de Fisiopatología', uc: 5, weekHours: 8 },
                { id: 3, teacher_cdi: '10101003', sede: 'Calabozo/Guárico', info: 'Cátedra de Suelos y Fertilidad', uc: 4, weekHours: 8 }
              ]
            }));
            return;
          }

          if (req.url?.startsWith('/api/v1/permissions')) {
            res.setHeader('Content-Type', 'application/json');
            res.end(JSON.stringify({
              status: 'success',
              data: [
                { id: 1, teacher_cdi: '10101002', type: 'Año Sabático', status: 'Aprobado', semester: '2026-I / 2026-II' },
                { id: 2, teacher_cdi: '10101005', type: 'Comisión de Servicio', status: 'Aprobado', semester: '2026-I' },
                { id: 3, teacher_cdi: '10101009', type: 'Prórroga de Estudios', status: 'Pendiente', semester: '2026-I' }
              ]
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
                    memoNumber: 'MEMO-ING-2026-001',
                    status: 'Verificado',
                    typeReport: 'Constancia de Trabajo',
                    created_at: '2026-03-01 10:30:00',
                    teacher: { id: 1, cdi: '10101001', name: 'Oliver Peña' },
                    sede: { id: 1, nombre: 'Sede Central/San Juan de los Morros' }
                  },
                  {
                    id: 2,
                    verification_code: 'UNERG-REP-2026-0002',
                    memoNumber: 'MEMO-MED-2026-045',
                    status: 'Verificado',
                    typeReport: 'Expediente Curricular Individual',
                    created_at: '2026-02-18 14:15:00',
                    teacher: { id: 2, cdi: '10101002', name: 'Raquel Giménez' },
                    sede: { id: 1, nombre: 'Sede Central/San Juan de los Morros' }
                  }
                ],
                first_page_url: '/api/v1/reports?page=1',
                from: 1,
                last_page: 1,
                per_page: 15,
                to: 2,
                total: 16
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
    allowedHosts: true,
  },
  preview: {
    host: '0.0.0.0',
    port: 3000,
    allowedHosts: true,
  },
});
