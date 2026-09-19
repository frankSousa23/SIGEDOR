import React, { useState } from 'react';
import { useData } from '../context/DataContext';
import { useAuth } from '../context/AuthContext';
import { FileText, Plus, Search, Printer, ShieldCheck, CheckCircle2, X } from 'lucide-react';
import { Report } from '../types';

interface ReportManagementProps {
  onViewReport: (report: Report) => void;
}

export const ReportManagement: React.FC<ReportManagementProps> = ({ onViewReport }) => {
  const { filteredReports, teachers, generateReport } = useData();
  const { isSuperAdmin, isAreaManager } = useAuth();

  const [search, setSearch] = useState('');
  const [filterType, setFilterType] = useState('');
  const [showGenerateModal, setShowGenerateModal] = useState(false);

  // Form state
  const [selectedTeacherCdi, setSelectedTeacherCdi] = useState(teachers[0]?.cdi || '');
  const [reportType, setReportType] = useState<Report['typeReport']>('Constancia de Trabajo');
  const [memoCustom, setMemoCustom] = useState('');

  const filtered = filteredReports.filter(r => {
    const matchesSearch = 
      r.teacher_name.toLowerCase().includes(search.toLowerCase()) ||
      r.teacher_cdi.includes(search) ||
      r.verification_code.toLowerCase().includes(search.toLowerCase()) ||
      r.memoNumber.toLowerCase().includes(search.toLowerCase());

    const matchesType = !filterType || r.typeReport === filterType;
    return matchesSearch && matchesType;
  });

  const handleGenerate = (e: React.FormEvent) => {
    e.preventDefault();
    const rep = generateReport({
      teacher_cdi: selectedTeacherCdi,
      typeReport: reportType,
      memoNumber: memoCustom || undefined,
    });
    setShowGenerateModal(false);
    onViewReport(rep);
  };

  return (
    <div className="space-y-4">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
          <h2 className="text-lg sm:text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <FileText className="w-5 h-5 text-indigo-600 flex-shrink-0" />
            <span>Reportes Oficiales y Certificaciones</span>
          </h2>
          <p className="text-xs text-slate-500 mt-0.5">
            Generador institucional de constancias de trabajo, expedientes curriculares y memorandos oficiales
          </p>
        </div>

        {(isSuperAdmin || isAreaManager) && (
          <button
            onClick={() => setShowGenerateModal(true)}
            className="w-full sm:w-auto px-4 py-2 text-xs font-bold text-white bg-unerg-blue hover:bg-unerg-blue-light rounded-lg shadow-xs flex items-center justify-center gap-1.5 transition-all flex-shrink-0"
          >
            <Plus className="w-4 h-4" /> Emitir Nuevo Documento
          </button>
        )}
      </div>

      {/* Filter and Search Bar */}
      <div className="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center">
        <div className="relative flex-1 min-w-[200px]">
          <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Buscar por código de verificación, docente o memorando..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-unerg-blue"
          />
        </div>

        <select
          value={filterType}
          onChange={(e) => setFilterType(e.target.value)}
          className="text-xs p-2 bg-slate-50 border border-slate-300 rounded-lg text-slate-700 font-medium"
        >
          <option value="">Todos los Tipos de Documento</option>
          <option value="Constancia de Trabajo">Constancia de Trabajo</option>
          <option value="Expediente Curricular Individual">Expediente Curricular Individual</option>
          <option value="Memorando de Carga y Dedicación">Memorando de Carga y Dedicación</option>
          <option value="Reporte Institucional de Escalafón">Reporte Institucional de Escalafón</option>
        </select>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-slate-100/70 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px] tracking-wider">
              <tr>
                <th className="py-3 px-4">Documento / Tipo</th>
                <th className="py-3 px-4">Código de Autenticidad</th>
                <th className="py-3 px-4">Docente Titular</th>
                <th className="py-3 px-4">Nº Memorando</th>
                <th className="py-3 px-4">Fecha de Emisión</th>
                <th className="py-3 px-4 text-right">Acción</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {filtered.map(report => (
                <tr key={report.id} className="hover:bg-slate-50/80 transition-colors">
                  <td className="py-3 px-4">
                    <div className="font-bold text-slate-900">{report.typeReport}</div>
                    <span className="text-[10px] text-emerald-700 font-semibold flex items-center gap-1">
                      <ShieldCheck className="w-3.5 h-3.5" /> Autenticado
                    </span>
                  </td>

                  <td className="py-3 px-4">
                    <span className="font-mono text-xs font-bold text-blue-700 bg-blue-50 px-2 py-0.5 rounded border border-blue-200">
                      {report.verification_code}
                    </span>
                  </td>

                  <td className="py-3 px-4">
                    <div className="font-semibold text-slate-800">{report.teacher_name}</div>
                    <div className="text-[11px] text-slate-500 font-mono">V-{report.teacher_cdi}</div>
                  </td>

                  <td className="py-3 px-4 font-mono text-slate-700 font-medium">
                    {report.memoNumber}
                  </td>

                  <td className="py-3 px-4 text-slate-500 text-[11px]">
                    {report.created_at}
                  </td>

                  <td className="py-3 px-4 text-right">
                    <button
                      onClick={() => onViewReport(report)}
                      className="px-3 py-1.5 bg-unerg-blue hover:bg-unerg-blue-light text-white font-semibold rounded-md shadow-xs transition-colors inline-flex items-center gap-1 text-[11px]"
                    >
                      <Printer className="w-3.5 h-3.5" /> Ver / Imprimir
                    </button>
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </div>

      {/* Modal Emitir Documento */}
      {showGenerateModal && (
        <div className="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95">
            <div className="bg-unerg-blue text-white p-4 flex items-center justify-between">
              <h3 className="font-bold text-sm flex items-center gap-2">
                <FileText className="w-4 h-4 text-amber-300" />
                Emitir Documento Académico Oficial
              </h3>
              <button onClick={() => setShowGenerateModal(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleGenerate} className="p-6 space-y-4 text-xs">
              <div>
                <label className="block font-semibold text-slate-700 mb-1">Docente:</label>
                <select
                  value={selectedTeacherCdi}
                  onChange={(e) => setSelectedTeacherCdi(e.target.value)}
                  className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                >
                  {teachers.map(t => (
                    <option key={t.cdi} value={t.cdi}>
                      {t.name} {t.surName} (V-{t.cdi}) — {t.area_nombre}
                    </option>
                  ))}
                </select>
              </div>

              <div>
                <label className="block font-semibold text-slate-700 mb-1">Tipo de Certificación:</label>
                <select
                  value={reportType}
                  onChange={(e) => setReportType(e.target.value as Report['typeReport'])}
                  className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                >
                  <option value="Constancia de Trabajo">Constancia de Trabajo Oficial</option>
                  <option value="Expediente Curricular Individual">Expediente Curricular Individual</option>
                  <option value="Memorando de Carga y Dedicación">Memorando de Carga y Dedicación</option>
                  <option value="Reporte Institucional de Escalafón">Reporte Institucional de Escalafón</option>
                </select>
              </div>

              <div>
                <label className="block font-semibold text-slate-700 mb-1">Número de Memorando (Opcional):</label>
                <input
                  type="text"
                  placeholder="Se generará automáticamente si se deja vacío"
                  value={memoCustom}
                  onChange={(e) => setMemoCustom(e.target.value)}
                  className="w-full p-2 border border-slate-300 rounded-lg font-mono"
                />
              </div>

              <div className="pt-4 border-t border-slate-200 flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setShowGenerateModal(false)}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-unerg-blue hover:bg-unerg-blue-light text-white font-bold rounded-lg shadow-sm"
                >
                  Generar y Visualizar Documento
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
