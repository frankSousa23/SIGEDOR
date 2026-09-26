import React, { useState } from 'react';
import { useData } from '../context/DataContext';
import { useAuth } from '../context/AuthContext';
import { CalendarDays, Plus, CheckCircle2, XCircle, Clock, AlertCircle, X, Search } from 'lucide-react';
import { PermissionTeacher } from '../types';

export const PermissionManagement: React.FC = () => {
  const { filteredPermissions, addPermission, updatePermissionStatus, teachers } = useData();
  const { currentUser, isSuperAdmin, isAreaManager, isTeacher } = useAuth();

  const [search, setSearch] = useState('');
  const [statusFilter, setStatusFilter] = useState<'TODOS' | 'Pendiente' | 'Aprobado' | 'Rechazado'>('TODOS');
  const [showRequestModal, setShowRequestModal] = useState(false);
  const [formTeacherCdi, setFormTeacherCdi] = useState(teachers[0]?.cdi || '');
  const [formType, setFormType] = useState<PermissionTeacher['type']>('Permiso Académico');
  const [formReason, setFormReason] = useState('');
  const [formStartDate, setFormStartDate] = useState(new Date().toISOString().split('T')[0]);
  const [formEndDate, setFormEndDate] = useState(new Date(Date.now() + 14 * 86400000).toISOString().split('T')[0]);
  const [formSemester, setFormSemester] = useState('2025-I');

  const handleRequestSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formReason) return;

    // If teacher role, auto assign own CDI
    let cdi = formTeacherCdi;
    if (isTeacher) {
      const myTeacher = teachers.find(t => t.email.toLowerCase() === currentUser.email.toLowerCase());
      if (myTeacher) cdi = myTeacher.cdi;
    }

    addPermission({
      teacher_cdi: cdi,
      type: formType,
      reason: formReason,
      start_date: formStartDate,
      end_date: formEndDate,
      semester: formSemester,
      status: 'Pendiente',
      observations: 'Solicitud ingresada para revisión de Consejo de Área.',
    });

    setShowRequestModal(false);
    setFormReason('');
  };

  return (
    <div className="space-y-4">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <CalendarDays className="w-5 h-5 text-purple-600" />
            Permisos, Licencias y Años Sabáticos
          </h2>
          <p className="text-xs text-slate-500">
            Tramitación y verificación de licencias del personal docente UNERG ({filteredPermissions.length} registros)
          </p>
        </div>

        <button
          onClick={() => setShowRequestModal(true)}
          className="px-4 py-2 text-xs font-bold text-white bg-unerg-blue hover:bg-unerg-blue-light rounded-lg shadow-sm flex items-center gap-1.5 transition-all"
        >
          <Plus className="w-4 h-4" /> Solicitar Permiso / Licencia
        </button>
      </div>

      {/* Filter and Search Bar */}
      <div className="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs flex flex-col sm:flex-row gap-2.5 items-stretch sm:items-center justify-between">
        <div className="relative flex-1 max-w-md">
          <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Buscar por docente, cédula, tipo de permiso o motivo..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-unerg-blue"
          />
        </div>

        <div className="flex items-center gap-1.5 overflow-x-auto text-xs">
          <button
            onClick={() => setStatusFilter('TODOS')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors ${
              statusFilter === 'TODOS'
                ? 'bg-slate-800 text-white'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            }`}
          >
            Todos ({filteredPermissions.length})
          </button>
          <button
            onClick={() => setStatusFilter('Pendiente')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors ${
              statusFilter === 'Pendiente'
                ? 'bg-amber-600 text-white'
                : 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200'
            }`}
          >
            Pendientes ({filteredPermissions.filter(p => p.status === 'Pendiente').length})
          </button>
          <button
            onClick={() => setStatusFilter('Aprobado')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors ${
              statusFilter === 'Aprobado'
                ? 'bg-emerald-600 text-white'
                : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200'
            }`}
          >
            Aprobados ({filteredPermissions.filter(p => p.status === 'Aprobado').length})
          </button>
          <button
            onClick={() => setStatusFilter('Rechazado')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors ${
              statusFilter === 'Rechazado'
                ? 'bg-red-600 text-white'
                : 'bg-red-50 text-red-700 hover:bg-red-100 border border-red-200'
            }`}
          >
            Rechazados ({filteredPermissions.filter(p => p.status === 'Rechazado').length})
          </button>
        </div>
      </div>

      {/* Permissions Grid */}
      {(() => {
        const displayed = filteredPermissions.filter(p => {
          const teacher = teachers.find(t => t.cdi === p.teacher_cdi);
          const teacherName = teacher ? `${teacher.name} ${teacher.surName}`.toLowerCase() : (p.teacher_name || '').toLowerCase();
          const matchesSearch = 
            teacherName.includes(search.toLowerCase()) ||
            p.teacher_cdi.includes(search) ||
            p.type.toLowerCase().includes(search.toLowerCase()) ||
            p.reason.toLowerCase().includes(search.toLowerCase());
          const matchesStatus = statusFilter === 'TODOS' || p.status === statusFilter;
          return matchesSearch && matchesStatus;
        });

        return (
          <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
            {displayed.length === 0 ? (
              <div className="col-span-2 bg-white rounded-xl p-8 border border-slate-200 text-center text-slate-500">
                No se encontraron solicitudes de permisos con los filtros seleccionados.
              </div>
            ) : (
              displayed.map(p => {
            const teacher = teachers.find(t => t.cdi === p.teacher_cdi);

            return (
              <div key={p.id} className="bg-white rounded-xl p-5 border border-slate-200 shadow-sm space-y-3">
                <div className="flex items-start justify-between gap-2">
                  <div>
                    <div className="flex items-center gap-2">
                      <span className="text-sm font-bold text-slate-900">{p.type}</span>
                      <span className={`text-[10px] font-bold px-2 py-0.5 rounded-full ${
                        p.status === 'Aprobado'
                          ? 'bg-emerald-100 text-emerald-800 border border-emerald-200'
                          : p.status === 'Pendiente'
                          ? 'bg-amber-100 text-amber-800 border border-amber-200'
                          : 'bg-red-100 text-red-800 border border-red-200'
                      }`}>
                        {p.status}
                      </span>
                    </div>
                    <div className="text-xs font-semibold text-unerg-blue mt-0.5">
                      Docente: {teacher ? `${teacher.name} ${teacher.surName}` : p.teacher_name} (V-{p.teacher_cdi})
                    </div>
                  </div>
                </div>

                <p className="text-xs text-slate-700 bg-slate-50 p-3 rounded-lg border border-slate-100">
                  {p.reason}
                </p>

                <div className="grid grid-cols-2 gap-2 text-[11px] text-slate-500">
                  <div>Vigencia: <strong className="text-slate-800">{p.start_date} al {p.end_date}</strong></div>
                  <div>Semestre Lectivo: <strong className="text-slate-800">{p.semester}</strong></div>
                </div>

                {p.observations && (
                  <div className="text-[11px] text-slate-600 bg-blue-50/70 p-2.5 rounded-lg border border-blue-100">
                    <span className="font-semibold text-unerg-blue">Dictamen / Observaciones:</span> {p.observations}
                  </div>
                )}

                {/* Workflow Actions for Area Manager / Admin */}
                {(isSuperAdmin || isAreaManager) && p.status === 'Pendiente' && (
                  <div className="pt-2 border-t border-slate-100 flex items-center justify-end gap-2">
                    <button
                      onClick={() => updatePermissionStatus(p.id, 'Rechazado', 'No cumple con los requisitos del reglamento de permisos.')}
                      className="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 text-xs font-semibold rounded-lg border border-red-200 transition-colors flex items-center gap-1"
                    >
                      <XCircle className="w-3.5 h-3.5" /> Rechazar
                    </button>
                    <button
                      onClick={() => updatePermissionStatus(p.id, 'Aprobado', 'Aprobado por el Consejo de Área conforme a normativa.')}
                      className="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-xs transition-colors flex items-center gap-1"
                    >
                      <CheckCircle2 className="w-3.5 h-3.5" /> Aprobar Permiso
                    </button>
                  </div>
                )}
              </div>
            );
          })
        )}
      </div>
    );
  })()}

      {/* Modal: Solicitar Permiso */}
      {showRequestModal && (
        <div className="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95">
            <div className="bg-unerg-blue text-white p-4 flex items-center justify-between">
              <h3 className="font-bold text-sm flex items-center gap-2">
                <CalendarDays className="w-4 h-4 text-amber-300" />
                Nueva Solicitud de Permiso o Licencia
              </h3>
              <button onClick={() => setShowRequestModal(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleRequestSubmit} className="p-6 space-y-3 text-xs">
              {!isTeacher && (
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Docente Solicitante:</label>
                  <select
                    value={formTeacherCdi}
                    onChange={(e) => setFormTeacherCdi(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                  >
                    {teachers.map(t => (
                      <option key={t.cdi} value={t.cdi}>
                        {t.name} {t.surName} (V-{t.cdi})
                      </option>
                    ))}
                  </select>
                </div>
              )}

              <div>
                <label className="block font-semibold text-slate-700 mb-1">Modalidad de Permiso:</label>
                <select
                  value={formType}
                  onChange={(e) => setFormType(e.target.value as PermissionTeacher['type'])}
                  className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                >
                  <option value="Permiso Académico">Permiso Académico</option>
                  <option value="Año Sabático">Año Sabático</option>
                  <option value="Comisión de Servicio">Comisión de Servicio</option>
                  <option value="Licencia Médica">Licencia Médica</option>
                  <option value="Incapacidad Médica">Incapacidad Médica</option>
                  <option value="Prórroga de Estudios">Prórroga de Estudios</option>
                  <option value="Permiso por Cuido">Permiso por Cuido</option>
                  <option value="Permiso Especial">Permiso Especial</option>
                  <option value="Permiso No Remunerado">Permiso No Remunerado</option>
                </select>
              </div>

              <div>
                <label className="block font-semibold text-slate-700 mb-1">Motivo Justificado / Descripción:</label>
                <textarea
                  required
                  rows={3}
                  placeholder="Detalle los fundamentos y objetivos de la solicitud..."
                  value={formReason}
                  onChange={(e) => setFormReason(e.target.value)}
                  className="w-full p-2 border border-slate-300 rounded-lg"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Fecha de Inicio:</label>
                  <input
                    type="date"
                    required
                    value={formStartDate}
                    onChange={(e) => setFormStartDate(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                  />
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Fecha de Culminación:</label>
                  <input
                    type="date"
                    required
                    value={formEndDate}
                    onChange={(e) => setFormEndDate(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                  />
                </div>
              </div>

              <div>
                <label className="block font-semibold text-slate-700 mb-1">Semestre Lectivo:</label>
                <input
                  type="text"
                  required
                  value={formSemester}
                  onChange={(e) => setFormSemester(e.target.value)}
                  className="w-full p-2 border border-slate-300 rounded-lg font-mono"
                />
              </div>

              <div className="pt-4 border-t border-slate-200 flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setShowRequestModal(false)}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-unerg-blue hover:bg-unerg-blue-light text-white font-bold rounded-lg shadow-sm"
                >
                  Enviar Solicitud
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
