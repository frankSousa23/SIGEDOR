import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { ReportItem, ReportType } from '../types';
import {
  FileText,
  Search,
  Plus,
  Trash2,
  Printer,
  Download,
  Eye,
  CheckCircle2,
  X
} from 'lucide-react';
import { OfficialDocumentModal } from './OfficialDocumentModal';

export const ReportsView: React.FC = () => {
  const {
    reports,
    teachers,
    categories,
    dedications,
    sites,
    sedes,
    areas,
    addReport,
    deleteReport
  } = useApp();

  const [searchTerm, setSearchTerm] = useState('');
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
    memoNumber: string;
    teacher_cdi: string;
    typeReport: ReportType;
    email: string;
    sede_nombre: string;
    area_nombre: string;
    report: string;
    info: string;
  }>({
    memoNumber: `MEMO-UNERG-VA-${new Date().getFullYear()}-${Math.floor(100 + Math.random() * 900)}`,
    teacher_cdi: teachers[0]?.cdi || '',
    typeReport: 'Constancia de Trabajo',
    email: teachers[0]?.email || '',
    sede_nombre: teachers[0]?.sede_nombre || '',
    area_nombre: teachers[0]?.area_nombre || '',
    report: '',
    info: '',
  });

  const handleTeacherSelection = (cdi: string) => {
    const teacher = teachers.find(t => t.cdi === cdi);
    if (teacher) {
      setFormData(prev => ({
        ...prev,
        teacher_cdi: cdi,
        email: teacher.email,
        sede_nombre: teacher.sede_nombre,
        area_nombre: teacher.area_nombre,
      }));
    }
  };

  const handleTypeReportChange = (type: ReportType) => {
    const teacher = teachers.find(t => t.cdi === formData.teacher_cdi);
    const cat = categories.find(c => c.teacher_cdi === formData.teacher_cdi);
    const ded = dedications.find(d => d.teacher_cdi === formData.teacher_cdi);
    const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : 'Docente';

    let defaultText = '';
    if (type === 'Constancia de Trabajo') {
      defaultText = `Por medio de la presente se hace constar que el (la) ciudadano(a) ${teacherName}, titular de la C.I. ${formData.teacher_cdi}, presta sus servicios docentes en la Universidad Nacional Experimental "Rómulo Gallegos" como Profesor Ordinario en la categoría de ${cat?.current_category || 'Instructor'} a ${ded?.name || 'Tiempo Completo'} adscrito al ${teacher?.area_nombre || 'Área'} (${teacher?.sede_nombre || 'Sede Central'}).`;
    } else if (type === 'Informe de Escalafón') {
      defaultText = `Informe técnico de escalafón universitario para ${teacherName} (C.I. ${formData.teacher_cdi}), certificando su condición académica como Profesor ${cat?.current_category || 'Instructor'}, habiendo acreditado los recaudos reglamentarios y publicaciones requeridas.`;
    } else if (type === 'Informe de Dedicación') {
      defaultText = `Certificación de régimen laboral y carga horaria para ${teacherName} (C.I. ${formData.teacher_cdi}), con jornada de ${ded?.name || 'Tiempo Completo'} (${ded?.hours || 30} horas semanales) en el ${teacher?.area_nombre}.`;
    } else {
      defaultText = `Memorando administrativo interno para fines de coordinación institucional referente a las actividades docentes y académicas del profesor ${teacherName}.`;
    }

    setFormData(prev => ({
      ...prev,
      typeReport: type,
      report: defaultText,
    }));
  };

  const handleOpenCreateModal = () => {
    const firstTeacher = teachers[0];
    const cat = firstTeacher ? categories.find(c => c.teacher_cdi === firstTeacher.cdi) : null;
    const ded = firstTeacher ? dedications.find(d => d.teacher_cdi === firstTeacher.cdi) : null;
    const teacherName = firstTeacher ? `${firstTeacher.name} ${firstTeacher.surName}` : '';

    setFormData({
      memoNumber: `MEMO-UNERG-VA-${new Date().getFullYear()}-${Math.floor(100 + Math.random() * 900)}`,
      teacher_cdi: firstTeacher?.cdi || '',
      typeReport: 'Constancia de Trabajo',
      email: firstTeacher?.email || '',
      sede_nombre: firstTeacher?.sede_nombre || '',
      area_nombre: firstTeacher?.area_nombre || '',
      report: `Por medio de la presente se hace constar que el (la) ciudadano(a) ${teacherName}, titular de la C.I. ${firstTeacher?.cdi}, presta sus servicios docentes como Profesor Ordinario en la categoría de ${cat?.current_category || 'Instructor'} a ${ded?.name || 'Tiempo Completo'} en el ${firstTeacher?.area_nombre}.`,
      info: 'Emitido a solicitud de la parte interesada.',
    });
    setIsModalOpen(true);
  };

  const handleFormSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    addReport(formData);
    setIsModalOpen(false);
  };

  const filteredReports = reports.filter(r => {
    const teacher = teachers.find(t => t.cdi === r.teacher_cdi);
    const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : '';

    const matchesSearch =
      r.memoNumber.toLowerCase().includes(searchTerm.toLowerCase()) ||
      r.teacher_cdi.toLowerCase().includes(searchTerm.toLowerCase()) ||
      teacherName.toLowerCase().includes(searchTerm.toLowerCase()) ||
      r.report.toLowerCase().includes(searchTerm.toLowerCase());

    const matchesType = selectedTypeFilter ? r.typeReport === selectedTypeFilter : true;

    return matchesSearch && matchesType;
  });

  const handleViewOfficialDocument = (r: ReportItem) => {
    const teacher = teachers.find(t => t.cdi === r.teacher_cdi);
    const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : 'Docente';

    setOfficialDocProps({
      title: r.typeReport.toUpperCase(),
      memoNumber: r.memoNumber,
      recipient: `${teacherName} (C.I. ${r.teacher_cdi})`,
      subject: `Emisión de ${r.typeReport}`,
      bodyContent: (
        <div className="space-y-4">
          <p className="indent-8">{r.report}</p>
          {r.info && (
            <div className="mt-4 p-3 bg-slate-50 border border-slate-200 rounded text-xs">
              <strong>Nota / Observaciones:</strong> {r.info}
            </div>
          )}
          <p className="text-xs text-slate-600 mt-4">
            Constancia que se expide a solicitud de la parte interesada, en la ciudad de San Juan de los Morros, a los fines que estime pertinentes.
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
          <h1 className="text-xl font-bold text-slate-900 font-serif">Reportes y Memorandos Oficiales</h1>
          <p className="text-xs text-slate-500">Emisión de constancias de trabajo, informes de escalafón y memorandos institucionales</p>
        </div>

        <button
          onClick={handleOpenCreateModal}
          className="bg-[#003366] hover:bg-[#002244] text-white text-xs font-bold px-3.5 py-2 rounded-lg transition-colors flex items-center space-x-1.5 shadow-sm"
        >
          <Plus className="w-4 h-4 text-amber-400" />
          <span>Nuevo Memorando / Reporte</span>
        </button>
      </div>

      {/* Search and Filters */}
      <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="flex flex-1 gap-3 w-full sm:w-auto">
          <div className="relative flex-1">
            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
            <input
              type="text"
              placeholder="Buscar por Nº Memo, CDI, docente o texto..."
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
            <option value="Constancia de Trabajo">Constancia de Trabajo</option>
            <option value="Informe de Dedicación">Informe de Dedicación</option>
            <option value="Informe de Escalafón">Informe de Escalafón</option>
            <option value="Memorando Administrativo">Memorando Administrativo</option>
          </select>
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs text-slate-700">
            <thead className="bg-[#003366] text-white uppercase text-[11px] font-semibold tracking-wider">
              <tr>
                <th className="py-3 px-4">Nº Memorando</th>
                <th className="py-3 px-4">Tipo de Documento</th>
                <th className="py-3 px-4">Docente / C.I.</th>
                <th className="py-3 px-4">Sede y Área</th>
                <th className="py-3 px-4">Fecha Emisión</th>
                <th className="py-3 px-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 font-sans">
              {filteredReports.length === 0 ? (
                <tr>
                  <td colSpan={6} className="text-center py-8 text-slate-500">
                    No se encontraron reportes o memorandos emitidos.
                  </td>
                </tr>
              ) : (
                filteredReports.map(rep => {
                  const teacher = teachers.find(t => t.cdi === rep.teacher_cdi);
                  const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : 'Docente';

                  return (
                    <tr key={rep.id} className="hover:bg-slate-50 transition-colors">
                      <td className="py-3 px-4">
                        <span className="font-mono font-bold text-[#003366]">{rep.memoNumber}</span>
                      </td>

                      <td className="py-3 px-4">
                        <span className="font-semibold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">
                          {rep.typeReport}
                        </span>
                      </td>

                      <td className="py-3 px-4">
                        <div className="font-bold text-slate-900">{teacherName}</div>
                        <div className="text-[11px] text-slate-500 font-mono">C.I. {rep.teacher_cdi}</div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="text-slate-800">{rep.area_nombre}</div>
                        <div className="text-[11px] text-slate-500">{rep.sede_nombre}</div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="text-slate-600 font-mono text-[11px]">{rep.created_at}</div>
                      </td>

                      <td className="py-3 px-4 text-center">
                        <div className="flex items-center justify-center space-x-1.5">
                          <button
                            onClick={() => handleViewOfficialDocument(rep)}
                            className="p-1.5 text-blue-700 hover:bg-blue-50 rounded-lg transition-colors flex items-center space-x-1 font-semibold"
                            title="Ver Documento Membretado / Imprimir"
                          >
                            <Printer className="w-4 h-4" />
                            <span className="hidden md:inline text-[11px]">Ver/PDF</span>
                          </button>
                          <button
                            onClick={() => {
                              if (confirm(`¿Desea eliminar el reporte ${rep.memoNumber}?`)) {
                                deleteReport(rep.id);
                              }
                            }}
                            className="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Eliminar Reporte"
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

      {/* Create Report Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300">
            <div className="bg-[#003366] text-white px-6 py-4 flex items-center justify-between border-b border-amber-500">
              <h3 className="font-bold text-base">Emitir Nuevo Documento / Reporte Oficial</h3>
              <button onClick={() => setIsModalOpen(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleFormSubmit} className="p-6 space-y-4 text-xs text-slate-700">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Nº de Control / Memorando *</label>
                  <input
                    type="text"
                    required
                    value={formData.memoNumber}
                    onChange={(e) => setFormData({ ...formData, memoNumber: e.target.value })}
                    className="w-full p-2 font-mono border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none text-[#003366] font-bold"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Tipo de Documento *</label>
                  <select
                    value={formData.typeReport}
                    onChange={(e) => handleTypeReportChange(e.target.value as ReportType)}
                    className="w-full p-2 border border-slate-300 rounded font-bold text-slate-800 focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    <option value="Constancia de Trabajo">Constancia de Trabajo</option>
                    <option value="Informe de Escalafón">Informe de Escalafón</option>
                    <option value="Informe de Dedicación">Informe de Dedicación</option>
                    <option value="Memorando Administrativo">Memorando Administrativo</option>
                  </select>
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Docente Asunto *</label>
                  <select
                    value={formData.teacher_cdi}
                    onChange={(e) => handleTeacherSelection(e.target.value)}
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
                  <label className="block font-semibold text-slate-800 mb-1">Sede Universitaria</label>
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
                  <label className="block font-semibold text-slate-800 mb-1">Cuerpo del Documento / Contenido *</label>
                  <textarea
                    rows={4}
                    required
                    value={formData.report}
                    onChange={(e) => setFormData({ ...formData, report: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none leading-relaxed"
                    placeholder="Texto oficial del memorando o constancia..."
                  />
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Información Adicional / Destino</label>
                  <input
                    type="text"
                    value={formData.info}
                    onChange={(e) => setFormData({ ...formData, info: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Solicitado para trámites bancarios y de jubilación"
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
                  Generar y Guardar Documento
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
