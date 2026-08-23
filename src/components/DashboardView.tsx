import React from 'react';
import { useApp } from '../context/AppContext';
import {
  GraduationCap,
  Award,
  Clock,
  Building2,
  FileCheck2,
  FileText,
  UserPlus,
  PlusCircle,
  FileSpreadsheet,
  CheckCircle,
  AlertTriangle,
  ArrowRight,
  TrendingUp,
  ShieldCheck
} from 'lucide-react';

export const DashboardView: React.FC = () => {
  const {
    currentUser,
    teachers,
    categories,
    dedications,
    sites,
    permissions,
    reports,
    activityLogs,
    setActiveTab
  } = useApp();

  // Role flags matching blade template
  const isAdmin = currentUser.roles.includes('admin');
  const isAreaManager = currentUser.roles.includes('area_manager');
  const isTeacher = currentUser.roles.includes('teacher');

  // Stats calculation
  const totalTeachers = teachers.length;
  const pendingPermissions = permissions.filter(p => p.status === 'pending');
  const approvedPermissions = permissions.filter(p => p.status === 'approved');

  // Escalafón breakdown
  const categoryCount = {
    Titular: categories.filter(c => c.current_category === 'Titular').length,
    Asociado: categories.filter(c => c.current_category === 'Asociado').length,
    Agregado: categories.filter(c => c.current_category === 'Agregado').length,
    Asistente: categories.filter(c => c.current_category === 'Asistente').length,
    Instructor: categories.filter(c => c.current_category === 'Instructor').length,
  };

  // Dedication breakdown
  const dedicationCount = {
    Exclusiva: dedications.filter(d => d.name === 'Exclusiva').length,
    'Tiempo Completo': dedications.filter(d => d.name === 'Tiempo Completo').length,
    'Medio Tiempo': dedications.filter(d => d.name === 'Medio Tiempo').length,
    'Tiempo Convencional': dedications.filter(d => d.name === 'Tiempo Convencional').length,
  };

  // Unique sedes represented
  const activeSedesCount = new Set(teachers.map(t => t.sede_nombre)).size;

  // Teacher personal view if logged in as simple teacher
  const teacherRecord = teachers.find(t => t.user_email === currentUser.email || t.email === currentUser.email);
  const teacherCategory = teacherRecord ? categories.find(c => c.teacher_cdi === teacherRecord.cdi) : null;
  const teacherDedication = teacherRecord ? dedications.find(d => d.teacher_cdi === teacherRecord.cdi) : null;
  const teacherSites = teacherRecord ? sites.filter(s => s.teacher_cdi === teacherRecord.cdi) : [];
  const teacherPerms = teacherRecord ? permissions.filter(p => p.teacher_cdi === teacherRecord.cdi) : [];

  return (
    <div className="space-y-6">
      {/* Welcome Banner */}
      <div className="bg-gradient-to-r from-[#003366] via-[#004080] to-[#002244] rounded-2xl p-6 text-white shadow-lg relative overflow-hidden">
        <div className="absolute right-0 top-0 bottom-0 w-1/3 bg-white/5 skew-x-12 pointer-events-none"></div>
        <div className="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div className="inline-flex items-center space-x-1.5 bg-amber-500 text-slate-950 px-2.5 py-0.5 rounded-full text-xs font-bold uppercase tracking-wider mb-2">
              <ShieldCheck className="w-3.5 h-3.5" />
              <span>
                {isAdmin ? 'Perfil: Administrador Central' : isAreaManager ? 'Perfil: Jefe de Área Académica' : 'Perfil: Docente Ordinario'}
              </span>
            </div>
            <h1 className="text-2xl font-bold font-serif tracking-tight">
              Bienvenido(a), {currentUser.name}
            </h1>
            <p className="text-slate-200 text-sm mt-1 max-w-2xl">
              {isAdmin && 'Panel Central de Control. Gestione expedientes docentes, ascensos en el escalafón, asignaciones territoriales y emisión de memorandos oficiales.'}
              {isAreaManager && `Supervisión de profesores y permisos adscritos a: ${currentUser.area_nombre || 'Área Académica'} (${currentUser.sede_nombre || 'Sede Central'}).`}
              {!isAdmin && !isAreaManager && isTeacher && 'Consulte el estado de su escalafón universitario, carga horaria asignada, materias y solicitudes de permisos.'}
            </p>
          </div>

          <div className="flex items-center gap-2">
            <button
              onClick={() => setActiveTab('teachers')}
              className="bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold px-4 py-2 rounded-lg text-xs transition-colors shadow flex items-center space-x-1.5"
            >
              <GraduationCap className="w-4 h-4" />
              <span>Ver Expedientes</span>
            </button>
            <button
              onClick={() => setActiveTab('reports')}
              className="bg-white/10 hover:bg-white/20 text-white font-medium px-4 py-2 rounded-lg text-xs transition-colors border border-white/20 flex items-center space-x-1.5"
            >
              <FileText className="w-4 h-4" />
              <span>Nuevo Memorando</span>
            </button>
          </div>
        </div>
      </div>

      {/* Main KPI Stat Cards */}
      <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {/* Total Docentes */}
        <div className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p className="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Docentes</p>
            <h3 className="text-2xl font-bold text-[#003366] mt-1">{totalTeachers}</h3>
            <p className="text-[11px] text-emerald-700 font-medium mt-1 flex items-center">
              <TrendingUp className="w-3 h-3 mr-1" />
              100% Expedientes activos
            </p>
          </div>
          <div className="w-12 h-12 bg-blue-50 text-[#003366] rounded-xl flex items-center justify-center">
            <GraduationCap className="w-6 h-6" />
          </div>
        </div>

        {/* Profesores Titulares y Asociados */}
        <div className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p className="text-xs font-semibold text-slate-500 uppercase tracking-wider">Titulares / Asociados</p>
            <h3 className="text-2xl font-bold text-amber-800 mt-1">{categoryCount.Titular + categoryCount.Asociado}</h3>
            <p className="text-[11px] text-slate-600 font-medium mt-1">
              {categoryCount.Titular} Titulares • {categoryCount.Asociado} Asociados
            </p>
          </div>
          <div className="w-12 h-12 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center">
            <Award className="w-6 h-6" />
          </div>
        </div>

        {/* Dedicación Exclusiva y TC */}
        <div className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p className="text-xs font-semibold text-slate-500 uppercase tracking-wider">Dedicación Alta Carga</p>
            <h3 className="text-2xl font-bold text-slate-800 mt-1">{dedicationCount.Exclusiva + dedicationCount['Tiempo Completo']}</h3>
            <p className="text-[11px] text-slate-600 font-medium mt-1">
              {dedicationCount.Exclusiva} Exclusivas • {dedicationCount['Tiempo Completo']} TC
            </p>
          </div>
          <div className="w-12 h-12 bg-emerald-50 text-emerald-800 rounded-xl flex items-center justify-center">
            <Clock className="w-6 h-6" />
          </div>
        </div>

        {/* Permisos por Revisar */}
        <div className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <p className="text-xs font-semibold text-slate-500 uppercase tracking-wider">Permisos Pendientes</p>
            <h3 className="text-2xl font-bold text-amber-700 mt-1">{pendingPermissions.length}</h3>
            <p className="text-[11px] text-amber-800 font-medium mt-1">
              {approvedPermissions.length} aprobados este período
            </p>
          </div>
          <div className="w-12 h-12 bg-amber-50 text-amber-700 rounded-xl flex items-center justify-center">
            <FileCheck2 className="w-6 h-6" />
          </div>
        </div>
      </div>

      {/* Teacher Profile Card if logged in as simple Teacher */}
      {isTeacher && teacherRecord && (
        <div className="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
          <div className="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
              <span className="text-xs font-bold uppercase tracking-wider text-amber-700">Mi Expediente Académico</span>
              <h2 className="text-xl font-bold text-slate-900 mt-0.5">
                {teacherRecord.name} {teacherRecord.surName}
              </h2>
              <p className="text-xs text-slate-500">C.I. {teacherRecord.cdi} • {teacherRecord.email}</p>
            </div>
            <button
              onClick={() => setActiveTab('teachers')}
              className="text-xs font-semibold text-[#003366] hover:underline flex items-center"
            >
              Ver Ficha Completa <ArrowRight className="w-3.5 h-3.5 ml-1" />
            </button>
          </div>

          <div className="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
            <div className="bg-slate-50 p-4 rounded-lg border border-slate-100">
              <p className="text-xs font-medium text-slate-500">Categoría Escalafón</p>
              <p className="text-base font-bold text-[#003366] mt-1">{teacherCategory?.current_category || 'Instructor'}</p>
              <p className="text-xs text-slate-600 mt-0.5">{teacherCategory?.lastTitle || teacherCategory?.preTitle || 'Título Registrado'}</p>
            </div>
            <div className="bg-slate-50 p-4 rounded-lg border border-slate-100">
              <p className="text-xs font-medium text-slate-500">Dedicación Horaria</p>
              <p className="text-base font-bold text-emerald-800 mt-1">{teacherDedication?.name || 'Tiempo Completo'} ({teacherDedication?.hours || 30}h)</p>
              <p className="text-xs text-slate-600 mt-0.5">{teacherDedication?.director ? `Cargo: ${teacherDedication.director}` : 'Docencia regular'}</p>
            </div>
            <div className="bg-slate-50 p-4 rounded-lg border border-slate-100">
              <p className="text-xs font-medium text-slate-500">Cátedras Asignadas</p>
              <p className="text-base font-bold text-slate-800 mt-1">{teacherSites.length} Asignaciones</p>
              <p className="text-xs text-slate-600 mt-0.5">{teacherRecord.area_nombre}</p>
            </div>
          </div>
        </div>
      )}

      {/* Two Column Layout: Distributions & Recent Actions */}
      <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {/* Left 2 Cols: Escalafón & Dedicación Analytics */}
        <div className="lg:col-span-2 space-y-6">
          {/* Escalafón Distribution */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div className="flex items-center justify-between mb-4">
              <div>
                <h3 className="font-bold text-slate-900 text-sm">Distribución por Escalafón Docente</h3>
                <p className="text-xs text-slate-500">Clasificación según estatutos universitarios UNERG</p>
              </div>
              <button 
                onClick={() => setActiveTab('categories')}
                className="text-xs font-medium text-[#003366] hover:underline"
              >
                Ver Escalafón
              </button>
            </div>

            <div className="space-y-3">
              {[
                { label: 'Titular', count: categoryCount.Titular, color: 'bg-amber-600', total: totalTeachers },
                { label: 'Asociado', count: categoryCount.Asociado, color: 'bg-blue-600', total: totalTeachers },
                { label: 'Agregado', count: categoryCount.Agregado, color: 'bg-indigo-600', total: totalTeachers },
                { label: 'Asistente', count: categoryCount.Asistente, color: 'bg-emerald-600', total: totalTeachers },
                { label: 'Instructor', count: categoryCount.Instructor, color: 'bg-slate-500', total: totalTeachers },
              ].map(item => {
                const percentage = totalTeachers > 0 ? Math.round((item.count / totalTeachers) * 100) : 0;
                return (
                  <div key={item.label} className="space-y-1">
                    <div className="flex justify-between text-xs font-medium">
                      <span className="text-slate-700">{item.label}</span>
                      <span className="text-slate-500">{item.count} docentes ({percentage}%)</span>
                    </div>
                    <div className="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                      <div
                        className={`h-full rounded-full ${item.color}`}
                        style={{ width: `${Math.max(percentage, 4)}%` }}
                      ></div>
                    </div>
                  </div>
                );
              })}
            </div>
          </div>

          {/* Dedication Distribution */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div className="flex items-center justify-between mb-4">
              <div>
                <h3 className="font-bold text-slate-900 text-sm">Distribución de Dedicación y Carga Horaria</h3>
                <p className="text-xs text-slate-500">Carga académica reglamentaria por docente</p>
              </div>
              <button 
                onClick={() => setActiveTab('dedications')}
                className="text-xs font-medium text-[#003366] hover:underline"
              >
                Ver Dedicación
              </button>
            </div>

            <div className="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center">
              <div className="bg-slate-50 p-3 rounded-lg border border-slate-100">
                <p className="text-[11px] text-slate-500 uppercase font-bold">Exclusiva (36h)</p>
                <p className="text-xl font-bold text-[#003366] mt-1">{dedicationCount.Exclusiva}</p>
                <p className="text-[10px] text-slate-400">Docencia + Inv.</p>
              </div>
              <div className="bg-slate-50 p-3 rounded-lg border border-slate-100">
                <p className="text-[11px] text-slate-500 uppercase font-bold">T. Completo (30h)</p>
                <p className="text-xl font-bold text-emerald-800 mt-1">{dedicationCount['Tiempo Completo']}</p>
                <p className="text-[10px] text-slate-400">Jornada 30h</p>
              </div>
              <div className="bg-slate-50 p-3 rounded-lg border border-slate-100">
                <p className="text-[11px] text-slate-500 uppercase font-bold">Medio Tiempo (18h)</p>
                <p className="text-xl font-bold text-indigo-800 mt-1">{dedicationCount['Medio Tiempo']}</p>
                <p className="text-[10px] text-slate-400">Jornada 18h</p>
              </div>
              <div className="bg-slate-50 p-3 rounded-lg border border-slate-100">
                <p className="text-[11px] text-slate-500 uppercase font-bold">Convencional (12h)</p>
                <p className="text-xl font-bold text-amber-800 mt-1">{dedicationCount['Tiempo Convencional']}</p>
                <p className="text-[10px] text-slate-400">Por horas</p>
              </div>
            </div>
          </div>
        </div>

        {/* Right Col: Quick Actions & Recent Activity Log */}
        <div className="space-y-6">
          {/* Quick Actions Card */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <h3 className="font-bold text-slate-900 text-sm mb-3">Acciones Frecuentes</h3>
            <div className="space-y-2">
              <button
                onClick={() => setActiveTab('teachers')}
                className="w-full flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 text-left transition-colors text-xs text-slate-700"
              >
                <div className="flex items-center space-x-2.5">
                  <div className="p-1.5 bg-blue-50 text-[#003366] rounded-md">
                    <UserPlus className="w-4 h-4" />
                  </div>
                  <div>
                    <span className="font-semibold text-slate-900 block">Registrar Nuevo Docente</span>
                    <span className="text-[11px] text-slate-500">Alta en nómina y escalafón</span>
                  </div>
                </div>
                <ArrowRight className="w-3.5 h-3.5 text-slate-400" />
              </button>

              <button
                onClick={() => setActiveTab('reports')}
                className="w-full flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 text-left transition-colors text-xs text-slate-700"
              >
                <div className="flex items-center space-x-2.5">
                  <div className="p-1.5 bg-amber-50 text-amber-700 rounded-md">
                    <FileText className="w-4 h-4" />
                  </div>
                  <div>
                    <span className="font-semibold text-slate-900 block">Generar Constancia / Memo</span>
                    <span className="text-[11px] text-slate-500">Documento oficial membretado</span>
                  </div>
                </div>
                <ArrowRight className="w-3.5 h-3.5 text-slate-400" />
              </button>

              <button
                onClick={() => setActiveTab('csv')}
                className="w-full flex items-center justify-between p-2.5 rounded-lg border border-slate-100 hover:bg-slate-50 text-left transition-colors text-xs text-slate-700"
              >
                <div className="flex items-center space-x-2.5">
                  <div className="p-1.5 bg-emerald-50 text-emerald-700 rounded-md">
                    <FileSpreadsheet className="w-4 h-4" />
                  </div>
                  <div>
                    <span className="font-semibold text-slate-900 block">Carga Masiva por CSV</span>
                    <span className="text-[11px] text-slate-500">Importación de bases de datos</span>
                  </div>
                </div>
                <ArrowRight className="w-3.5 h-3.5 text-slate-400" />
              </button>
            </div>
          </div>

          {/* Activity Log Mini Widget */}
          <div className="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
            <div className="flex items-center justify-between mb-3">
              <h3 className="font-bold text-slate-900 text-sm">Registro Reciente</h3>
              <button
                onClick={() => setActiveTab('activity')}
                className="text-xs text-[#003366] hover:underline font-medium"
              >
                Ver Todo
              </button>
            </div>

            <div className="space-y-3">
              {activityLogs.slice(0, 4).map(log => (
                <div key={log.id} className="text-xs space-y-0.5 border-b border-slate-100 pb-2 last:border-0 last:pb-0">
                  <div className="flex items-center justify-between">
                    <span className="font-semibold text-slate-800 truncate max-w-[150px]">{log.user_name}</span>
                    <span className="text-[10px] text-slate-500">{log.timestamp.substring(11, 16)}</span>
                  </div>
                  <p className="text-slate-600 text-[11px] leading-tight">{log.description}</p>
                </div>
              ))}
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};
