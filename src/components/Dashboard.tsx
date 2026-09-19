import React from 'react';
import { useData } from '../context/DataContext';
import { useAuth } from '../context/AuthContext';
import { 
  Users, 
  Award, 
  Clock, 
  FileText, 
  CalendarDays, 
  ArrowUpRight, 
  CheckCircle2, 
  AlertCircle,
  Building2
} from 'lucide-react';
import { Report } from '../types';

interface DashboardProps {
  onSelectTeacher?: (cdi: string) => void;
  onViewReport: (report: Report) => void;
  onNavigate: (section: any) => void;
}

export const Dashboard: React.FC<DashboardProps> = ({ onSelectTeacher, onViewReport, onNavigate }) => {
  const { filteredTeachers, categories, dedications, sites, filteredReports, filteredPermissions } = useData();
  const { currentUser, isSuperAdmin, isAreaManager } = useAuth();

  // Compute metrics
  const totalTeachers = filteredTeachers.length;
  const activeTeachers = filteredTeachers.length; // all registered in filtered scope
  const activePermissions = filteredPermissions.filter(p => p.status === 'Aprobado').length;
  const pendingPermissions = filteredPermissions.filter(p => p.status === 'Pendiente').length;
  const totalReports = filteredReports.length;

  // Average weekly hours
  const teacherCdis = new Set(filteredTeachers.map(t => t.cdi));
  const scopedDedications = dedications.filter(d => teacherCdis.has(d.teacher_cdi));
  const avgHours = scopedDedications.length > 0
    ? (scopedDedications.reduce((acc, curr) => acc + curr.hours, 0) / scopedDedications.length).toFixed(1)
    : '0';

  // Escalafón breakdown
  const scopedCategories = categories.filter(c => teacherCdis.has(c.teacher_cdi));
  const categoryCounts = {
    Instructor: scopedCategories.filter(c => c.current_category === 'Instructor').length,
    Asistente: scopedCategories.filter(c => c.current_category === 'Asistente').length,
    Agregado: scopedCategories.filter(c => c.current_category === 'Agregado').length,
    Asociado: scopedCategories.filter(c => c.current_category === 'Asociado').length,
    Titular: scopedCategories.filter(c => c.current_category === 'Titular').length,
  };

  // Dedication breakdown
  const dedicationCounts = {
    'Tiempo Convencional': scopedDedications.filter(d => d.name === 'Tiempo Convencional').length,
    'Medio Tiempo': scopedDedications.filter(d => d.name === 'Medio Tiempo').length,
    'Tiempo Completo': scopedDedications.filter(d => d.name === 'Tiempo Completo').length,
    'Exclusiva': scopedDedications.filter(d => d.name === 'Exclusiva').length,
  };

  // Sede distribution
  const sedeMap = new Map<string, number>();
  filteredTeachers.forEach(t => {
    const count = sedeMap.get(t.sede_nombre) || 0;
    sedeMap.set(t.sede_nombre, count + 1);
  });
  const sedeCounts = Array.from(sedeMap.entries());

  return (
    <div className="space-y-6">
      {/* Welcome Banner */}
      <div className="bg-gradient-to-r from-unerg-blue via-unerg-blue-dark to-slate-900 rounded-xl p-4 sm:p-6 text-white shadow-xs border border-slate-700/50">
        <div className="flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div className="flex items-center gap-2 mb-1 flex-wrap">
              <span className="px-2 py-0.5 rounded text-[11px] font-bold bg-amber-400 text-slate-950 uppercase tracking-wide">
                Panel Institucional
              </span>
              <span className="text-xs text-slate-300">
                {isSuperAdmin ? 'Ámbito Global' : `Sede: ${currentUser.sede_nombre}`}
              </span>
            </div>
            <h1 className="text-xl sm:text-2xl font-bold tracking-tight">
              Bienvenido, {currentUser.name}
            </h1>
            <p className="text-slate-300 text-xs sm:text-sm mt-1">
              Sistema de Gestión Docente y Expedientes Académicos de la UNERG.
            </p>
          </div>
          <div className="flex flex-wrap sm:flex-nowrap gap-2">
            <button
              onClick={() => onNavigate('teachers')}
              className="flex-1 sm:flex-initial px-4 py-2 bg-amber-400 hover:bg-amber-300 text-slate-950 text-xs font-bold rounded-lg shadow-xs transition-all flex items-center justify-center gap-1.5"
            >
              <Users className="w-4 h-4" />
              <span>Ver Expedientes</span>
            </button>
            <button
              onClick={() => onNavigate('architecture')}
              className="flex-1 sm:flex-initial px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-xs font-semibold rounded-lg border border-white/20 transition-all flex items-center justify-center gap-1.5"
            >
              <FileText className="w-4 h-4 text-amber-300" />
              <span className="truncate">Visión y Láminas (PDF)</span>
            </button>
          </div>
        </div>
      </div>

      {/* KPI Stats Widgets (StatsOverview) */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {/* Total Docentes */}
        <div className="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              Docentes Adscritos
            </p>
            <h3 className="text-2xl font-extrabold text-slate-900 mt-1">{totalTeachers}</h3>
            <span className="text-xs text-emerald-600 font-medium flex items-center gap-1 mt-1">
              <CheckCircle2 className="w-3.5 h-3.5" /> 100% Expedientes Activos
            </span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-blue-50 text-unerg-blue flex items-center justify-center">
            <Users className="w-6 h-6" />
          </div>
        </div>

        {/* Dedicación Media */}
        <div className="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              Carga Media Semanal
            </p>
            <h3 className="text-2xl font-extrabold text-slate-900 mt-1">{avgHours} <span className="text-sm font-normal text-slate-500">hrs</span></h3>
            <span className="text-xs text-slate-500 font-medium flex items-center gap-1 mt-1">
              <Clock className="w-3.5 h-3.5 text-blue-500" /> Promedio ponderado
            </span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center">
            <Clock className="w-6 h-6" />
          </div>
        </div>

        {/* Documentos y Certificaciones */}
        <div className="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              Reportes y Memos
            </p>
            <h3 className="text-2xl font-extrabold text-slate-900 mt-1">{totalReports}</h3>
            <span className="text-xs text-emerald-600 font-medium flex items-center gap-1 mt-1">
              <CheckCircle2 className="w-3.5 h-3.5" /> Con código de verificación
            </span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-700 flex items-center justify-center">
            <FileText className="w-6 h-6" />
          </div>
        </div>

        {/* Licencias y Permisos */}
        <div className="bg-white rounded-xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p className="text-xs font-semibold text-slate-500 uppercase tracking-wider">
              Permisos en Curso
            </p>
            <h3 className="text-2xl font-extrabold text-slate-900 mt-1">{activePermissions}</h3>
            <span className="text-xs text-amber-600 font-medium flex items-center gap-1 mt-1">
              <AlertCircle className="w-3.5 h-3.5" /> {pendingPermissions} pendientes
            </span>
          </div>
          <div className="w-12 h-12 rounded-xl bg-purple-50 text-purple-700 flex items-center justify-center">
            <CalendarDays className="w-6 h-6" />
          </div>
        </div>
      </div>

      {/* Charts Grid */}
      <div className="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {/* Escalafón Docente Distribution (TeacherDistributionChart) */}
        <div className="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
          <div className="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
            <div>
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                <Award className="w-4 h-4 text-unerg-blue" />
                Distribución por Escalafón Universitario
              </h3>
              <p className="text-xs text-slate-500">Jerarquía docente según reglamento de ascensos UNERG</p>
            </div>
            <button 
              onClick={() => onNavigate('categories')}
              className="text-xs font-semibold text-unerg-blue hover:underline flex items-center gap-0.5"
            >
              Ver escalafón <ArrowUpRight className="w-3.5 h-3.5" />
            </button>
          </div>

          <div className="space-y-3">
            {Object.entries(categoryCounts).map(([cat, count]) => {
              const pct = totalTeachers > 0 ? Math.round((count / totalTeachers) * 100) : 0;
              return (
                <div key={cat} className="space-y-1">
                  <div className="flex items-center justify-between text-xs font-medium">
                    <span className="text-slate-700">{cat}</span>
                    <span className="text-slate-500 font-semibold">{count} ({pct}%)</span>
                  </div>
                  <div className="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                    <div
                      className={`h-full rounded-full ${
                        cat === 'Titular'
                          ? 'bg-amber-500'
                          : cat === 'Asociado'
                          ? 'bg-blue-600'
                          : cat === 'Agregado'
                          ? 'bg-indigo-500'
                          : cat === 'Asistente'
                          ? 'bg-emerald-500'
                          : 'bg-slate-400'
                      }`}
                      style={{ width: `${pct}%` }}
                    />
                  </div>
                </div>
              );
            })}
          </div>
        </div>

        {/* Sede Distribution Chart (SedeStatsChart) */}
        <div className="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
          <div className="flex items-center justify-between mb-4 pb-2 border-b border-slate-100">
            <div>
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                <Building2 className="w-4 h-4 text-emerald-600" />
                Distribución Territorial por Sedes
              </h3>
              <p className="text-xs text-slate-500">Claustro académico por campus y núcleos del estado Guárico</p>
            </div>
            <button 
              onClick={() => onNavigate('sites')}
              className="text-xs font-semibold text-unerg-blue hover:underline flex items-center gap-0.5"
            >
              Ver sedes <ArrowUpRight className="w-3.5 h-3.5" />
            </button>
          </div>

          <div className="space-y-3">
            {sedeCounts.map(([sede, count]) => {
              const pct = totalTeachers > 0 ? Math.round((count / totalTeachers) * 100) : 0;
              return (
                <div key={sede} className="space-y-1">
                  <div className="flex items-center justify-between text-xs font-medium">
                    <span className="text-slate-700 truncate max-w-[240px]">{sede}</span>
                    <span className="text-slate-500 font-semibold">{count} ({pct}%)</span>
                  </div>
                  <div className="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                    <div
                      className="h-full rounded-full bg-emerald-600"
                      style={{ width: `${pct}%` }}
                    />
                  </div>
                </div>
              );
            })}
          </div>
        </div>
      </div>

      {/* Dedication Breakdown & Recent Official Reports */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Dedication summary */}
        <div className="bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
          <div className="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
            <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
              <Clock className="w-4 h-4 text-amber-600" />
              Modalidades de Carga Horaria
            </h3>
          </div>
          <div className="space-y-2.5">
            {Object.entries(dedicationCounts).map(([ded, count]) => (
              <div key={ded} className="flex items-center justify-between p-2.5 rounded-lg bg-slate-50 border border-slate-100">
                <div>
                  <div className="text-xs font-semibold text-slate-800">{ded}</div>
                  <div className="text-[11px] text-slate-500">
                    {ded === 'Exclusiva' ? '36 hrs/sem' : ded === 'Tiempo Completo' ? '30 hrs/sem' : ded === 'Medio Tiempo' ? '18 hrs/sem' : '12 hrs/sem'}
                  </div>
                </div>
                <span className="text-sm font-bold text-slate-900 bg-white px-2.5 py-1 rounded border border-slate-200 shadow-xs">
                  {count}
                </span>
              </div>
            ))}
          </div>
        </div>

        {/* Latest Reports Widget (LatestReportsWidget) */}
        <div className="lg:col-span-2 bg-white rounded-xl p-5 border border-slate-200 shadow-sm">
          <div className="flex items-center justify-between mb-3 pb-2 border-b border-slate-100">
            <div>
              <h3 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                <FileText className="w-4 h-4 text-unerg-blue" />
                Últimos Documentos Oficiales Emitidos
              </h3>
              <p className="text-xs text-slate-500">Documentos oficiales con código de autenticidad UNERG</p>
            </div>
            <button
              onClick={() => onNavigate('reports')}
              className="text-xs font-semibold text-unerg-blue hover:underline"
            >
              Ver todos
            </button>
          </div>

          <div className="space-y-2.5">
            {filteredReports.slice(0, 3).map(report => (
              <div
                key={report.id}
                className="flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 p-3 rounded-lg border border-slate-100 bg-slate-50 hover:bg-slate-100/80 transition-colors"
              >
                <div className="space-y-0.5 min-w-0">
                  <div className="flex items-center gap-2 flex-wrap">
                    <span className="text-xs font-bold text-slate-900">{report.typeReport}</span>
                    <span className="text-[10px] font-mono font-semibold px-2 py-0.5 rounded bg-blue-100 text-blue-800">
                      {report.verification_code}
                    </span>
                  </div>
                  <div className="text-xs text-slate-600 truncate">
                    Docente: <strong className="text-slate-800">{report.teacher_name}</strong> • Memo: {report.memoNumber}
                  </div>
                  <div className="text-[11px] text-slate-400">
                    Emitido por {report.created_by} el {report.created_at}
                  </div>
                </div>

                <button
                  onClick={() => onViewReport(report)}
                  className="self-start sm:self-auto px-3 py-1.5 text-xs font-semibold text-unerg-blue bg-white hover:bg-blue-50 border border-blue-200 rounded-md shadow-xs transition-colors flex-shrink-0"
                >
                  Ver PDF Oficial
                </button>
              </div>
            ))}
          </div>
        </div>
      </div>
    </div>
  );
};
