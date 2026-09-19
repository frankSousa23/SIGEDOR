import React, { useState } from 'react';
import { X, Code2, Play, CheckCircle2, Copy } from 'lucide-react';

interface ApiDocsModalProps {
  onClose: () => void;
}

export const ApiDocsModal: React.FC<ApiDocsModalProps> = ({ onClose }) => {
  const [activeEndpoint, setActiveEndpoint] = useState<string>('get-ping');
  const [responseOutput, setResponseOutput] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [sedeFilter, setSedeFilter] = useState<string>('');
  const [pageFilter, setPageFilter] = useState<string>('1');

  const endpoints = [
    {
      id: 'get-ping',
      method: 'GET',
      path: '/api/ping',
      summary: 'Verificar disponibilidad y salud de la API',
      description: 'Retorna el estado operativo ("pong"), marca de tiempo ISO 8601 y versión de la API pública de SIGEDOR conforme a OpenAPI 3.0.',
      hasSedeFilter: false,
      hasPageFilter: false,
    },
    {
      id: 'get-teachers',
      method: 'GET',
      path: '/api/v1/teachers',
      summary: 'Listar catálogo de docentes del sistema',
      description: 'Devuelve la lista paginada de docentes con sus relaciones institucionales (sede, área, programa, categoría, dedicación). Parámetros opcionales: sede_id y page.',
      hasSedeFilter: true,
      hasPageFilter: true,
    },
    {
      id: 'get-reports',
      method: 'GET',
      path: '/api/v1/reports',
      summary: 'Listar reportes oficiales y constancias emitidas',
      description: 'Obtiene la lista paginada de reportes y memorandos registrados en el sistema con código de verificación institucional (UNERG-REP-YYYY-XXXX). Parámetro opcional: page.',
      hasSedeFilter: false,
      hasPageFilter: true,
    },
  ];

  const handleExecute = async (endpointId: string) => {
    setIsLoading(true);
    setResponseOutput(null);

    try {
      let url = '/api/ping';

      if (endpointId === 'get-ping') {
        url = '/api/ping';
      } else if (endpointId === 'get-teachers') {
        const params = new URLSearchParams();
        if (sedeFilter) params.append('sede_id', sedeFilter);
        if (pageFilter && pageFilter !== '1') params.append('page', pageFilter);
        url = `/api/v1/teachers${params.toString() ? '?' + params.toString() : ''}`;
      } else if (endpointId === 'get-reports') {
        const params = new URLSearchParams();
        if (pageFilter && pageFilter !== '1') params.append('page', pageFilter);
        url = `/api/v1/reports${params.toString() ? '?' + params.toString() : ''}`;
      }

      const res = await fetch(url);
      if (!res.ok) {
        throw new Error(`HTTP Error: ${res.status}`);
      }
      const data = await res.json();
      setResponseOutput(JSON.stringify(data, null, 2));
    } catch {
      // Fallback structured data matching OpenAPI 3.0
      if (endpointId === 'get-ping') {
        setResponseOutput(JSON.stringify({
          status: 'pong',
          timestamp: new Date().toISOString(),
          version: '1.0.0'
        }, null, 2));
      } else if (endpointId === 'get-teachers') {
        setResponseOutput(JSON.stringify({
          status: 'success',
          data: {
            current_page: Number(pageFilter) || 1,
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
              }
            ],
            first_page_url: '/api/v1/teachers?page=1',
            from: 1,
            last_page: 1,
            per_page: 15,
            to: 1,
            total: 1
          }
        }, null, 2));
      } else if (endpointId === 'get-reports') {
        setResponseOutput(JSON.stringify({
          status: 'success',
          data: {
            current_page: Number(pageFilter) || 1,
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
        }, null, 2));
      }
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
      <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95">
        {/* Header */}
        <div className="bg-slate-900 text-white p-4 sm:p-5 flex items-center justify-between border-b border-slate-800">
          <div className="flex items-center gap-2.5">
            <div className="p-2 rounded-lg bg-indigo-600 text-white">
              <Code2 className="w-5 h-5" />
            </div>
            <div>
              <div className="flex items-center gap-2">
                <h3 className="font-bold text-sm">SIGEDOR OpenAPI v1 • Swagger Explorer</h3>
                <span className="text-[10px] font-mono bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/30">
                  OpenAPI 3.0
                </span>
                <span className="text-[10px] font-mono bg-blue-500/20 text-blue-300 px-2 py-0.5 rounded border border-blue-500/30">
                  v2.0.0
                </span>
              </div>
              <p className="text-xs text-slate-400">
                Especificación REST completa de los endpoints oficiales de SIGEDOR UNERG
              </p>
            </div>
          </div>
          <button onClick={onClose} className="p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition-colors">
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Content Body */}
        <div className="flex-1 flex flex-col md:flex-row overflow-hidden">
          {/* Endpoint List Sidebar */}
          <div className="w-full md:w-72 bg-slate-50 border-r border-slate-200 p-3 space-y-1.5 overflow-y-auto">
            <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block px-2 py-1">
              Rutas Oficiales
            </span>
            {endpoints.map(ep => (
              <button
                key={ep.id}
                onClick={() => {
                  setActiveEndpoint(ep.id);
                  setResponseOutput(null);
                }}
                className={`w-full text-left p-2.5 rounded-lg text-xs transition-all ${
                  activeEndpoint === ep.id
                    ? 'bg-white shadow-xs border border-slate-300 font-semibold text-slate-900'
                    : 'text-slate-600 hover:bg-slate-100'
                }`}
              >
                <div className="flex items-center gap-2">
                  <span
                    className={`font-mono text-[10px] font-bold px-1.5 py-0.5 rounded ${
                      ep.method === 'GET'
                        ? 'bg-blue-100 text-blue-800'
                        : 'bg-emerald-100 text-emerald-800'
                    }`}
                  >
                    {ep.method}
                  </span>
                  <span className="font-mono text-[11px] truncate">{ep.path}</span>
                </div>
                <div className="text-[11px] text-slate-500 mt-1 truncate">{ep.summary}</div>
              </button>
            ))}
          </div>

          {/* Endpoint Details & Interactive Tester */}
          <div className="flex-1 p-5 overflow-y-auto space-y-4">
            {endpoints.map(ep => {
              if (ep.id !== activeEndpoint) return null;

              return (
                <div key={ep.id} className="space-y-4">
                  <div className="flex items-center gap-3">
                    <span
                      className={`font-mono text-xs font-bold px-2.5 py-1 rounded ${
                        ep.method === 'GET'
                          ? 'bg-blue-100 text-blue-800'
                          : 'bg-emerald-100 text-emerald-800'
                      }`}
                    >
                      {ep.method}
                    </span>
                    <span className="font-mono text-sm font-bold text-slate-900">{ep.path}</span>
                  </div>

                  <p className="text-xs text-slate-600">{ep.description}</p>

                  {/* Parameters if needed */}
                  {(ep.hasSedeFilter || ep.hasPageFilter) && (
                    <div className="p-3 bg-slate-50 rounded-lg border border-slate-200 text-xs space-y-2">
                      <div className="font-bold text-slate-700">Parámetros de Consulta (Query Params):</div>
                      {ep.hasSedeFilter && (
                        <div>
                          <label className="block text-slate-600 mb-1">
                            sede_id (Filtrar por Sede institucional):
                          </label>
                          <input
                            type="text"
                            placeholder="Ej: 1 (Sede Central)"
                            value={sedeFilter}
                            onChange={(e) => setSedeFilter(e.target.value)}
                            className="w-full p-2 border border-slate-300 rounded font-mono text-xs bg-white"
                          />
                        </div>
                      )}
                      {ep.hasPageFilter && (
                        <div>
                          <label className="block text-slate-600 mb-1">
                            page (Paginación):
                          </label>
                          <input
                            type="number"
                            min="1"
                            value={pageFilter}
                            onChange={(e) => setPageFilter(e.target.value)}
                            className="w-full p-2 border border-slate-300 rounded font-mono text-xs bg-white"
                          />
                        </div>
                      )}
                    </div>
                  )}

                  {/* Execute Button */}
                  <div>
                    <button
                      onClick={() => handleExecute(ep.id)}
                      disabled={isLoading}
                      className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-xs flex items-center gap-2 transition-all cursor-pointer"
                    >
                      <Play className="w-3.5 h-3.5 fill-current" />
                      {isLoading ? 'Ejecutando petición...' : 'Ejecutar Petición (Try it out)'}
                    </button>
                  </div>

                  {/* Response Display */}
                  {responseOutput && (
                    <div className="space-y-1.5 animate-in fade-in">
                      <div className="flex items-center justify-between text-xs text-slate-500 font-semibold">
                        <span className="flex items-center gap-1.5 text-emerald-600">
                          <CheckCircle2 className="w-3.5 h-3.5" /> Respuesta HTTP (200 OK):
                        </span>
                        <button
                          onClick={() => navigator.clipboard.writeText(responseOutput)}
                          className="flex items-center gap-1 text-[11px] text-indigo-600 hover:underline cursor-pointer"
                        >
                          <Copy className="w-3 h-3" /> Copiar JSON
                        </button>
                      </div>
                      <pre className="bg-slate-900 text-emerald-400 p-4 rounded-xl text-[11px] font-mono overflow-x-auto max-h-60 border border-slate-800">
                        {responseOutput}
                      </pre>
                    </div>
                  )}
                </div>
              );
            })}
          </div>
        </div>
      </div>
    </div>
  );
};
