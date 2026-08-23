import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { Dedication, DedicationType, DirectorPosition } from '../types';
import {
  Clock,
  Search,
  Edit2,
  FileText,
  CheckCircle2,
  Users,
  Briefcase,
  X
} from 'lucide-react';
import { OfficialDocumentModal } from './OfficialDocumentModal';

export const DedicationsView: React.FC = () => {
  const { dedications, teachers, saveDedication } = useApp();

  const [searchTerm, setSearchTerm] = useState('');
  const [selectedTypeFilter, setSelectedTypeFilter] = useState<string>('');
  const [editingDedication, setEditingDedication] = useState<Dedication | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  const [officialDocProps, setOfficialDocProps] = useState<{
    title: string;
    memoNumber: string;
    bodyContent: React.ReactNode;
    recipient?: string;
    subject?: string;
  } | null>(null);

  // Form state
  const [formData, setFormData] = useState<{
    id?: number;
    teacher_cdi: string;
    name: DedicationType;
    hours: number;
    director: DirectorPosition;
    studentNumber: number;
    studentHours: number;
    info: string;
  }>({
    teacher_cdi: '',
    name: 'Tiempo Completo',
    hours: 30,
    director: '',
    studentNumber: 30,
    studentHours: 4,
    info: '',
  });

  const handleDedicationTypeChange = (type: DedicationType) => {
    let hours = 30;
    if (type === 'Exclusiva') hours = 36;
    else if (type === 'Tiempo Completo') hours = 30;
    else if (type === 'Medio Tiempo') hours = 18;
    else if (type === 'Tiempo Convencional') hours = 12;

    setFormData(prev => ({
      ...prev,
      name: type,
      hours,
    }));
  };

  const handleOpenEditModal = (ded: Dedication) => {
    setEditingDedication(ded);
    setFormData({
      id: ded.id,
      teacher_cdi: ded.teacher_cdi,
      name: ded.name,
      hours: ded.hours,
      director: ded.director || '',
      studentNumber: ded.studentNumber || 0,
      studentHours: ded.studentHours || 0,
      info: ded.info || '',
    });
    setIsModalOpen(true);
  };

  const handleFormSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    saveDedication({
      id: formData.id,
      teacher_cdi: formData.teacher_cdi,
      name: formData.name,
      hours: formData.hours,
      director: formData.director,
      studentNumber: Number(formData.studentNumber),
      studentHours: Number(formData.studentHours),
      info: formData.info,
    });
    setIsModalOpen(false);
  };

  // Filtered
  const filteredDedications = dedications.filter(d => {
    const teacher = teachers.find(t => t.cdi === d.teacher_cdi);
    const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : '';
    
    const matchesSearch =
      d.teacher_cdi.toLowerCase().includes(searchTerm.toLowerCase()) ||
      teacherName.toLowerCase().includes(searchTerm.toLowerCase()) ||
      (d.director && d.director.toLowerCase().includes(searchTerm.toLowerCase()));

    const matchesType = selectedTypeFilter ? d.name === selectedTypeFilter : true;

    return matchesSearch && matchesType;
  });

  // Certificate Generator
  const handleGenerateCertificate = (ded: Dedication) => {
    const teacher = teachers.find(t => t.cdi === ded.teacher_cdi);
    if (!teacher) return;

    setOfficialDocProps({
      title: 'CERTIFICACIÓN DE DEDICACIÓN Y CARGA HORARIA DOCENTE',
      memoNumber: `CERT-DED-UNERG-${ded.teacher_cdi}-${new Date().getFullYear()}`,
      recipient: `${teacher.name} ${teacher.surName}`,
      subject: `Certificación oficial de régimen de dedicación - ${ded.name} (${ded.hours} Horas)`,
      bodyContent: (
        <div className="space-y-4">
          <p>
            La <strong>Dirección de Asuntos Profesorales del Vicerrectorado Académico de la Universidad Nacional Experimental de los Llanos Centrales "Rómulo Gallegos"</strong>:
          </p>

          <div className="p-4 bg-slate-50 border border-slate-200 rounded text-xs space-y-2">
            <h4 className="font-bold text-slate-900 text-sm">HACE CONSTAR:</h4>
            <p>
              Que el (la) docente universitario(a) <strong>{teacher.name} {teacher.surName}</strong>, C.I. Nº <strong>{teacher.cdi}</strong>,
              adscrito(a) al <strong>{teacher.area_nombre}</strong> ({teacher.sede_nombre}), cumple con el siguiente régimen de dedicación académica:
            </p>
            <div className="text-center py-2">
              <span className="text-base font-bold text-emerald-900 bg-emerald-50 px-4 py-1.5 rounded border border-emerald-300 inline-block uppercase">
                {ded.name} ({ded.hours} Horas Semanales)
              </span>
            </div>
          </div>

          <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-1">Desglose de Responsabilidades y Asignación</h4>
          <div className="text-xs space-y-2">
            <p><strong>Carga Horaria Total:</strong> {ded.hours} horas semanales reglamentarias.</p>
            {ded.director ? (
              <p><strong>Cargo Directivo / Administrativo Universitario:</strong> {ded.director}</p>
            ) : (
              <p><strong>Cargo Directivo:</strong> Docencia Regular de Cátedra.</p>
            )}
            <p><strong>Tutoría y Asesoría Estudiantil:</strong> {ded.studentNumber} estudiantes asignados ({ded.studentHours} horas semanales).</p>
            {ded.info && <p><strong>Observaciones de Nómina:</strong> {ded.info}</p>}
          </div>

          <p className="text-xs text-slate-600">
            Se expide la presente certificación a los fines pertinentes en San Juan de los Morros, Estado Guárico.
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
          <h1 className="text-xl font-bold text-slate-900 font-serif">Dedicación y Carga Horaria Docente</h1>
          <p className="text-xs text-slate-500">Gestión de jornadas laborales (Exclusiva, Tiempo Completo, Medio Tiempo, Tiempo Convencional)</p>
        </div>
      </div>

      {/* Search and Filters */}
      <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="flex flex-1 gap-3 w-full sm:w-auto">
          <div className="relative flex-1">
            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
            <input
              type="text"
              placeholder="Buscar por CDI, nombre del docente o cargo..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none"
            />
          </div>

          <select
            value={selectedTypeFilter}
            onChange={(e) => setSelectedTypeFilter(e.target.value)}
            className="py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none text-slate-700"
          >
            <option value="">Todos los Tipos</option>
            <option value="Exclusiva">Exclusiva (36h)</option>
            <option value="Tiempo Completo">Tiempo Completo (30h)</option>
            <option value="Medio Tiempo">Medio Tiempo (18h)</option>
            <option value="Tiempo Convencional">Tiempo Convencional (12h)</option>
          </select>
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs text-slate-700">
            <thead className="bg-[#003366] text-white uppercase text-[11px] font-semibold tracking-wider">
              <tr>
                <th className="py-3 px-4">C.I. / Docente</th>
                <th className="py-3 px-4">Tipo de Dedicación</th>
                <th className="py-3 px-4">Horas Semanales</th>
                <th className="py-3 px-4">Cargo Directivo</th>
                <th className="py-3 px-4">Tutoría Estudiantes</th>
                <th className="py-3 px-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 font-sans">
              {filteredDedications.length === 0 ? (
                <tr>
                  <td colSpan={6} className="text-center py-8 text-slate-500">
                    No se encontraron registros de dedicación.
                  </td>
                </tr>
              ) : (
                filteredDedications.map(ded => {
                  const teacher = teachers.find(t => t.cdi === ded.teacher_cdi);
                  const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : 'Docente';

                  return (
                    <tr key={ded.id} className="hover:bg-slate-50 transition-colors">
                      <td className="py-3 px-4">
                        <div className="font-bold text-slate-900">{teacherName}</div>
                        <div className="text-[11px] text-slate-500 font-mono">C.I. {ded.teacher_cdi}</div>
                      </td>

                      <td className="py-3 px-4">
                        <span className={`inline-block px-2.5 py-1 rounded text-xs font-bold ${
                          ded.name === 'Exclusiva' ? 'bg-amber-100 text-amber-900 border border-amber-300' :
                          ded.name === 'Tiempo Completo' ? 'bg-emerald-100 text-emerald-900' :
                          ded.name === 'Medio Tiempo' ? 'bg-indigo-100 text-indigo-900' :
                          'bg-slate-100 text-slate-800'
                        }`}>
                          {ded.name}
                        </span>
                      </td>

                      <td className="py-3 px-4">
                        <div className="font-bold text-slate-900">{ded.hours} horas / semana</div>
                      </td>

                      <td className="py-3 px-4">
                        {ded.director ? (
                          <span className="inline-flex items-center text-amber-900 bg-amber-50 px-2 py-0.5 rounded font-medium border border-amber-200">
                            <Briefcase className="w-3 h-3 mr-1" />
                            {ded.director}
                          </span>
                        ) : (
                          <span className="text-slate-400 italic">Ninguno</span>
                        )}
                      </td>

                      <td className="py-3 px-4">
                        <div className="text-slate-800 font-medium">{ded.studentNumber} estudiantes ({ded.studentHours}h)</div>
                      </td>

                      <td className="py-3 px-4 text-center">
                        <div className="flex items-center justify-center space-x-1.5">
                          <button
                            onClick={() => handleGenerateCertificate(ded)}
                            className="p-1.5 text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors flex items-center space-x-1"
                            title="Emitir Certificación de Dedicación"
                          >
                            <FileText className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => handleOpenEditModal(ded)}
                            className="p-1.5 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                            title="Editar Dedicación"
                          >
                            <Edit2 className="w-4 h-4" />
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

      {/* Edit Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300">
            <div className="bg-[#003366] text-white px-6 py-4 flex items-center justify-between border-b border-amber-500">
              <h3 className="font-bold text-base">Actualizar Carga y Dedicación Docente</h3>
              <button onClick={() => setIsModalOpen(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleFormSubmit} className="p-6 space-y-4 text-xs text-slate-700">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block font-semibold text-slate-800 mb-1">C.I. del Docente</label>
                  <input
                    type="text"
                    disabled
                    value={formData.teacher_cdi}
                    className="w-full p-2 bg-slate-100 border border-slate-300 rounded font-mono text-slate-600"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Tipo de Dedicación *</label>
                  <select
                    value={formData.name}
                    onChange={(e) => handleDedicationTypeChange(e.target.value as DedicationType)}
                    className="w-full p-2 border border-slate-300 rounded font-bold text-emerald-900 focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    <option value="Exclusiva">Exclusiva (36 Horas)</option>
                    <option value="Tiempo Completo">Tiempo Completo (30 Horas)</option>
                    <option value="Medio Tiempo">Medio Tiempo (18 Horas)</option>
                    <option value="Tiempo Convencional">Tiempo Convencional (12 Horas)</option>
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Horas Semanales Totales *</label>
                  <input
                    type="number"
                    required
                    value={formData.hours}
                    onChange={(e) => setFormData({ ...formData, hours: Number(e.target.value) })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Cargo Directivo / Administrativo</label>
                  <select
                    value={formData.director}
                    onChange={(e) => setFormData({ ...formData, director: e.target.value as DirectorPosition })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    <option value="">Ninguno (Docencia Regular)</option>
                    <option value="Coordinador">Coordinador(a)</option>
                    <option value="Jefe de Departamento">Jefe de Departamento</option>
                    <option value="Director">Director(a)</option>
                    <option value="Sub-Director">Sub-Director(a)</option>
                    <option value="Decano">Decano(a)</option>
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Estudiantes en Asesoría / Tutoría</label>
                  <input
                    type="number"
                    value={formData.studentNumber}
                    onChange={(e) => setFormData({ ...formData, studentNumber: Number(e.target.value) })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. 30"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Horas Dedicadas a Tutoría</label>
                  <input
                    type="number"
                    value={formData.studentHours}
                    onChange={(e) => setFormData({ ...formData, studentHours: Number(e.target.value) })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. 4"
                  />
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Observaciones / Detalles</label>
                  <textarea
                    rows={2}
                    value={formData.info}
                    onChange={(e) => setFormData({ ...formData, info: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Asignación conforme a resolución de Consejo Directivo..."
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
                  Guardar Dedicación
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
