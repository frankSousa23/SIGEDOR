import React, { useState } from 'react';
import { X, Code2, Play, CheckCircle2, Copy } from 'lucide-react';

interface ApiDocsModalProps {
  onClose: () => void;
}

export const ApiDocsModal: React.FC<ApiDocsModalProps> = ({ onClose }) => {
  const [activeEndpoint, setActiveEndpoint] = useState<string>('get-teachers');
  const [responseOutput, setResponseOutput] = useState<string | null>(null);
  const [isLoading, setIsLoading] = useState(false);
  const [testCdi, setTestCdi] = useState('14987654');
  const [testVerifyCode, setTestVerifyCode] = useState('UNERG-REP-2025-0101');

  const endpoints = [
    {
      id: 'get-teachers',
      method: 'GET',
      path: '/api/v1/teachers',
      summary: 'Listar docentes con filtros y paginación',
      description: 'Devuelve la lista paginada de expedientes docentes con soporte para búsqueda y filtrado por sede.',
    },
    {
      id: 'get-teacher-cdi',
      method: 'GET',
      path: '/api/v1/teachers/:cdi',
      summary: 'Obtener expediente 360° de docente',
      description: 'Retorna el expediente completo incluyendo datos de escalafón, dedicación horaria, cátedras y permisos.',
    },
    {
      id: 'get-reports',
      method: 'GET',
      path: '/api/v1/reports',
      summary: 'Listar reportes oficiales emitidos',
      description: 'Obtiene los memorandos y constancias oficiales generados por el sistema con código de verificación.',
    },
    {
      id: 'post-verify',
      method: 'POST',
      path: '/api/v1/reports/verify',
      summary: 'Verificar autenticidad de documento oficial',
      description: 'Comprueba la validez de un código de verificación institucional emitido por la UNERG.',
    },
    {
      id: 'get-health',
      method: 'GET',
      path: '/api/health',
      summary: 'Healthcheck del servidor SIGEDOR',
      description: 'Verifica el estado operativo del backend en Node.js.',
    },
  ];

  const handleExecute = async (endpointId: string) => {
    setIsLoading(true);
    setResponseOutput(null);

    try {
      let url = '/api/v1/teachers';
      let options: RequestInit = { method: 'GET' };

      if (endpointId === 'get-teachers') {
        url = '/api/v1/teachers?limit=5';
      } else if (endpointId === 'get-teacher-cdi') {
        url = `/api/v1/teachers/${testCdi}`;
      } else if (endpointId === 'get-reports') {
        url = '/api/v1/reports';
      } else if (endpointId === 'post-verify') {
        url = '/api/v1/reports/verify';
        options = {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ verification_code: testVerifyCode }),
        };
      } else if (endpointId === 'get-health') {
        url = '/api/health';
      }

      const res = await fetch(url, options);
      const data = await res.json();
      setResponseOutput(JSON.stringify(data, null, 2));
    } catch (err: any) {
      setResponseOutput(JSON.stringify({ error: err.message }, null, 2));
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
      <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95">
        {/* Header */}
        <div className="bg-slate-900 text-white p-5 flex items-center justify-between border-b border-slate-800">
          <div className="flex items-center gap-2.5">
            <div className="p-2 rounded-lg bg-indigo-600 text-white">
              <Code2 className="w-5 h-5" />
            </div>
            <div>
              <div className="flex items-center gap-2">
                <h3 className="font-bold text-sm">SIGEDOR OpenAPI v1 • Swagger Explorer</h3>
                <span className="text-[10px] font-mono bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/30">
                  v2.0.0
                </span>
              </div>
              <p className="text-xs text-slate-400">
                Especificación REST completa para integración con sistemas universitarios UNERG
              </p>
            </div>
          </div>
          <button onClick={onClose} className="text-slate-400 hover:text-white">
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Content Body */}
        <div className="flex-1 flex flex-col md:flex-row overflow-hidden">
          {/* Endpoint List Sidebar */}
          <div className="w-full md:w-72 bg-slate-50 border-r border-slate-200 p-3 space-y-1.5 overflow-y-auto">
            <span className="text-[10px] font-bold text-slate-400 uppercase tracking-wider block px-2 py-1">
              Rutas Disponibles
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
                  {ep.id === 'get-teacher-cdi' && (
                    <div className="p-3 bg-slate-50 rounded-lg border border-slate-200 text-xs">
                      <label className="block font-semibold text-slate-700 mb-1">
                        Parámetro :cdi (Cédula del Docente):
                      </label>
                      <input
                        type="text"
                        value={testCdi}
                        onChange={(e) => setTestCdi(e.target.value)}
                        className="w-full p-2 border border-slate-300 rounded font-mono text-xs"
                      />
                    </div>
                  )}

                  {ep.id === 'post-verify' && (
                    <div className="p-3 bg-slate-50 rounded-lg border border-slate-200 text-xs">
                      <label className="block font-semibold text-slate-700 mb-1">
                        Código de Verificación (Body: verification_code):
                      </label>
                      <input
                        type="text"
                        value={testVerifyCode}
                        onChange={(e) => setTestVerifyCode(e.target.value)}
                        className="w-full p-2 border border-slate-300 rounded font-mono text-xs"
                      />
                    </div>
                  )}

                  {/* Execute Button */}
                  <div>
                    <button
                      onClick={() => handleExecute(ep.id)}
                      disabled={isLoading}
                      className="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold rounded-lg shadow-sm flex items-center gap-2 transition-all"
                    >
                      <Play className="w-3.5 h-3.5 fill-current" />
                      {isLoading ? 'Ejecutando petición...' : 'Probar endpoint (Try it out)'}
                    </button>
                  </div>

                  {/* Response Display */}
                  {responseOutput && (
                    <div className="space-y-1.5 animate-in fade-in">
                      <div className="flex items-center justify-between text-xs text-slate-500 font-semibold">
                        <span>Respuesta HTTP (200 OK):</span>
                        <button
                          onClick={() => navigator.clipboard.writeText(responseOutput)}
                          className="flex items-center gap-1 text-[11px] text-indigo-600 hover:underline"
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
