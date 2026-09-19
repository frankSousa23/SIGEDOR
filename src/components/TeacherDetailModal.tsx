import React, { useState } from 'react';
import { Teacher, Category, Dedication, Site, PermissionTeacher, Report, CategoryLevel } from '../types';
import { useData } from '../context/DataContext';
import { useAuth } from '../context/AuthContext';
import { 
  X, 
  User, 
  Award, 
  Clock, 
  MapPin, 
  CalendarDays, 
  FileText, 
  CheckCircle2, 
  GraduationCap, 
  ArrowUpRight,
  PlusCircle,
  FileCheck
} from 'lucide-react';

interface TeacherDetailModalProps {
  teacher: Teacher;
  onClose: () => void;
  onGenerateReport: (teacher: Teacher, type: Report['typeReport']) => void;
}

export const TeacherDetailModal: React.FC<TeacherDetailModalProps> = ({
  teacher,
  onClose,
  onGenerateReport,
}) => {
  const { categories, dedications, sites, permissions, reports, promoteTeacher } = useData();
  const { isSuperAdmin, isAreaManager } = useAuth();
  const [activeTab, setActiveTab] = useState<'info' | 'category' | 'dedication' | 'sites' | 'permissions' | 'reports'>('info');

  const category = categories.find(c => c.teacher_cdi === teacher.cdi);
  const dedication = dedications.find(d => d.teacher_cdi === teacher.cdi);
  const teacherSites = sites.filter(s => s.teacher_cdi === teacher.cdi);
  const teacherPermissions = permissions.filter(p => p.teacher_cdi === teacher.cdi);
  const teacherReports = reports.filter(r => r.teacher_cdi === teacher.cdi);

  // Promotion modal state
  const [showPromote, setShowPromote] = useState(false);
  const [promotionDate, setPromotionDate] = useState(new Date().toISOString().split('T')[0]);
  const [nextCat, setNextCat] = useState<CategoryLevel>('Asistente');

  const categoryOrder: CategoryLevel[] = ['Instructor', 'Asistente', 'Agregado', 'Asociado', 'Titular'];
  const currentIdx = category ? categoryOrder.indexOf(category.current_category) : 0;
  const canPromote = currentIdx < categoryOrder.length - 1;

  const handlePromote = (e: React.FormEvent) => {
    e.preventDefault();
    if (category) {
      promoteTeacher(teacher.cdi, nextCat, promotionDate);
      setShowPromote(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-2 sm:p-4">
      <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[92vh] flex flex-col overflow-hidden animate-in fade-in zoom-in-95 duration-150">
        {/* Header */}
        <div className="bg-gradient-to-r from-unerg-blue to-slate-900 text-white p-4 sm:p-6 flex items-start justify-between gap-3">
          <div className="flex items-center gap-3 sm:gap-4 min-w-0">
            <div className="w-12 h-12 sm:w-16 sm:h-16 rounded-2xl bg-amber-400 text-slate-950 flex items-center justify-center text-base sm:text-xl font-black shadow-lg flex-shrink-0">
              {teacher.name[0]}{teacher.surName[0]}
            </div>
            <div className="min-w-0">
              <div className="flex items-center gap-2 flex-wrap">
                <h2 className="text-base sm:text-xl font-bold tracking-tight truncate">
                  {teacher.name} {teacher.surName}
                </h2>
                <span className="px-2 py-0.5 rounded-full text-[11px] font-bold bg-amber-300 text-slate-900">
                  {category?.current_category || 'Docente'}
                </span>
              </div>
              <p className="text-xs text-slate-300 mt-0.5 truncate">
                Cédula: <strong className="text-white">{teacher.cdi}</strong> • {teacher.email}
              </p>
              <p className="text-xs text-amber-200 mt-0.5 truncate">
                {teacher.sede_nombre} — {teacher.area_nombre}
              </p>
            </div>
          </div>
          <button
            onClick={onClose}
            className="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-white/10 transition-colors flex-shrink-0"
            aria-label="Cerrar modal"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Tabs Bar */}
        <div className="border-b border-slate-200 px-3 sm:px-6 bg-slate-50 flex space-x-1 overflow-x-auto text-xs font-semibold">
          <button
            onClick={() => setActiveTab('info')}
            className={`py-3 px-3.5 border-b-2 transition-all flex items-center gap-1.5 whitespace-nowrap ${
              activeTab === 'info'
                ? 'border-unerg-blue text-unerg-blue bg-white font-bold shadow-xs'
                : 'border-transparent text-slate-600 hover:text-slate-900'
            }`}
          >
            <User className="w-4 h-4" /> Datos Personales
          </button>

          <button
            onClick={() => setActiveTab('category')}
            className={`py-3 px-3.5 border-b-2 transition-all flex items-center gap-1.5 whitespace-nowrap ${
              activeTab === 'category'
                ? 'border-unerg-blue text-unerg-blue bg-white font-bold shadow-xs'
                : 'border-transparent text-slate-600 hover:text-slate-900'
            }`}
          >
            <Award className="w-4 h-4 text-amber-600" /> Escalafón
          </button>

          <button
            onClick={() => setActiveTab('dedication')}
            className={`py-3 px-3.5 border-b-2 transition-all flex items-center gap-1.5 whitespace-nowrap ${
              activeTab === 'dedication'
                ? 'border-unerg-blue text-unerg-blue bg-white font-bold shadow-xs'
                : 'border-transparent text-slate-600 hover:text-slate-900'
            }`}
          >
            <Clock className="w-4 h-4 text-blue-600" /> Carga Horaria
          </button>

          <button
            onClick={() => setActiveTab('sites')}
            className={`py-3 px-3.5 border-b-2 transition-all flex items-center gap-1.5 whitespace-nowrap ${
              activeTab === 'sites'
                ? 'border-unerg-blue text-unerg-blue bg-white font-bold shadow-xs'
                : 'border-transparent text-slate-600 hover:text-slate-900'
            }`}
          >
            <MapPin className="w-4 h-4 text-emerald-600" /> Cátedras
          </button>

          <button
            onClick={() => setActiveTab('permissions')}
            className={`py-3 px-3.5 border-b-2 transition-all flex items-center gap-1.5 whitespace-nowrap ${
              activeTab === 'permissions'
                ? 'border-unerg-blue text-unerg-blue bg-white font-bold shadow-xs'
                : 'border-transparent text-slate-600 hover:text-slate-900'
            }`}
          >
            <CalendarDays className="w-4 h-4 text-purple-600" /> Permisos ({teacherPermissions.length})
          </button>

          <button
            onClick={() => setActiveTab('reports')}
            className={`py-3 px-3.5 border-b-2 transition-all flex items-center gap-1.5 whitespace-nowrap ${
              activeTab === 'reports'
                ? 'border-unerg-blue text-unerg-blue bg-white font-bold shadow-xs'
                : 'border-transparent text-slate-600 hover:text-slate-900'
            }`}
          >
            <FileText className="w-4 h-4 text-indigo-600" /> Reportes ({teacherReports.length})
          </button>
        </div>

        {/* Tab Content */}
        <div className="p-6 flex-1 overflow-y-auto">
          {/* TAB 1: DATOS PERSONALES */}
          {activeTab === 'info' && (
            <div className="space-y-6">
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="bg-slate-50 p-4 rounded-xl border border-slate-200">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                    Información de Identidad
                  </span>
                  <div className="mt-3 space-y-2 text-xs">
                    <div><span className="text-slate-500">Cédula de Identidad:</span> <strong className="text-slate-800 font-mono text-sm ml-1">V-{teacher.cdi}</strong></div>
                    <div><span className="text-slate-500">Sexo / Género:</span> <span className="font-semibold text-slate-800 ml-1">{teacher.genre === 'M' ? 'Masculino' : 'Femenino'}</span></div>
                    <div><span className="text-slate-500">Fecha de Nacimiento:</span> <span className="font-semibold text-slate-800 ml-1">{teacher.birthDate}</span></div>
                    <div><span className="text-slate-500">Teléfono Móvil:</span> <span className="font-semibold text-slate-800 ml-1">{teacher.phone}</span></div>
                    <div><span className="text-slate-500">Correo Institucional:</span> <span className="font-semibold text-slate-800 ml-1">{teacher.email}</span></div>
                  </div>
                </div>

                <div className="bg-slate-50 p-4 rounded-xl border border-slate-200">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                    Adscripción Académica
                  </span>
                  <div className="mt-3 space-y-2 text-xs">
                    <div><span className="text-slate-500">Sede / Núcleo:</span> <strong className="text-unerg-blue ml-1">{teacher.sede_nombre}</strong></div>
                    <div><span className="text-slate-500">Área de Conocimiento:</span> <strong className="text-slate-800 ml-1">{teacher.area_nombre}</strong></div>
                    <div><span className="text-slate-500">Programa Académico:</span> <strong className="text-slate-800 ml-1">{teacher.programa_nombre}</strong></div>
                    <div><span className="text-slate-500">Fecha de Ingreso:</span> <span className="font-semibold text-slate-800 ml-1">{teacher.datePromotion}</span></div>
                    <div><span className="text-slate-500">Asignatura por Concurso:</span> <span className="font-semibold text-slate-800 ml-1">{teacher.asignaturePromotion}</span></div>
                  </div>
                </div>
              </div>

              {/* Quick Actions */}
              <div className="p-4 bg-blue-50 border border-blue-200 rounded-xl flex items-center justify-between">
                <div>
                  <h4 className="text-xs font-bold text-unerg-blue">Generar Documentación Oficial</h4>
                  <p className="text-[11px] text-slate-600">Emite constancias con código de autenticidad verificable.</p>
                </div>
                <div className="flex gap-2">
                  <button
                    onClick={() => onGenerateReport(teacher, 'Constancia de Trabajo')}
                    className="px-3 py-1.5 bg-unerg-blue hover:bg-unerg-blue-light text-white text-xs font-semibold rounded-lg shadow-xs flex items-center gap-1.5"
                  >
                    <FileCheck className="w-3.5 h-3.5" /> Constancia de Trabajo
                  </button>
                  <button
                    onClick={() => onGenerateReport(teacher, 'Expediente Curricular Individual')}
                    className="px-3 py-1.5 bg-white hover:bg-slate-50 text-slate-800 border border-slate-300 text-xs font-semibold rounded-lg shadow-xs flex items-center gap-1.5"
                  >
                    <FileText className="w-3.5 h-3.5" /> Expediente 360°
                  </button>
                </div>
              </div>
            </div>
          )}

          {/* TAB 2: ESCALAFÓN */}
          {activeTab === 'category' && (
            <div className="space-y-6">
              <div className="flex items-center justify-between pb-3 border-b border-slate-200">
                <div>
                  <h3 className="text-sm font-bold text-slate-900">
                    Control de Escalafón Universitario UNERG
                  </h3>
                  <p className="text-xs text-slate-500">Historial cronológico de ascensos docentes</p>
                </div>
                {(isSuperAdmin || isAreaManager) && canPromote && (
                  <button
                    onClick={() => {
                      const next = categoryOrder[currentIdx + 1];
                      setNextCat(next);
                      setShowPromote(true);
                    }}
                    className="px-3 py-1.5 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-lg shadow-xs flex items-center gap-1"
                  >
                    <Award className="w-3.5 h-3.5" /> Promover de Categoría
                  </button>
                )}
              </div>

              {/* Degrees & Titles */}
              <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div className="p-4 bg-slate-50 rounded-xl border border-slate-200">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                    Título de Pregrado
                  </span>
                  <div className="font-bold text-slate-800 text-sm mt-1">
                    {category?.preTitle || 'No especificado'}
                  </div>
                </div>

                <div className="p-4 bg-slate-50 rounded-xl border border-slate-200">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                    Último Título / Posgrado Obtenido
                  </span>
                  <div className="font-bold text-indigo-900 text-sm mt-1">
                    {category?.lastTitle || 'Sin título de posgrado registrado'}
                  </div>
                </div>
              </div>

              {/* Escalafón Progression Stepper */}
              <div className="bg-slate-50 p-6 rounded-xl border border-slate-200">
                <span className="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-4">
                  Línea de Ascensos Universitarios
                </span>

                <div className="grid grid-cols-5 gap-2">
                  {categoryOrder.map((cat, idx) => {
                    const isReached = idx <= currentIdx;
                    const isCurrent = idx === currentIdx;
                    let dateStr = '';
                    if (cat === 'Instructor') dateStr = category?.instructor || '';
                    if (cat === 'Asistente') dateStr = category?.asistente || '';
                    if (cat === 'Agregado') dateStr = category?.agregado || '';
                    if (cat === 'Asociado') dateStr = category?.asociado || '';
                    if (cat === 'Titular') dateStr = category?.titular || '';

                    return (
                      <div
                        key={cat}
                        className={`p-3 rounded-xl border text-center transition-all ${
                          isCurrent
                            ? 'bg-amber-50 border-amber-300 ring-2 ring-amber-400/50'
                            : isReached
                            ? 'bg-white border-blue-200'
                            : 'bg-slate-100/60 border-slate-200 opacity-60'
                        }`}
                      >
                        <div className={`w-8 h-8 rounded-full mx-auto flex items-center justify-center font-bold text-xs ${
                          isCurrent ? 'bg-amber-400 text-slate-900' : isReached ? 'bg-unerg-blue text-white' : 'bg-slate-300 text-slate-600'
                        }`}>
                          {idx + 1}
                        </div>
                        <div className={`text-xs font-bold mt-2 ${isCurrent ? 'text-amber-900' : 'text-slate-800'}`}>
                          {cat}
                        </div>
                        <div className="text-[10px] text-slate-500 mt-1">
                          {dateStr ? dateStr : 'Pendiente'}
                        </div>
                      </div>
                    );
                  })}
                </div>
              </div>

              {/* Promotion form modal inline */}
              {showPromote && (
                <form onSubmit={handlePromote} className="p-4 bg-amber-50 border border-amber-200 rounded-xl space-y-3">
                  <div className="flex items-center justify-between">
                    <h4 className="text-xs font-bold text-amber-900">Registrar Ascenso de Escalafón</h4>
                    <button type="button" onClick={() => setShowPromote(false)} className="text-xs text-slate-500">Cancelar</button>
                  </div>
                  <div className="grid grid-cols-2 gap-3 text-xs">
                    <div>
                      <label className="block text-slate-600 mb-1">Nueva Categoría:</label>
                      <select
                        value={nextCat}
                        onChange={(e) => setNextCat(e.target.value as CategoryLevel)}
                        className="w-full p-2 border border-slate-300 rounded-md font-semibold"
                      >
                        {categoryOrder.slice(currentIdx + 1).map(c => (
                          <option key={c} value={c}>{c}</option>
                        ))}
                      </select>
                    </div>
                    <div>
                      <label className="block text-slate-600 mb-1">Fecha de Aprobación:</label>
                      <input
                        type="date"
                        value={promotionDate}
                        onChange={(e) => setPromotionDate(e.target.value)}
                        className="w-full p-2 border border-slate-300 rounded-md font-semibold"
                      />
                    </div>
                  </div>
                  <button
                    type="submit"
                    className="px-4 py-2 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold text-xs rounded-lg"
                  >
                    Confirmar Ascenso Docente
                  </button>
                </form>
              )}
            </div>
          )}

          {/* TAB 3: CARGA HORARIA */}
          {activeTab === 'dedication' && dedication && (
            <div className="space-y-6">
              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div className="bg-slate-50 p-4 rounded-xl border border-slate-200">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                    Modalidad
                  </span>
                  <div className="text-lg font-bold text-unerg-blue mt-1">
                    {dedication.name}
                  </div>
                  <span className="text-xs text-slate-500">{dedication.hours} horas semanales</span>
                </div>

                <div className="bg-slate-50 p-4 rounded-xl border border-slate-200">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                    Cargo Directivo / Coordinación
                  </span>
                  <div className="text-lg font-bold text-slate-800 mt-1">
                    {dedication.director || 'Docente Regular'}
                  </div>
                </div>

                <div className="bg-slate-50 p-4 rounded-xl border border-slate-200">
                  <span className="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">
                    Atención Estudiantil y Tutoría
                  </span>
                  <div className="text-lg font-bold text-slate-800 mt-1">
                    {dedication.studentNumber} estudiantes
                  </div>
                  <span className="text-xs text-slate-500">{dedication.studentHours} horas/semana de asesoría</span>
                </div>
              </div>

              <div className="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span className="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-1">
                  Observaciones Académicas
                </span>
                <p className="text-xs text-slate-700">
                  {dedication.info || 'Cumplimiento regular de labores docentes y de investigación.'}
                </p>
              </div>
            </div>
          )}

          {/* TAB 4: CÁTEDRAS Y SEDES */}
          {activeTab === 'sites' && (
            <div className="space-y-4">
              <div className="flex items-center justify-between pb-2 border-b border-slate-200">
                <h3 className="text-sm font-bold text-slate-900">
                  Cátedras Asignadas ({teacherSites.length})
                </h3>
              </div>

              {teacherSites.length === 0 ? (
                <p className="text-xs text-slate-500 py-6 text-center">No tiene cátedras asignadas registradas.</p>
              ) : (
                <div className="space-y-3">
                  {teacherSites.map(site => (
                    <div key={site.id} className="p-4 rounded-xl border border-slate-200 bg-slate-50 hover:bg-white transition-colors">
                      <div className="flex items-center justify-between">
                        <h4 className="text-xs font-bold text-slate-900">{site.info}</h4>
                        <span className="text-[10px] font-bold px-2 py-0.5 rounded bg-emerald-100 text-emerald-800">
                          {site.is_active ? 'Activa' : 'Inactiva'}
                        </span>
                      </div>
                      <div className="grid grid-cols-2 sm:grid-cols-4 gap-2 mt-2 text-[11px] text-slate-600">
                        <div>Sede: <strong className="text-slate-800">{site.sede_nombre}</strong></div>
                        <div>Programa: <strong className="text-slate-800">{site.programa_nombre}</strong></div>
                        <div>Unidades Crédito: <strong className="text-slate-800">{site.uc} UC</strong></div>
                        <div>Horas / Secciones: <strong className="text-slate-800">{site.weekHours}h / {site.sections} sec.</strong></div>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

          {/* TAB 5: PERMISOS */}
          {activeTab === 'permissions' && (
            <div className="space-y-4">
              <h3 className="text-sm font-bold text-slate-900 pb-2 border-b border-slate-200">
                Historial de Permisos y Licencias
              </h3>
              {teacherPermissions.length === 0 ? (
                <p className="text-xs text-slate-500 py-6 text-center">No registra solicitudes de permiso ni licencias.</p>
              ) : (
                <div className="space-y-3">
                  {teacherPermissions.map(p => (
                    <div key={p.id} className="p-4 rounded-xl border border-slate-200 bg-slate-50">
                      <div className="flex items-center justify-between">
                        <span className="text-xs font-bold text-slate-900">{p.type}</span>
                        <span className={`text-[10px] font-bold px-2 py-0.5 rounded ${
                          p.status === 'Aprobado' ? 'bg-emerald-100 text-emerald-800' : p.status === 'Pendiente' ? 'bg-amber-100 text-amber-800' : 'bg-red-100 text-red-800'
                        }`}>
                          {p.status}
                        </span>
                      </div>
                      <p className="text-xs text-slate-600 mt-1">{p.reason}</p>
                      <div className="text-[11px] text-slate-400 mt-2">
                        Vigencia: {p.start_date} al {p.end_date} • Semestre: {p.semester}
                      </div>
                      {p.observations && (
                        <div className="text-[11px] text-indigo-700 bg-indigo-50 p-2 rounded-md mt-2">
                          <strong>Observaciones:</strong> {p.observations}
                        </div>
                      )}
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}

          {/* TAB 6: REPORTES EMITIDOS */}
          {activeTab === 'reports' && (
            <div className="space-y-4">
              <div className="flex items-center justify-between pb-2 border-b border-slate-200">
                <h3 className="text-sm font-bold text-slate-900">
                  Documentos Emitidos para {teacher.name} {teacher.surName}
                </h3>
              </div>
              {teacherReports.length === 0 ? (
                <div className="text-center py-6">
                  <p className="text-xs text-slate-500">No se han generado reportes para este docente aún.</p>
                  <button
                    onClick={() => onGenerateReport(teacher, 'Constancia de Trabajo')}
                    className="mt-3 px-3 py-1.5 bg-unerg-blue text-white text-xs font-bold rounded-lg shadow-xs"
                  >
                    Emitir Primera Constancia
                  </button>
                </div>
              ) : (
                <div className="space-y-3">
                  {teacherReports.map(r => (
                    <div key={r.id} className="p-3.5 rounded-xl border border-slate-200 bg-slate-50 flex items-center justify-between">
                      <div>
                        <div className="flex items-center gap-2">
                          <span className="text-xs font-bold text-slate-900">{r.typeReport}</span>
                          <span className="text-[10px] font-mono bg-blue-100 text-blue-800 px-2 py-0.5 rounded font-bold">
                            {r.verification_code}
                          </span>
                        </div>
                        <div className="text-xs text-slate-600 mt-0.5">{r.memoNumber} • {r.created_at}</div>
                      </div>
                      <button
                        onClick={() => onGenerateReport(teacher, r.typeReport)}
                        className="px-3 py-1 bg-white hover:bg-slate-100 text-xs font-semibold text-unerg-blue border border-slate-300 rounded-md"
                      >
                        Visualizar / Reimprimir
                      </button>
                    </div>
                  ))}
                </div>
              )}
            </div>
          )}
        </div>

        {/* Modal Footer */}
        <div className="bg-slate-50 px-6 py-4 border-t border-slate-200 flex justify-end">
          <button
            onClick={onClose}
            className="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-semibold rounded-lg transition-colors"
          >
            Cerrar Expediente
          </button>
        </div>
      </div>
    </div>
  );
};
