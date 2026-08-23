import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { TeacherPermission, PermissionType, DurationType, PermissionStatus } from '../types';
import {
  FileCheck2,
  Search,
  Plus,
  CheckCircle,
  XCircle,
  Clock,
  FileText,
  Trash2,
  AlertCircle,
  X
} from 'lucide-react';
import { OfficialDocumentModal } from './OfficialDocumentModal';

export const PermissionsView: React.FC = () => {
  const {
    permissions,
    teachers,
    addPermission,
    updatePermissionStatus,
    deletePermission,
    currentUser
  } = useApp();

  const [searchTerm, setSearchTerm] = useState('');
  const [selectedStatusFilter, setSelectedStatusFilter] = useState('');
  const [selectedTypeFilter, setSelectedTypeFilter] = useState('');
  const [isModalOpen, setIsModalOpen] = useState(false);

  const [officialDocProps, setOfficialDocProps] = useState<{
    title: string;
    memoNumber: string;
    bodyContent: React.ReactNode;
    recipient?: string;
    subject?: string;
  } | null>(null);

  // Form
  const [formData, setFormData] = useState<{
    teacher_cdi: string;
    memo_number: string;
    type: PermissionType;
    duration_type: DurationType;
    start_date: string;
    end_date: string;
    status: PermissionStatus;
    is_paid: boolean;
    description: string;
  }>({
    teacher_cdi: teachers[0]?.cdi || '',
    memo_number: `MEMO-UNERG-VA-${new Date().getFullYear()}-${Math.floor(100 + Math.random() * 900)}`,
    type: 'Comisión de Servicio',
    duration_type: 'semestral',
    start_date: new Date().toISOString().split('T')[0],
    end_date: new Date(Date.now() + 180 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
    status: 'pending',
    is_paid: true,
    description: '',
  });

  const handleOpenCreateModal = () => {
    setFormData({
      teacher_cdi: teachers[0]?.cdi || '',
      memo_number: `MEMO-UNERG-VA-${new Date().getFullYear()}-${Math.floor(100 + Math.random() * 900)}`,
      type: 'Comisión de Servicio',
      duration_type: 'semestral',
      start_date: new Date().toISOString().split('T')[0],
      end_date: new Date(Date.now() + 180 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
      status: currentUser.roles.includes('admin') || currentUser.roles.includes('area_manager') ? 'approved' : 'pending',
      is_paid: true,
      description: '',
    });
    setIsModalOpen(true);
  };

  const handleFormSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    addPermission(formData);
    setIsModalOpen(false);
  };

  const filteredPermissions = permissions.filter(p => {
    const teacher = teachers.find(t => t.cdi === p.teacher_cdi);
    const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : '';

    const matchesSearch =
      p.memo_number.toLowerCase().includes(searchTerm.toLowerCase()) ||
      p.teacher_cdi.toLowerCase().includes(searchTerm.toLowerCase()) ||
      teacherName.toLowerCase().includes(searchTerm.toLowerCase()) ||
      p.description.toLowerCase().includes(searchTerm.toLowerCase());

    const matchesStatus = selectedStatusFilter ? p.status === selectedStatusFilter : true;
    const matchesType = selectedTypeFilter ? p.type === selectedTypeFilter : true;

    return matchesSearch && matchesStatus && matchesType;
  });

  // Official Memo Generator
  const handleGenerateOfficialMemo = (p: TeacherPermission) => {
    const teacher = teachers.find(t => t.cdi === p.teacher_cdi);
    if (!teacher) return;

    setOfficialDocProps({
      title: `RESOLUCIÓN DE CONCESIÓN DE ${p.type.toUpperCase()}`,
      memoNumber: p.memo_number,
      recipient: `${teacher.name} ${teacher.surName} (C.I. ${teacher.cdi})`,
      subject: `Notificación y Resolución de solicitud de permiso docente`,
      bodyContent: (
        <div className="space-y-4">
          <p>
            El <strong>Vicerrectorado Académico de la Universidad Nacional Experimental de los Llanos Centrales "Rómulo Gallegos"</strong>,
            en conformidad con el Reglamento General de Personal Docente y de Investigación:
          </p>

          <div className="p-4 bg-slate-50 border border-slate-200 rounded text-xs space-y-2">
            <h4 className="font-bold text-slate-900 text-sm">RESOLUCIÓN DE LA COMISIÓN ACADÉMICA:</h4>
            <p>
              Visto el expediente y la solicitud formal tramitada por el (la) ciudadano(a) <strong>{teacher.name} {teacher.surName}</strong>,
              titular de la Cédula de Identidad Nº <strong>{teacher.cdi}</strong>, adscrito(a) al <strong>{teacher.area_nombre}</strong> en la <strong>{teacher.sede_nombre}</strong>,
              se acuerda dictaminar el estado de:
            </p>
            <div className="text-center py-2">
              <span className={`text-base font-bold px-4 py-1.5 rounded border inline-block uppercase ${
                p.status === 'approved' ? 'bg-emerald-50 text-emerald-950 border-emerald-300' :
                p.status === 'rejected' ? 'bg-red-50 text-red-950 border-red-300' : 'bg-amber-50 text-amber-950 border-amber-300'
              }`}>
                {p.status === 'approved' ? 'SOLICITUD APROBADA' : p.status === 'rejected' ? 'SOLICITUD RECHAZADA' : 'EN TRÁMITE / PENDIENTE'}
              </span>
            </div>
          </div>

          <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-1">Términos y Condiciones del Permiso</h4>
          <div className="text-xs space-y-2">
            <p><strong>Tipo de Permiso / Licencia:</strong> {p.type}</p>
            <p><strong>Modalidad de Duración:</strong> {p.duration_type === 'semestral' ? 'Semestral (6 meses)' : p.duration_type === 'anual' ? 'Anual (12 meses)' : 'Temporal'}</p>
            <p><strong>Período de Vigencia:</strong> Desde el <strong>{p.start_date}</strong> hasta el <strong>{p.end_date}</strong></p>
            <p><strong>Régimen Remunerativo:</strong> {p.is_paid ? 'Con goce de sueldo (Remunerado)' : 'Sin goce de sueldo (No remunerado)'}</p>
            <p><strong>Motivo / Justificación:</strong> {p.description}</p>
          </div>

          <p className="text-xs text-slate-600">
            Regístrese, comuníquese a la Dirección de Recursos Humanos y a la Coordinación Académica del Área respectiva para los fines consiguientes.
          </p>
        </div>
      )
    });
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-xl font-bold text-slate-900 font-serif">Permisos y Licencias Docentes</h1>
          <p className="text-xs text-slate-500">Gestión y aprobación de años sabáticos, comisiones de servicio, reposos e incapacidades</p>
        </div>

        <button
          onClick={handleOpenCreateModal}
          className="bg-[#003366] hover:bg-[#002244] text-white text-xs font-bold px-3.5 py-2 rounded-lg transition-colors flex items-center space-x-1.5 shadow-sm"
        >
          <Plus className="w-4 h-4 text-amber-400" />
          <span>Solicitar Permiso</span>
        </button>
      </div>

      {/* Search and Filters */}
      <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="flex flex-1 gap-3 w-full sm:w-auto">
          <div className="relative flex-1">
            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
            <input
              type="text"
              placeholder="Buscar por Nº Memo, CDI o nombre de docente..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none"
            />
          </div>

          <select
            value={selectedStatusFilter}
            onChange={(e) => setSelectedStatusFilter(e.target.value)}
            className="py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none text-slate-700"
          >
            <option value="">Todos los Estados</option>
            <option value="pending">Pendientes</option>
            <option value="approved">Aprobados</option>
            <option value="rejected">Rechazados</option>
          </select>

          <select
            value={selectedTypeFilter}
            onChange={(e) => setSelectedTypeFilter(e.target.value)}
            className="py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none text-slate-700"
          >
            <option value="">Todos los Tipos</option>
            <option value="Año Sabático">Año Sabático</option>
            <option value="Comisión de Servicio">Comisión de Servicio</option>
            <option value="Prórroga de Comisión">Prórroga de Comisión</option>
            <option value="Incapacidad Médica">Incapacidad Médica</option>
            <option value="Permiso por Cuido">Permiso por Cuido</option>
            <option value="Beca o Formación Doctoral">Beca / Formación</option>
          </select>
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs text-slate-700">
            <thead className="bg-[#003366] text-white uppercase text-[11px] font-semibold tracking-wider">
              <tr>
                <th className="py-3 px-4">Nº Memorando / Docente</th>
                <th className="py-3 px-4">Tipo de Permiso</th>
                <th className="py-3 px-4">Duración y Fechas</th>
                <th className="py-3 px-4">Remuneración</th>
                <th className="py-3 px-4 text-center">Estado</th>
                <th className="py-3 px-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 font-sans">
              {filteredPermissions.length === 0 ? (
                <tr>
                  <td colSpan={6} className="text-center py-8 text-slate-500">
                    No se encontraron solicitudes de permisos.
                  </td>
                </tr>
              ) : (
                filteredPermissions.map(perm => {
                  const teacher = teachers.find(t => t.cdi === perm.teacher_cdi);
                  const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : 'Docente';

                  return (
                    <tr key={perm.id} className="hover:bg-slate-50 transition-colors">
                      <td className="py-3 px-4">
                        <div className="font-mono font-bold text-[#003366]">{perm.memo_number}</div>
                        <div className="font-medium text-slate-900 mt-0.5">{teacherName}</div>
                        <div className="text-[11px] text-slate-500">C.I. {perm.teacher_cdi}</div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="font-bold text-slate-800">{perm.type}</div>
                        <div className="text-[11px] text-slate-500 line-clamp-1 max-w-xs">{perm.description}</div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="font-semibold text-slate-800 capitalize">{perm.duration_type}</div>
                        <div className="text-[11px] text-slate-500">{perm.start_date} al {perm.end_date}</div>
                      </td>

                      <td className="py-3 px-4">
                        <span className={`inline-block px-2 py-0.5 rounded text-[11px] font-bold ${
                          perm.is_paid ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-200 text-slate-700'
                        }`}>
                          {perm.is_paid ? 'Remunerado' : 'No Remunerado'}
                        </span>
                      </td>

                      <td className="py-3 px-4 text-center">
                        <span className={`inline-block px-2.5 py-1 rounded text-xs font-bold ${
                          perm.status === 'approved' ? 'bg-emerald-100 text-emerald-900 border border-emerald-300' :
                          perm.status === 'rejected' ? 'bg-red-100 text-red-900 border border-red-300' :
                          'bg-amber-100 text-amber-900 border border-amber-300'
                        }`}>
                          {perm.status === 'approved' ? 'Aprobado' : perm.status === 'rejected' ? 'Rechazado' : 'Pendiente'}
                        </span>
                      </td>

                      <td className="py-3 px-4 text-center">
                        <div className="flex items-center justify-center space-x-1">
                          {/* Approve button */}
                          {perm.status !== 'approved' && (
                            <button
                              onClick={() => updatePermissionStatus(perm.id, 'approved')}
                              className="p-1.5 text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors"
                              title="Aprobar Permiso"
                            >
                              <CheckCircle className="w-4 h-4" />
                            </button>
                          )}

                          {/* Reject button */}
                          {perm.status !== 'rejected' && (
                            <button
                              onClick={() => updatePermissionStatus(perm.id, 'rejected')}
                              className="p-1.5 text-amber-700 hover:bg-amber-50 rounded-lg transition-colors"
                              title="Rechazar Permiso"
                            >
                              <XCircle className="w-4 h-4" />
                            </button>
                          )}

                          {/* Memo print */}
                          <button
                            onClick={() => handleGenerateOfficialMemo(perm)}
                            className="p-1.5 text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                            title="Generar Resolución Oficial / PDF"
                          >
                            <FileText className="w-4 h-4" />
                          </button>

                          {/* Delete */}
                          <button
                            onClick={() => {
                              if (confirm(`¿Eliminar permiso ${perm.memo_number}?`)) {
                                deletePermission(perm.id);
                              }
                            }}
                            className="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Eliminar Permiso"
                          >
                            <Trash2 className="w-4 h-4" />
                          </button>
                        </div>
                      </td>
                    </tr>
                  );
                })
              )}
            </tbody>
          </table>
        </div>
      </div>

      {/* Create Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300">
            <div className="bg-[#003366] text-white px-6 py-4 flex items-center justify-between border-b border-amber-500">
              <h3 className="font-bold text-base">Solicitud de Permiso / Licencia Docente</h3>
              <button onClick={() => setIsModalOpen(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleFormSubmit} className="p-6 space-y-4 text-xs text-slate-700">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Docente Solicitante *</label>
                  <select
                    value={formData.teacher_cdi}
                    onChange={(e) => setFormData({ ...formData, teacher_cdi: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    {teachers.map(t => (
                      <option key={t.id} value={t.cdi}>
                        {t.name} {t.surName} (C.I. {t.cdi}) - {t.area_nombre}
                      </option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Nº de Memorando / Expediente *</label>
                  <input
                    type="text"
                    required
                    value={formData.memo_number}
                    onChange={(e) => setFormData({ ...formData, memo_number: e.target.value })}
                    className="w-full p-2 font-mono border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none text-[#003366] font-bold"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Tipo de Permiso *</label>
                  <select
                    value={formData.type}
                    onChange={(e) => setFormData({ ...formData, type: e.target.value as PermissionType })}
                    className="w-full p-2 border border-slate-300 rounded font-bold text-slate-800 focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    <option value="Año Sabático">Año Sabático</option>
                    <option value="Comisión de Servicio">Comisión de Servicio</option>
                    <option value="Prórroga de Comisión">Prórroga de Comisión</option>
                    <option value="Incapacidad Médica">Incapacidad Médica</option>
                    <option value="Permiso por Cuido">Permiso por Cuido</option>
                    <option value="Permiso Especial No Remunerado">Permiso Especial No Remunerado</option>
                    <option value="Beca o Formación Doctoral">Beca o Formación Doctoral</option>
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Modalidad de Duración</label>
                  <select
                    value={formData.duration_type}
                    onChange={(e) => setFormData({ ...formData, duration_type: e.target.value as DurationType })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    <option value="semestral">Semestral (6 meses)</option>
                    <option value="anual">Anual (12 meses)</option>
                    <option value="temporal">Temporal / Especial</option>
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Estado de la Solicitud</label>
                  <select
                    value={formData.status}
                    onChange={(e) => setFormData({ ...formData, status: e.target.value as PermissionStatus })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none font-bold"
                  >
                    <option value="pending">Pendiente de Revisión</option>
                    <option value="approved">Aprobado</option>
                    <option value="rejected">Rechazado</option>
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Fecha de Inicio *</label>
                  <input
                    type="date"
                    required
                    value={formData.start_date}
                    onChange={(e) => setFormData({ ...formData, start_date: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Fecha de Culminación *</label>
                  <input
                    type="date"
                    required
                    value={formData.end_date}
                    onChange={(e) => setFormData({ ...formData, end_date: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div className="sm:col-span-2 flex items-center space-x-2">
                  <input
                    type="checkbox"
                    id="isPaid"
                    checked={formData.is_paid}
                    onChange={(e) => setFormData({ ...formData, is_paid: e.target.checked })}
                    className="h-4 w-4 text-[#003366] rounded focus:ring-[#003366]"
                  />
                  <label htmlFor="isPaid" className="text-slate-800 font-medium cursor-pointer">
                    Permiso con Goce de Sueldo (Remunerado)
                  </label>
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Justificación / Motivo del Permiso *</label>
                  <textarea
                    rows={3}
                    required
                    value={formData.description}
                    onChange={(e) => setFormData({ ...formData, description: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Detalles de la investigación, aval médico, institución de destino..."
                  />
                </div>
              </div>

              <div className="flex justify-end space-x-2 pt-4 border-t border-slate-200">
                <button
                  type="button"
                  onClick={() => setIsModalOpen(false)}
                  className="px-4 py-2 border border-slate-300 rounded text-slate-700 hover:bg-slate-50 transition-colors"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#003366] hover:bg-[#002244] text-white font-bold rounded transition-colors shadow"
                >
                  Registrar Solicitud
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Official PDF Modal */}
      {officialDocProps && (
        <OfficialDocumentModal
          title={officialDocProps.title}
          memoNumber={officialDocProps.memoNumber}
          recipient={officialDocProps.recipient}
          subject={officialDocProps.subject}
          bodyContent={officialDocProps.bodyContent}
          onClose={() => setOfficialDocProps(null)}
        />
      )}
    </div>
  );
};
