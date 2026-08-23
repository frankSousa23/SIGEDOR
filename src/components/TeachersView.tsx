import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { Teacher } from '../types';
import {
  Search,
  Plus,
  Filter,
  Eye,
  Edit2,
  Trash2,
  FileText,
  Download,
  GraduationCap,
  Award,
  Clock,
  Building2,
  FileCheck2,
  X,
  Phone,
  Mail,
  Calendar,
  BookOpen,
  CheckCircle2,
  Printer
} from 'lucide-react';
import { OfficialDocumentModal } from './OfficialDocumentModal';

export const TeachersView: React.FC = () => {
  const {
    teachers,
    categories,
    dedications,
    sites,
    permissions,
    reports,
    sedes,
    areas,
    programas,
    addTeacher,
    updateTeacher,
    deleteTeacher,
    currentUser
  } = useApp();

  const [searchTerm, setSearchTerm] = useState('');
  const [selectedSede, setSelectedSede] = useState('');
  const [selectedArea, setSelectedArea] = useState('');
  const [selectedGenre, setSelectedGenre] = useState('');

  // Modals state
  const [selectedTeacherForDetail, setSelectedTeacherForDetail] = useState<Teacher | null>(null);
  const [detailActiveTab, setDetailActiveTab] = useState<'info' | 'category' | 'dedication' | 'sites' | 'permissions' | 'reports'>('info');
  const [isFormModalOpen, setIsFormModalOpen] = useState(false);
  const [editingTeacher, setEditingTeacher] = useState<Teacher | null>(null);

  // Official PDF Memo Modal state
  const [officialDocProps, setOfficialDocProps] = useState<{
    title: string;
    memoNumber: string;
    bodyContent: React.ReactNode;
    recipient?: string;
    subject?: string;
  } | null>(null);

  // Form state
  const [formData, setFormData] = useState({
    name: '',
    surName: '',
    cdi: '',
    genre: 'M' as 'M' | 'F',
    phone: '',
    email: '',
    birthDate: '1985-01-01',
    datePromotion: '2015-01-01',
    asignaturePromotion: '',
    user_email: '',
    sede_nombre: sedes[0]?.nombre || 'Sede Central/San Juan de los Morros',
    area_nombre: areas[0]?.nombre || 'Ingeniería de sistemas',
    programa_nombre: programas[0]?.nombre || 'Ingeniería en Informática',
  });

  const handleOpenCreateModal = () => {
    setEditingTeacher(null);
    setFormData({
      name: '',
      surName: '',
      cdi: '',
      genre: 'M',
      phone: '',
      email: '',
      birthDate: '1985-01-01',
      datePromotion: new Date().toISOString().split('T')[0],
      asignaturePromotion: '',
      user_email: '',
      sede_nombre: sedes[0]?.nombre || '',
      area_nombre: areas[0]?.nombre || '',
      programa_nombre: programas[0]?.nombre || '',
    });
    setIsFormModalOpen(true);
  };

  const handleOpenEditModal = (teacher: Teacher) => {
    setEditingTeacher(teacher);
    setFormData({
      name: teacher.name,
      surName: teacher.surName,
      cdi: teacher.cdi,
      genre: teacher.genre,
      phone: teacher.phone,
      email: teacher.email,
      birthDate: teacher.birthDate,
      datePromotion: teacher.datePromotion,
      asignaturePromotion: teacher.asignaturePromotion,
      user_email: teacher.user_email,
      sede_nombre: teacher.sede_nombre,
      area_nombre: teacher.area_nombre,
      programa_nombre: teacher.programa_nombre,
    });
    setIsFormModalOpen(true);
  };

  const handleFormSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (editingTeacher) {
      updateTeacher(editingTeacher.id, formData);
    } else {
      addTeacher(formData);
    }
    setIsFormModalOpen(false);
  };

  // Filtered teachers list
  const filteredTeachers = teachers.filter(t => {
    const matchesSearch =
      t.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
      t.surName.toLowerCase().includes(searchTerm.toLowerCase()) ||
      t.cdi.toLowerCase().includes(searchTerm.toLowerCase()) ||
      t.email.toLowerCase().includes(searchTerm.toLowerCase()) ||
      t.asignaturePromotion.toLowerCase().includes(searchTerm.toLowerCase());

    const matchesSede = selectedSede ? t.sede_nombre === selectedSede : true;
    const matchesArea = selectedArea ? t.area_nombre === selectedArea : true;
    const matchesGenre = selectedGenre ? t.genre === selectedGenre : true;

    return matchesSearch && matchesSede && matchesArea && matchesGenre;
  });

  // Generate Official Teacher Record PDF Memo
  const handleGenerateTeacherRecordPdf = (teacher: Teacher) => {
    const cat = categories.find(c => c.teacher_cdi === teacher.cdi);
    const ded = dedications.find(d => d.teacher_cdi === teacher.cdi);
    const siteList = sites.filter(s => s.teacher_cdi === teacher.cdi);
    const permList = permissions.filter(p => p.teacher_cdi === teacher.cdi);

    setOfficialDocProps({
      title: 'EXPEDIENTE DOCENTE ACADÉMICO INTEGRAL',
      memoNumber: `EXP-UNERG-${teacher.cdi}-${new Date().getFullYear()}`,
      recipient: 'A quien pueda interesar / Comisión de Clasificación',
      subject: `Resumen de Hoja de Servicio Docente - ${teacher.name} ${teacher.surName}`,
      bodyContent: (
        <div className="space-y-4">
          <p>
            El <strong>Vicerrectorado Académico de la Universidad Nacional Experimental de los Llanos Centrales "Rómulo Gallegos"</strong>,
            hace constar por medio del presente documento los datos y trayectoria académica del docente universitario:
          </p>

          <div className="bg-slate-50 p-4 rounded border border-slate-200 text-xs grid grid-cols-2 gap-3">
            <div><strong>Nombres y Apellidos:</strong> {teacher.name} {teacher.surName}</div>
            <div><strong>Cédula de Identidad:</strong> {teacher.cdi}</div>
            <div><strong>Sede de Adscripción:</strong> {teacher.sede_nombre}</div>
            <div><strong>Área Académica:</strong> {teacher.area_nombre}</div>
            <div><strong>Programa de Formación:</strong> {teacher.programa_nombre}</div>
            <div><strong>Cátedra de Ingreso/Ascenso:</strong> {teacher.asignaturePromotion}</div>
            <div><strong>Fecha de Ingreso/Ascenso:</strong> {teacher.datePromotion}</div>
            <div><strong>Correo Institucional:</strong> {teacher.email}</div>
          </div>

          <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-1 mt-4">1. Situación en el Escalafón</h4>
          <p className="text-xs">
            Categoría Actual: <strong>{cat?.current_category || 'Instructor'}</strong> • Título de Pregrado: {cat?.preTitle || 'N/A'} • Título de Postgrado: {cat?.lastTitle || 'N/A'}.
          </p>

          <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-1 mt-4">2. Régimen de Dedicación y Carga</h4>
          <p className="text-xs">
            Dedicación: <strong>{ded?.name || 'Tiempo Completo'} ({ded?.hours || 30} horas semanales)</strong>
            {ded?.director && ` • Cargo Directivo: ${ded.director}`}.
            Tutoría de estudiantes: {ded?.studentNumber || 0} estudiantes ({ded?.studentHours || 0} horas semanales).
          </p>

          <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-1 mt-4">3. Cátedras y Secciones Asignadas</h4>
          <ul className="list-disc list-inside text-xs space-y-1">
            {siteList.map(s => (
              <li key={s.id}>
                {s.info || 'Cátedra Regular'} - {s.uc} UC ({s.weekHours} horas semanales, {s.sections} secciones) en {s.sede_nombre}.
              </li>
            ))}
          </ul>

          <p className="text-xs text-slate-600 mt-4">
            Documento emitido conforme a los reglamentos del personal docente y de investigación de la UNERG para los fines administrativos y legales correspondientes.
          </p>
        </div>
      )
    });
  };

  return (
    <div className="space-y-6">
      {/* Top Header & Actions */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-xl font-bold text-slate-900 font-serif">Expedientes de Docentes Ordinarios</h1>
          <p className="text-xs text-slate-500">Gestión de datos filiatorios, adscripción por áreas y registro de ascensos</p>
        </div>

        <div className="flex items-center gap-2">
          <button
            onClick={handleOpenCreateModal}
            className="bg-[#003366] hover:bg-[#002244] text-white text-xs font-bold px-3.5 py-2 rounded-lg transition-colors flex items-center space-x-1.5 shadow-sm"
          >
            <Plus className="w-4 h-4 text-amber-400" />
            <span>Nuevo Docente</span>
          </button>
        </div>
      </div>

      {/* Filter & Search Bar */}
      <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm space-y-3">
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
          {/* Search Box */}
          <div className="relative sm:col-span-2 lg:col-span-1">
            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
            <input
              type="text"
              placeholder="Buscar por CDI, nombre, asignatura..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none"
            />
          </div>

          {/* Sede Filter */}
          <div>
            <select
              value={selectedSede}
              onChange={(e) => setSelectedSede(e.target.value)}
              className="w-full py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none text-slate-700"
            >
              <option value="">Todas las Sedes</option>
              {sedes.map(s => (
                <option key={s.id} value={s.nombre}>{s.nombre}</option>
              ))}
            </select>
          </div>

          {/* Área Filter */}
          <div>
            <select
              value={selectedArea}
              onChange={(e) => setSelectedArea(e.target.value)}
              className="w-full py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none text-slate-700"
            >
              <option value="">Todas las Áreas</option>
              {areas.map(a => (
                <option key={a.id} value={a.nombre}>{a.nombre}</option>
              ))}
            </select>
          </div>

          {/* Género Filter */}
          <div>
            <select
              value={selectedGenre}
              onChange={(e) => setSelectedGenre(e.target.value)}
              className="w-full py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none text-slate-700"
            >
              <option value="">Todos los Géneros</option>
              <option value="M">Masculino (M)</option>
              <option value="F">Femenino (F)</option>
            </select>
          </div>
        </div>

        <div className="flex items-center justify-between text-xs text-slate-500 pt-1 border-t border-slate-100">
          <span>Mostrando <strong>{filteredTeachers.length}</strong> de {teachers.length} docentes registrados</span>
          {(searchTerm || selectedSede || selectedArea || selectedGenre) && (
            <button
              onClick={() => {
                setSearchTerm('');
                setSelectedSede('');
                setSelectedArea('');
                setSelectedGenre('');
              }}
              className="text-[#003366] hover:underline font-medium"
            >
              Limpiar Filtros
            </button>
          )}
        </div>
      </div>

      {/* Teachers Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs text-slate-700">
            <thead className="bg-[#003366] text-white uppercase text-[11px] font-semibold tracking-wider">
              <tr>
                <th className="py-3 px-4">C.I. / Docente</th>
                <th className="py-3 px-4">Área y Sede</th>
                <th className="py-3 px-4">Programa / Asignatura</th>
                <th className="py-3 px-4">Escalafón</th>
                <th className="py-3 px-4">Dedicación</th>
                <th className="py-3 px-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 font-sans">
              {filteredTeachers.length === 0 ? (
                <tr>
                  <td colSpan={6} className="text-center py-8 text-slate-500">
                    No se encontraron docentes con los criterios seleccionados.
                  </td>
                </tr>
              ) : (
                filteredTeachers.map(teacher => {
                  const cat = categories.find(c => c.teacher_cdi === teacher.cdi);
                  const ded = dedications.find(d => d.teacher_cdi === teacher.cdi);

                  return (
                    <tr key={teacher.id} className="hover:bg-slate-50 transition-colors">
                      <td className="py-3 px-4">
                        <div className="font-bold text-slate-900">
                          {teacher.name} {teacher.surName}
                        </div>
                        <div className="text-slate-500 text-[11px] flex items-center space-x-1 mt-0.5">
                          <span className="font-mono bg-slate-100 px-1 py-0.2 rounded text-slate-700">C.I. {teacher.cdi}</span>
                          <span>•</span>
                          <span>{teacher.genre === 'F' ? 'Femenino' : 'Masculino'}</span>
                        </div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="font-medium text-slate-800">{teacher.area_nombre}</div>
                        <div className="text-[11px] text-slate-500">{teacher.sede_nombre}</div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="text-slate-800">{teacher.programa_nombre}</div>
                        <div className="text-[11px] text-amber-800 font-medium">{teacher.asignaturePromotion}</div>
                      </td>

                      <td className="py-3 px-4">
                        <span className={`inline-block px-2 py-0.5 rounded text-[11px] font-bold ${
                          cat?.current_category === 'Titular' ? 'bg-amber-100 text-amber-900 border border-amber-300' :
                          cat?.current_category === 'Asociado' ? 'bg-blue-100 text-blue-900' :
                          cat?.current_category === 'Agregado' ? 'bg-indigo-100 text-indigo-900' :
                          cat?.current_category === 'Asistente' ? 'bg-emerald-100 text-emerald-900' :
                          'bg-slate-100 text-slate-800'
                        }`}>
                          {cat?.current_category || 'Instructor'}
                        </span>
                      </td>

                      <td className="py-3 px-4">
                        <div className="font-medium text-slate-800">{ded?.name || 'Tiempo Completo'}</div>
                        <div className="text-[11px] text-slate-500">{ded?.hours || 30}h semanales {ded?.director && `(${ded.director})`}</div>
                      </td>

                      <td className="py-3 px-4 text-center">
                        <div className="flex items-center justify-center space-x-1">
                          <button
                            onClick={() => setSelectedTeacherForDetail(teacher)}
                            className="p-1.5 text-blue-700 hover:bg-blue-50 rounded-lg transition-colors"
                            title="Ver Expediente Integral"
                          >
                            <Eye className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => handleGenerateTeacherRecordPdf(teacher)}
                            className="p-1.5 text-amber-700 hover:bg-amber-50 rounded-lg transition-colors"
                            title="Generar Ficha PDF Oficial"
                          >
                            <FileText className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => handleOpenEditModal(teacher)}
                            className="p-1.5 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                            title="Editar Datos"
                          >
                            <Edit2 className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => {
                              if (confirm(`¿Está seguro de eliminar el expediente del docente ${teacher.name} ${teacher.surName}?`)) {
                                deleteTeacher(teacher.id);
                              }
                            }}
                            className="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Eliminar Docente"
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

      {/* Detailed Full Record Modal */}
      {selectedTeacherForDetail && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden border border-slate-300">
            {/* Modal Header */}
            <div className="bg-[#003366] text-white px-6 py-4 flex items-center justify-between border-b border-amber-500">
              <div className="flex items-center space-x-3">
                <div className="w-10 h-10 rounded-full bg-white text-[#003366] font-bold flex items-center justify-center text-base">
                  {selectedTeacherForDetail.name.charAt(0)}
                </div>
                <div>
                  <h3 className="font-bold text-base">{selectedTeacherForDetail.name} {selectedTeacherForDetail.surName}</h3>
                  <p className="text-xs text-slate-200">C.I. {selectedTeacherForDetail.cdi} • {selectedTeacherForDetail.area_nombre}</p>
                </div>
              </div>
              <div className="flex items-center space-x-2">
                <button
                  onClick={() => handleGenerateTeacherRecordPdf(selectedTeacherForDetail)}
                  className="bg-amber-500 hover:bg-amber-600 text-slate-950 px-3 py-1.5 rounded-lg text-xs font-bold flex items-center space-x-1 transition-colors"
                >
                  <Printer className="w-3.5 h-3.5" />
                  <span>Imprimir Ficha</span>
                </button>
                <button
                  onClick={() => setSelectedTeacherForDetail(null)}
                  className="text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10"
                >
                  <X className="w-5 h-5" />
                </button>
              </div>
            </div>

            {/* Navigation Tabs */}
            <div className="flex border-b border-slate-200 bg-slate-50 px-6 text-xs font-medium overflow-x-auto">
              {[
                { id: 'info', label: 'Datos Personales', icon: GraduationCap },
                { id: 'category', label: 'Escalafón y Ascensos', icon: Award },
                { id: 'dedication', label: 'Dedicación y Carga', icon: Clock },
                { id: 'sites', label: 'Cátedras y Sedes', icon: Building2 },
                { id: 'permissions', label: 'Permisos', icon: FileCheck2 },
                { id: 'reports', label: 'Reportes Emitidos', icon: FileText },
              ].map(tab => {
                const Icon = tab.icon;
                const isActive = detailActiveTab === tab.id;
                return (
                  <button
                    key={tab.id}
                    onClick={() => setDetailActiveTab(tab.id as any)}
                    className={`flex items-center space-x-1.5 py-3 px-4 border-b-2 font-semibold whitespace-nowrap transition-colors ${
                      isActive
                        ? 'border-[#003366] text-[#003366] bg-white'
                        : 'border-transparent text-slate-500 hover:text-slate-800'
                    }`}
                  >
                    <Icon className="w-4 h-4" />
                    <span>{tab.label}</span>
                  </button>
                );
              })}
            </div>

            {/* Tab Contents */}
            <div className="p-6 overflow-y-auto flex-1 text-xs text-slate-700 space-y-4">
              {detailActiveTab === 'info' && (
                <div className="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <div className="bg-slate-50 p-4 rounded-lg border border-slate-200 space-y-2">
                    <h4 className="font-bold text-slate-900 uppercase text-[11px] tracking-wider mb-2">Información de Identidad</h4>
                    <p><strong>Nombres:</strong> {selectedTeacherForDetail.name}</p>
                    <p><strong>Apellidos:</strong> {selectedTeacherForDetail.surName}</p>
                    <p><strong>Cédula:</strong> {selectedTeacherForDetail.cdi}</p>
                    <p><strong>Género:</strong> {selectedTeacherForDetail.genre === 'F' ? 'Femenino' : 'Masculino'}</p>
                    <p><strong>Fecha de Nacimiento:</strong> {selectedTeacherForDetail.birthDate}</p>
                    <p><strong>Teléfono:</strong> {selectedTeacherForDetail.phone || 'No registrado'}</p>
                    <p><strong>Correo Electrónico:</strong> {selectedTeacherForDetail.email}</p>
                  </div>

                  <div className="bg-slate-50 p-4 rounded-lg border border-slate-200 space-y-2">
                    <h4 className="font-bold text-slate-900 uppercase text-[11px] tracking-wider mb-2">Adscripción Universitaria</h4>
                    <p><strong>Sede:</strong> {selectedTeacherForDetail.sede_nombre}</p>
                    <p><strong>Área:</strong> {selectedTeacherForDetail.area_nombre}</p>
                    <p><strong>Programa:</strong> {selectedTeacherForDetail.programa_nombre}</p>
                    <p><strong>Cátedra de Ingreso:</strong> {selectedTeacherForDetail.asignaturePromotion}</p>
                    <p><strong>Fecha de Ascenso/Ingreso:</strong> {selectedTeacherForDetail.datePromotion}</p>
                    <p><strong>Usuario Asociado:</strong> {selectedTeacherForDetail.user_email || 'Sin cuenta enlazada'}</p>
                  </div>
                </div>
              )}

              {detailActiveTab === 'category' && (
                <div>
                  {(() => {
                    const cat = categories.find(c => c.teacher_cdi === selectedTeacherForDetail.cdi);
                    return (
                      <div className="space-y-4">
                        <div className="bg-amber-50 p-4 rounded-lg border border-amber-200 flex items-center justify-between">
                          <div>
                            <span className="text-[11px] font-bold uppercase text-amber-800">Categoría Actual en Escalafón</span>
                            <h3 className="text-xl font-bold text-[#003366]">{cat?.current_category || 'Instructor'}</h3>
                            <p className="text-xs text-slate-600 mt-1">{cat?.info || 'Sin observaciones de escalafón'}</p>
                          </div>
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                          <div className="bg-slate-50 p-4 rounded-lg border border-slate-200 space-y-2">
                            <h4 className="font-bold text-slate-900">Títulos Académicos</h4>
                            <p><strong>Título de Pregrado:</strong> {cat?.preTitle || 'No especificado'}</p>
                            <p><strong>Título de Posgrado:</strong> {cat?.lastTitle || 'No especificado'}</p>
                            <p><strong>Excepción de Asistente:</strong> {cat?.disable_assistant_rule ? 'Activada' : 'Normal (Requiere Postgrado)'}</p>
                          </div>

                          <div className="bg-slate-50 p-4 rounded-lg border border-slate-200 space-y-2">
                            <h4 className="font-bold text-slate-900">Cronología de Ascensos</h4>
                            <p><strong>Instructor:</strong> {cat?.instructor || 'Fecha inicial'}</p>
                            <p><strong>Asistente:</strong> {cat?.asistente || 'No aplica'}</p>
                            <p><strong>Agregado:</strong> {cat?.agregado || 'No aplica'}</p>
                            <p><strong>Asociado:</strong> {cat?.asociado || 'No aplica'}</p>
                            <p><strong>Titular:</strong> {cat?.titular || 'No aplica'}</p>
                          </div>
                        </div>
                      </div>
                    );
                  })()}
                </div>
              )}

              {detailActiveTab === 'dedication' && (
                <div>
                  {(() => {
                    const ded = dedications.find(d => d.teacher_cdi === selectedTeacherForDetail.cdi);
                    return (
                      <div className="space-y-4">
                        <div className="bg-emerald-50 p-4 rounded-lg border border-emerald-200 flex items-center justify-between">
                          <div>
                            <span className="text-[11px] font-bold uppercase text-emerald-800">Régimen de Dedicación</span>
                            <h3 className="text-xl font-bold text-emerald-950">{ded?.name || 'Tiempo Completo'} ({ded?.hours || 30} Horas)</h3>
                            <p className="text-xs text-slate-600 mt-1">{ded?.director ? `Ejerce cargo de: ${ded.director}` : 'Sin cargo directivo asignado'}</p>
                          </div>
                        </div>

                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                          <div className="bg-slate-50 p-4 rounded-lg border border-slate-200 space-y-2">
                            <h4 className="font-bold text-slate-900">Carga Académica y Estudiantil</h4>
                            <p><strong>Estudiantes a Cargo:</strong> {ded?.studentNumber || 0} alumnos</p>
                            <p><strong>Horas de Asesoría/Tutoría:</strong> {ded?.studentHours || 0} horas semanales</p>
                            <p><strong>Detalles:</strong> {ded?.info || 'Asignación regular'}</p>
                          </div>
                        </div>
                      </div>
                    );
                  })()}
                </div>
              )}

              {detailActiveTab === 'sites' && (
                <div>
                  <h4 className="font-bold text-slate-900 mb-2">Asignaciones en Sedes y Cátedras</h4>
                  <div className="space-y-2">
                    {sites.filter(s => s.teacher_cdi === selectedTeacherForDetail.cdi).length === 0 ? (
                      <p className="text-slate-500 py-4 text-center">No tiene asignaciones registradas.</p>
                    ) : (
                      sites.filter(s => s.teacher_cdi === selectedTeacherForDetail.cdi).map(s => (
                        <div key={s.id} className="p-3 bg-slate-50 rounded-lg border border-slate-200 flex justify-between items-center">
                          <div>
                            <p className="font-bold text-slate-900">{s.info || 'Cátedra Regular'}</p>
                            <p className="text-slate-500">{s.sede_nombre} • {s.area_nombre} ({s.programa_nombre})</p>
                          </div>
                          <div className="text-right">
                            <span className="font-bold text-[#003366]">{s.uc} UC</span> • <span>{s.weekHours}h/sem</span> • <span>{s.sections} Secciones</span>
                          </div>
                        </div>
                      ))
                    )}
                  </div>
                </div>
              )}

              {detailActiveTab === 'permissions' && (
                <div>
                  <h4 className="font-bold text-slate-900 mb-2">Historial de Permisos y Licencias</h4>
                  <div className="space-y-2">
                    {permissions.filter(p => p.teacher_cdi === selectedTeacherForDetail.cdi).length === 0 ? (
                      <p className="text-slate-500 py-4 text-center">No tiene solicitudes de permisos registradas.</p>
                    ) : (
                      permissions.filter(p => p.teacher_cdi === selectedTeacherForDetail.cdi).map(p => (
                        <div key={p.id} className="p-3 bg-slate-50 rounded-lg border border-slate-200 flex justify-between items-center">
                          <div>
                            <div className="flex items-center space-x-2">
                              <span className="font-bold text-slate-900">{p.type}</span>
                              <span className="font-mono text-slate-500">({p.memo_number})</span>
                            </div>
                            <p className="text-slate-500 mt-0.5">{p.description}</p>
                            <p className="text-[11px] text-slate-400">Vigencia: {p.start_date} al {p.end_date} • {p.is_paid ? 'Remunerado' : 'No Remunerado'}</p>
                          </div>
                          <span className={`px-2 py-0.5 rounded text-[10px] font-bold ${
                            p.status === 'approved' ? 'bg-emerald-100 text-emerald-800' :
                            p.status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-amber-100 text-amber-800'
                          }`}>
                            {p.status.toUpperCase()}
                          </span>
                        </div>
                      ))
                    )}
                  </div>
                </div>
              )}

              {detailActiveTab === 'reports' && (
                <div>
                  <h4 className="font-bold text-slate-900 mb-2">Reportes y Memorandos Emitidos</h4>
                  <div className="space-y-2">
                    {reports.filter(r => r.teacher_cdi === selectedTeacherForDetail.cdi).length === 0 ? (
                      <p className="text-slate-500 py-4 text-center">No hay reportes o memorandos emitidos para este docente.</p>
                    ) : (
                      reports.filter(r => r.teacher_cdi === selectedTeacherForDetail.cdi).map(r => (
                        <div key={r.id} className="p-3 bg-slate-50 rounded-lg border border-slate-200 flex justify-between items-center">
                          <div>
                            <div className="font-bold text-slate-900">{r.typeReport}</div>
                            <div className="text-slate-500">{r.memoNumber} • {r.created_at}</div>
                            <div className="text-xs text-slate-600 mt-1 line-clamp-2">{r.report}</div>
                          </div>
                        </div>
                      ))
                    )}
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      )}

      {/* Create / Edit Teacher Modal */}
      {isFormModalOpen && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300">
            <div className="bg-[#003366] text-white px-6 py-4 flex items-center justify-between border-b border-amber-500">
              <h3 className="font-bold text-base">
                {editingTeacher ? 'Editar Expediente Docente' : 'Registrar Nuevo Docente'}
              </h3>
              <button onClick={() => setIsFormModalOpen(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleFormSubmit} className="p-6 space-y-4 text-xs text-slate-700">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Nombres *</label>
                  <input
                    type="text"
                    required
                    value={formData.name}
                    onChange={(e) => setFormData({ ...formData, name: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Carlos Eduardo"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Apellidos *</label>
                  <input
                    type="text"
                    required
                    value={formData.surName}
                    onChange={(e) => setFormData({ ...formData, surName: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Mendoza Rojas"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Cédula de Identidad (C.I.) *</label>
                  <input
                    type="text"
                    required
                    value={formData.cdi}
                    onChange={(e) => setFormData({ ...formData, cdi: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. 10101001"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Género</label>
                  <select
                    value={formData.genre}
                    onChange={(e) => setFormData({ ...formData, genre: e.target.value as 'M' | 'F' })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    <option value="M">Masculino (M)</option>
                    <option value="F">Femenino (F)</option>
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Teléfono</label>
                  <input
                    type="text"
                    value={formData.phone}
                    onChange={(e) => setFormData({ ...formData, phone: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="+58 412 1234567"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Correo Electrónico *</label>
                  <input
                    type="email"
                    required
                    value={formData.email}
                    onChange={(e) => setFormData({ ...formData, email: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="docente@unerg.edu.ve"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Fecha de Nacimiento</label>
                  <input
                    type="date"
                    value={formData.birthDate}
                    onChange={(e) => setFormData({ ...formData, birthDate: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Fecha de Ingreso / Ascenso</label>
                  <input
                    type="date"
                    value={formData.datePromotion}
                    onChange={(e) => setFormData({ ...formData, datePromotion: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Cátedra de Ascenso / Especialidad</label>
                  <input
                    type="text"
                    value={formData.asignaturePromotion}
                    onChange={(e) => setFormData({ ...formData, asignaturePromotion: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Estructuras de Datos y Algoritmos"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Sede de Adscripción</label>
                  <select
                    value={formData.sede_nombre}
                    onChange={(e) => setFormData({ ...formData, sede_nombre: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    {sedes.map(s => (
                      <option key={s.id} value={s.nombre}>{s.nombre}</option>
                    ))}
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Área Académica</label>
                  <select
                    value={formData.area_nombre}
                    onChange={(e) => setFormData({ ...formData, area_nombre: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    {areas.map(a => (
                      <option key={a.id} value={a.nombre}>{a.nombre}</option>
                    ))}
                  </select>
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Programa Académico</label>
                  <select
                    value={formData.programa_nombre}
                    onChange={(e) => setFormData({ ...formData, programa_nombre: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    {programas.map(p => (
                      <option key={p.id} value={p.nombre}>{p.nombre}</option>
                    ))}
                  </select>
                </div>
              </div>

              <div className="flex justify-end space-x-2 pt-4 border-t border-slate-200">
                <button
                  type="button"
                  onClick={() => setIsFormModalOpen(false)}
                  className="px-4 py-2 border border-slate-300 rounded text-slate-700 hover:bg-slate-50 transition-colors"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-[#003366] hover:bg-[#002244] text-white font-bold rounded transition-colors shadow"
                >
                  {editingTeacher ? 'Guardar Cambios' : 'Registrar Docente'}
                </button>
              </div>
            </form>
          </div>
        </div>
      )}

      {/* Official PDF Document Modal */}
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
