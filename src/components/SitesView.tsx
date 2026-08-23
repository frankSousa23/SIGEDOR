import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { SiteAssignment } from '../types';
import {
  Building2,
  Search,
  Plus,
  Edit2,
  Trash2,
  CheckCircle2,
  XCircle,
  X,
  BookOpen
} from 'lucide-react';

export const SitesView: React.FC = () => {
  const {
    sites,
    teachers,
    sedes,
    areas,
    programas,
    saveSiteAssignment,
    toggleSiteActive,
    deleteSiteAssignment
  } = useApp();

  const [searchTerm, setSearchTerm] = useState('');
  const [selectedSedeFilter, setSelectedSedeFilter] = useState('');
  const [editingSite, setEditingSite] = useState<SiteAssignment | null>(null);
  const [isModalOpen, setIsModalOpen] = useState(false);

  // Form
  const [formData, setFormData] = useState<{
    id?: number;
    teacher_cdi: string;
    sede_nombre: string;
    area_nombre: string;
    programa_nombre: string;
    uc: number;
    weekHours: number;
    sections: number;
    info: string;
    is_active: boolean;
  }>({
    teacher_cdi: teachers[0]?.cdi || '',
    sede_nombre: sedes[0]?.nombre || '',
    area_nombre: areas[0]?.nombre || '',
    programa_nombre: programas[0]?.nombre || '',
    uc: 3,
    weekHours: 6,
    sections: 2,
    info: '',
    is_active: true,
  });

  const handleOpenCreate = () => {
    setEditingSite(null);
    setFormData({
      teacher_cdi: teachers[0]?.cdi || '',
      sede_nombre: sedes[0]?.nombre || '',
      area_nombre: areas[0]?.nombre || '',
      programa_nombre: programas[0]?.nombre || '',
      uc: 3,
      weekHours: 6,
      sections: 2,
      info: '',
      is_active: true,
    });
    setIsModalOpen(true);
  };

  const handleOpenEdit = (site: SiteAssignment) => {
    setEditingSite(site);
    setFormData({
      id: site.id,
      teacher_cdi: site.teacher_cdi,
      sede_nombre: site.sede_nombre,
      area_nombre: site.area_nombre,
      programa_nombre: site.programa_nombre,
      uc: site.uc,
      weekHours: site.weekHours,
      sections: site.sections,
      info: site.info || '',
      is_active: site.is_active,
    });
    setIsModalOpen(true);
  };

  const handleFormSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    saveSiteAssignment({
      id: formData.id,
      teacher_cdi: formData.teacher_cdi,
      sede_nombre: formData.sede_nombre,
      area_nombre: formData.area_nombre,
      programa_nombre: formData.programa_nombre,
      uc: Number(formData.uc),
      weekHours: Number(formData.weekHours),
      sections: Number(formData.sections),
      info: formData.info,
      is_active: formData.is_active,
    });
    setIsModalOpen(false);
  };

  const filteredSites = sites.filter(s => {
    const teacher = teachers.find(t => t.cdi === s.teacher_cdi);
    const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : '';
    
    const matchesSearch =
      s.teacher_cdi.toLowerCase().includes(searchTerm.toLowerCase()) ||
      teacherName.toLowerCase().includes(searchTerm.toLowerCase()) ||
      s.area_nombre.toLowerCase().includes(searchTerm.toLowerCase()) ||
      (s.info && s.info.toLowerCase().includes(searchTerm.toLowerCase()));

    const matchesSede = selectedSedeFilter ? s.sede_nombre === selectedSedeFilter : true;

    return matchesSearch && matchesSede;
  });

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-xl font-bold text-slate-900 font-serif">Sedes y Asignación de Cátedras</h1>
          <p className="text-xs text-slate-500">Distribución territorial de docentes por Sedes, Áreas, Programas, UC y Secciones</p>
        </div>

        <button
          onClick={handleOpenCreate}
          className="bg-[#003366] hover:bg-[#002244] text-white text-xs font-bold px-3.5 py-2 rounded-lg transition-colors flex items-center space-x-1.5 shadow-sm"
        >
          <Plus className="w-4 h-4 text-amber-400" />
          <span>Nueva Asignación</span>
        </button>
      </div>

      {/* Search and Filters */}
      <div className="bg-white p-4 rounded-xl border border-slate-200 shadow-sm flex flex-col sm:flex-row gap-3 items-center justify-between">
        <div className="flex flex-1 gap-3 w-full sm:w-auto">
          <div className="relative flex-1">
            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-2.5" />
            <input
              type="text"
              placeholder="Buscar por CDI, docente, cátedra o área..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
              className="w-full pl-9 pr-3 py-2 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none"
            />
          </div>

          <select
            value={selectedSedeFilter}
            onChange={(e) => setSelectedSedeFilter(e.target.value)}
            className="py-2 px-3 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-[#003366] focus:outline-none text-slate-700"
          >
            <option value="">Todas las Sedes</option>
            {sedes.map(s => (
              <option key={s.id} value={s.nombre}>{s.nombre}</option>
            ))}
          </select>
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs text-slate-700">
            <thead className="bg-[#003366] text-white uppercase text-[11px] font-semibold tracking-wider">
              <tr>
                <th className="py-3 px-4">Docente / C.I.</th>
                <th className="py-3 px-4">Sede y Área</th>
                <th className="py-3 px-4">Programa y Cátedra</th>
                <th className="py-3 px-4 text-center">UC / Horas / Secciones</th>
                <th className="py-3 px-4 text-center">Estado</th>
                <th className="py-3 px-4 text-center">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100 font-sans">
              {filteredSites.length === 0 ? (
                <tr>
                  <td colSpan={6} className="text-center py-8 text-slate-500">
                    No se encontraron asignaciones territoriales.
                  </td>
                </tr>
              ) : (
                filteredSites.map(site => {
                  const teacher = teachers.find(t => t.cdi === site.teacher_cdi);
                  const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : 'Docente';

                  return (
                    <tr key={site.id} className="hover:bg-slate-50 transition-colors">
                      <td className="py-3 px-4">
                        <div className="font-bold text-slate-900">{teacherName}</div>
                        <div className="text-[11px] text-slate-500 font-mono">C.I. {site.teacher_cdi}</div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="font-medium text-slate-800">{site.sede_nombre}</div>
                        <div className="text-[11px] text-slate-500">{site.area_nombre}</div>
                      </td>

                      <td className="py-3 px-4">
                        <div className="font-semibold text-[#003366]">{site.info || 'Cátedra Regular'}</div>
                        <div className="text-[11px] text-slate-500">{site.programa_nombre}</div>
                      </td>

                      <td className="py-3 px-4 text-center">
                        <span className="font-bold text-slate-900">{site.uc} UC</span> • <span className="text-slate-600">{site.weekHours}h/sem</span> • <span className="text-amber-800 font-bold">{site.sections} Sec.</span>
                      </td>

                      <td className="py-3 px-4 text-center">
                        <button
                          onClick={() => toggleSiteActive(site.id)}
                          className={`px-2 py-0.5 rounded text-[10px] font-bold inline-flex items-center space-x-1 cursor-pointer transition-colors ${
                            site.is_active ? 'bg-emerald-100 text-emerald-800 hover:bg-emerald-200' : 'bg-slate-200 text-slate-600 hover:bg-slate-300'
                          }`}
                          title="Clic para cambiar estado"
                        >
                          {site.is_active ? <CheckCircle2 className="w-3 h-3 text-emerald-600" /> : <XCircle className="w-3 h-3 text-slate-400" />}
                          <span>{site.is_active ? 'Activa' : 'Inactiva'}</span>
                        </button>
                      </td>

                      <td className="py-3 px-4 text-center">
                        <div className="flex items-center justify-center space-x-1.5">
                          <button
                            onClick={() => handleOpenEdit(site)}
                            className="p-1.5 text-slate-600 hover:bg-slate-100 rounded-lg transition-colors"
                            title="Editar Asignación"
                          >
                            <Edit2 className="w-4 h-4" />
                          </button>
                          <button
                            onClick={() => {
                              if (confirm('¿Desea eliminar esta asignación de cátedra?')) {
                                deleteSiteAssignment(site.id);
                              }
                            }}
                            className="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                            title="Eliminar Asignación"
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

      {/* Modal */}
      {isModalOpen && (
        <div className="fixed inset-0 bg-slate-950/70 backdrop-blur-sm z-50 flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden border border-slate-300">
            <div className="bg-[#003366] text-white px-6 py-4 flex items-center justify-between border-b border-amber-500">
              <h3 className="font-bold text-base">{editingSite ? 'Editar Asignación de Cátedra' : 'Nueva Asignación de Sede / Cátedra'}</h3>
              <button onClick={() => setIsModalOpen(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleFormSubmit} className="p-6 space-y-4 text-xs text-slate-700">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Docente Asignado *</label>
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
                  <label className="block font-semibold text-slate-800 mb-1">Sede Territorial *</label>
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
                  <label className="block font-semibold text-slate-800 mb-1">Área Académica *</label>
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
                  <label className="block font-semibold text-slate-800 mb-1">Programa de Formación *</label>
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

                <div className="sm:col-span-2">
                  <label className="block font-semibold text-slate-800 mb-1">Nombre de la Asignatura / Cátedra *</label>
                  <input
                    type="text"
                    required
                    value={formData.info}
                    onChange={(e) => setFormData({ ...formData, info: e.target.value })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                    placeholder="Ej. Cátedra de Algoritmos y Estructuras de Datos"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Unidades de Crédito (UC)</label>
                  <input
                    type="number"
                    value={formData.uc}
                    onChange={(e) => setFormData({ ...formData, uc: Number(e.target.value) })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Horas Semanales de Clase</label>
                  <input
                    type="number"
                    value={formData.weekHours}
                    onChange={(e) => setFormData({ ...formData, weekHours: Number(e.target.value) })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div>
                  <label className="block font-semibold text-slate-800 mb-1">Número de Secciones</label>
                  <input
                    type="number"
                    value={formData.sections}
                    onChange={(e) => setFormData({ ...formData, sections: Number(e.target.value) })}
                    className="w-full p-2 border border-slate-300 rounded focus:ring-2 focus:ring-[#003366] focus:outline-none"
                  />
                </div>

                <div className="flex items-center space-x-2 pt-6">
                  <input
                    type="checkbox"
                    id="siteActive"
                    checked={formData.is_active}
                    onChange={(e) => setFormData({ ...formData, is_active: e.target.checked })}
                    className="h-4 w-4 text-[#003366] rounded focus:ring-[#003366]"
                  />
                  <label htmlFor="siteActive" className="text-slate-800 font-medium cursor-pointer">
                    Asignación Activa este Período
                  </label>
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
                  Guardar Asignación
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
