import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { Category, CategoryLevel } from '../types';
import {
  Award,
  Search,
  Plus,
  Edit2,
  FileText,
  CheckCircle2,
  AlertCircle,
  HelpCircle,
  X,
  Printer
} from 'lucide-react';
import { OfficialDocumentModal } from './OfficialDocumentModal';

export const CategoriesView: React.FC = () => {
  const { categories, teachers, saveCategory } = useApp();

  const [searchTerm, setSearchTerm] = useState('');
  const [selectedCategoryFilter, setSelectedCategoryFilter] = useState<string>('');
  const [editingCategory, setEditingCategory] = useState<Category | null>(null);
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
    preTitle: string;
    lastTitle: string;
    current_category: CategoryLevel;
    instructor: string;
    asistente: string;
    agregado: string;
    asociado: string;
    titular: string;
    disable_assistant_rule: boolean;
    info: string;
  }>({
    teacher_cdi: '',
    preTitle: 'Licenciado(a)',
    lastTitle: '',
    current_category: 'Instructor',
    instructor: '',
    asistente: '',
    agregado: '',
    asociado: '',
    titular: '',
    disable_assistant_rule: false,
    info: '',
  });

  const handleOpenEditModal = (cat: Category) => {
    setEditingCategory(cat);
    setFormData({
      id: cat.id,
      teacher_cdi: cat.teacher_cdi,
      preTitle: cat.preTitle,
      lastTitle: cat.lastTitle || '',
      current_category: cat.current_category,
      instructor: cat.instructor || '',
      asistente: cat.asistente || '',
      agregado: cat.agregado || '',
      asociado: cat.asociado || '',
      titular: cat.titular || '',
      disable_assistant_rule: !!cat.disable_assistant_rule,
      info: cat.info || '',
    });
    setIsModalOpen(true);
  };

  const handleFormSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    saveCategory({
      id: formData.id,
      teacher_cdi: formData.teacher_cdi,
      preTitle: formData.preTitle,
      lastTitle: formData.lastTitle,
      current_category: formData.current_category,
      instructor: formData.instructor || undefined,
      asistente: formData.asistente || undefined,
      agregado: formData.agregado || undefined,
      asociado: formData.asociado || undefined,
      titular: formData.titular || undefined,
      disable_assistant_rule: formData.disable_assistant_rule,
      info: formData.info,
    });
    setIsModalOpen(false);
  };

  // Filtered categories
  const filteredCategories = categories.filter(c => {
    const teacher = teachers.find(t => t.cdi === c.teacher_cdi);
    const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : '';
    
    const matchesSearch =
      c.teacher_cdi.toLowerCase().includes(searchTerm.toLowerCase()) ||
      teacherName.toLowerCase().includes(searchTerm.toLowerCase()) ||
      (c.lastTitle && c.lastTitle.toLowerCase().includes(searchTerm.toLowerCase())) ||
      (c.preTitle && c.preTitle.toLowerCase().includes(searchTerm.toLowerCase()));

    const matchesCategory = selectedCategoryFilter ? c.current_category === selectedCategoryFilter : true;

    return matchesSearch && matchesCategory;
  });

  // Certificate Generator
  const handleGenerateCertificate = (cat: Category) => {
    const teacher = teachers.find(t => t.cdi === cat.teacher_cdi);
    if (!teacher) return;

    setOfficialDocProps({
      title: 'CERTIFICACIÓN DE ESCALAFÓN Y ASCENSO UNIVERSITARIO',
      memoNumber: `CERT-ESC-UNERG-${cat.teacher_cdi}-${new Date().getFullYear()}`,
      recipient: `${teacher.name} ${teacher.surName}`,
      subject: `Certificación oficial de categoría en el escalafón docente - ${cat.current_category}`,
      bodyContent: (
        <div className="space-y-4">
          <p>
            La <strong>Comisión Calificadora del Personal Docente y de Investigación del Vicerrectorado Académico de la Universidad Nacional Experimental "Rómulo Gallegos"</strong>,
            en uso de sus atribuciones reglamentarias y estatutarias:
          </p>

          <div className="p-4 bg-slate-50 border border-slate-200 rounded text-xs space-y-2">
            <h4 className="font-bold text-slate-900 text-sm">CERTIFICA:</h4>
            <p>
              Que el (la) ciudadano(a) <strong>{teacher.name} {teacher.surName}</strong>, titular de la Cédula de Identidad Nº <strong>{teacher.cdi}</strong>,
              docente adscrito(a) al <strong>{teacher.area_nombre}</strong> en la <strong>{teacher.sede_nombre}</strong>, se encuentra legalmente clasificado(a)
              en el Escalafón del Personal Docente Ordinario de esta Ilustre Universidad en el rango de:
            </p>
            <div className="text-center py-2">
              <span className="text-base font-bold text-[#003366] bg-amber-50 px-4 py-1.5 rounded border border-amber-300 inline-block uppercase">
                Profesor(a) {cat.current_category}
              </span>
            </div>
          </div>

          <h4 className="font-bold text-slate-900 border-b border-slate-200 pb-1">Trayectoria de Ascensos Registrada</h4>
          <table className="w-full text-xs border border-slate-300">
            <thead className="bg-slate-100 font-bold">
              <tr>
                <th className="p-2 border">Categoría</th>
                <th className="p-2 border">Fecha Efectiva</th>
                <th className="p-2 border">Estado</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td className="p-2 border font-medium">Instructor</td>
                <td className="p-2 border">{cat.instructor || 'Fecha inicial'}</td>
                <td className="p-2 border text-emerald-700 font-bold">Acreditado</td>
              </tr>
              {cat.asistente && (
                <tr>
                  <td className="p-2 border font-medium">Asistente</td>
                  <td className="p-2 border">{cat.asistente}</td>
                  <td className="p-2 border text-emerald-700 font-bold">Acreditado</td>
                </tr>
              )}
              {cat.agregado && (
                <tr>
                  <td className="p-2 border font-medium">Agregado</td>
                  <td className="p-2 border">{cat.agregado}</td>
                  <td className="p-2 border text-emerald-700 font-bold">Acreditado</td>
                </tr>
              )}
              {cat.asociado && (
                <tr>
                  <td className="p-2 border font-medium">Asociado</td>
                  <td className="p-2 border">{cat.asociado}</td>
                  <td className="p-2 border text-emerald-700 font-bold">Acreditado</td>
                </tr>
              )}
              {cat.titular && (
                <tr>
                  <td className="p-2 border font-medium">Titular</td>
                  <td className="p-2 border">{cat.titular}</td>
                  <td className="p-2 border text-emerald-700 font-bold">Acreditado</td>
                </tr>
              )}
            </tbody>
          </table>

          <div className="text-xs text-slate-700">
            <p><strong>Título de Pregrado:</strong> {cat.preTitle}</p>
            <p><strong>Título de Postgrado Acreditado:</strong> {cat.lastTitle || 'En proceso de validación'}</p>
          </div>

          <p className="text-xs text-slate-600">
            Constancia que se expide a solicitud de la parte interesada en la ciudad de San Juan de los Morros.
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
          <h1 className="text-xl font-bold text-slate-900 font-serif">Escalafón y Cronología de Ascensos</h1>
          <p className="text-xs text-slate-500">Registro de categorías académicas (Instructor, Asistente, Agregado, Asociado, Titular)</p>
        </div>
      </div>

      {/* Search and Filters */}
      <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="flex flex-1 gap-3 w-full sm:w-auto">
          <div className="relative flex-1">
            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
            <input
              type="text"
              placeholder="Buscar por CDI, nombre del docente o título..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none"
            />
          </div>

          <select
            value={selectedCategoryFilter}
            onChange={(e) => setSelectedCategoryFilter(e.target.value)}
            className="py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none text-slate-700"
          >
            <option value="">Todas las Categorías</option>
            <option value="Titular">Titular</option>
            <option value="Asociado">Asociado</option>
            <option value="Agregado">Agregado</option>
            <option value="Asistente">Asistente</option>
            <option value="Instructor">Instructor</option>
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
                <th className="py-3 px-4">Categoría Actual</th>
                <th className="py-3 px-4">Título Pregrado</th>
                <th className="py-3 px-4">Título Posgrado</th>
                <th className="py-3 px-4">Última Fecha de Ascenso</th>
                <th className="py-3 px-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 font-sans">
              {filteredCategories.length === 0 ? (
                <tr>
                  <td colSpan={6} className="text-center py-8 text-slate-500">
                    No se encontraron registros de escalafón.
                  </td>
                </tr>
              ) : (
                filteredCategories.map(cat => {
                  const teacher = teachers.find(t => t.cdi === cat.teacher_cdi);
                  const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : 'Docente';
                  const lastPromotionDate = cat.titular || cat.asociado || cat.agregado || cat.asistente || cat.instructor || 'N/A';

                  return (
                    <tr key={cat.id} className="hover:bg-slate-50 transition-colors">
                      <td className="py-3 px-4">
                        <div className="font-bold text-slate-900">{teacherName}</div>
                        <div className="text-[11px] text-slate-500 font-mono">C.I. {cat.teacher_cdi}</div>
                      </td>

                      <td className="py-3 px-4">
                        <span className={`inline-block px-2.5 py-1 rounded text-xs font-bold ${
                          cat.current_category === 'Titular' ? 'bg-amber-100 text-amber-900 border border-amber-300' :
                          cat.current_category === 'Asociado' ? 'bg-blue-100 text-blue-900' :
                          cat.current_category === 'Agregado' ? 'bg-indigo-100 text-indigo-900' :
                          cat.current_category === 'Asistente' ? 'bg-emerald-100 text-emerald-900' :
                          'bg-slate-100 text-slate-800'
                        }`}>
                          {cat.current_category}
                        </span>
                      </td>

                      <td className="py-3 px-4">
                        <div className="text-slate-800">{cat.preTitle}</div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="text-slate-800 font-medium">{cat.lastTitle || <span className="text-slate-400 italic">No registrado</span>}</div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="font-mono text-slate-700">{lastPromotionDate}</div>
                      </td>

                      <td className="py-3 px-4 text-center">
                        <div className="flex items-center justify-center space-x-1.5">
                          <button
                            onClick={() => handleGenerateCertificate(cat)}
                            className="p-1.5 text-amber-700 hover:bg-amber-50 rounded-lg transition-colors flex items-center space-x-1"
                            title="Emitir Certificación de Escalafón"
                          >
                            <FileText className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => handleOpenEditModal(cat)}
                            className="p-1.5 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                            title="Editar Datos de Escalafón"
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

      {/* Edit Category Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300">
            <div className="bg-[#003366] text-white px-6 py-4 flex items-center justify-between border-b border-amber-500">
              <h3 className="font-bold text-base">Actualizar Registro de Escalafón</h3>
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
                  <label className="block font-semibold text-slate-800 mb-1">Categoría Actual *</label>
                  <select
                    value={formData.current_category}
                    onChange={(e) => setFormData({ ...formData, current_category: e.target.value as CategoryLevel })}
                    className="w-full p-2 border border-slate-300 rounded font-bold text-[#003366] focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  >
                    <option value="Instructor">Instructor</option>
                    <option value="Asistente">Asistente</option>
                    <option value="Agregado">Agregado</option>
                    <option value="Asociado">Asociado</option>
                    <option value="Titular">Titular</option>
                  </select>
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Título de Pregrado *</label>
                  <input
                    type="text"
                    required
                    value={formData.preTitle}
                    onChange={(e) => setFormData({ ...formData, preTitle: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Licenciado en Computación"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Título de Posgrado (Magíster / Doctor)</label>
                  <input
                    type="text"
                    value={formData.lastTitle}
                    onChange={(e) => setFormData({ ...formData, lastTitle: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Magíster en Ciencias"
                  />
                </div>

                {/* Dates per category level */}
                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Fecha Instructor</label>
                  <input
                    type="date"
                    value={formData.instructor}
                    onChange={(e) => setFormData({ ...formData, instructor: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Fecha Asistente</label>
                  <input
                    type="date"
                    value={formData.asistente}
                    onChange={(e) => setFormData({ ...formData, asistente: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Fecha Agregado</label>
                  <input
                    type="date"
                    value={formData.agregado}
                    onChange={(e) => setFormData({ ...formData, agregado: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Fecha Asociado</label>
                  <input
                    type="date"
                    value={formData.asociado}
                    onChange={(e) => setFormData({ ...formData, asociado: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Fecha Titular</label>
                  <input
                    type="date"
                    value={formData.titular}
                    onChange={(e) => setFormData({ ...formData, titular: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div className="flex items-center space-x-2 pt-6">
                  <input
                    type="checkbox"
                    id="disableRule"
                    checked={formData.disable_assistant_rule}
                    onChange={(e) => setFormData({ ...formData, disable_assistant_rule: e.target.checked })}
                    className="h-4 w-4 text-[#003366] rounded focus:ring-[#003366]"
                  />
                  <label htmlFor="disableRule" className="text-slate-800 font-medium cursor-pointer">
                    Excepción de Regla de Asistente
                  </label>
                </div>

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Observaciones / Información Adicional</label>
                  <textarea
                    rows={2}
                    value={formData.info}
                    onChange={(e) => setFormData({ ...formData, info: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Acreditación aprobada por el Consejo Universitario según Resolución CU-2024-..."
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
                  Guardar Escalafón
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
