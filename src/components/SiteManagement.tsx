import React, { useState } from 'react';
import { useData } from '../context/DataContext';
import { useAuth } from '../context/AuthContext';
import { MapPin, Plus, Search, Building2, BookOpen, Trash2, X } from 'lucide-react';
import { Site } from '../types';

export const SiteManagement: React.FC = () => {
  const { sites, sedes, areas, programas, teachers, addSite, deleteSite } = useData();
  const { isSuperAdmin, isAreaManager } = useAuth();

  const [search, setSearch] = useState('');
  const [selectedSede, setSelectedSede] = useState('');
  const [showAddModal, setShowAddModal] = useState(false);

  // Form
  const [formTeacherCdi, setFormTeacherCdi] = useState(teachers[0]?.cdi || '');
  const [formSede, setFormSede] = useState(sedes[0]?.nombre || '');
  const [formArea, setFormArea] = useState(areas[0]?.nombre || '');
  const [formPrograma, setFormPrograma] = useState(programas[0]?.nombre || '');
  const [formInfo, setFormInfo] = useState('');
  const [formUc, setFormUc] = useState(4);
  const [formWeekHours, setFormWeekHours] = useState(6);
  const [formSections, setFormSections] = useState(2);

  const filtered = sites.filter(s => {
    const teacher = teachers.find(t => t.cdi === s.teacher_cdi);
    const name = teacher ? `${teacher.name} ${teacher.surName}`.toLowerCase() : '';
    const matchesSearch = s.info.toLowerCase().includes(search.toLowerCase()) || name.includes(search.toLowerCase()) || s.teacher_cdi.includes(search);
    const matchesSede = !selectedSede || s.sede_nombre === selectedSede;
    return matchesSearch && matchesSede;
  });

  const handleAddSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formInfo) return;

    addSite({
      teacher_cdi: formTeacherCdi,
      sede_nombre: formSede,
      area_nombre: formArea,
      programa_nombre: formPrograma,
      info: formInfo,
      uc: formUc,
      weekHours: formWeekHours,
      sections: formSections,
      is_active: true,
    });

    setShowAddModal(false);
    setFormInfo('');
  };

  return (
    <div className="space-y-4">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <MapPin className="w-5 h-5 text-emerald-600" />
            Distribución Territorial de Sedes y Cátedras
          </h2>
          <p className="text-xs text-slate-500">
            Asignación de unidades curriculares, sedes y secciones del cuerpo docente UNERG
          </p>
        </div>

        {(isSuperAdmin || isAreaManager) && (
          <button
            onClick={() => setShowAddModal(true)}
            className="px-4 py-2 text-xs font-bold text-white bg-unerg-blue hover:bg-unerg-blue-light rounded-lg shadow-sm flex items-center gap-1.5 transition-all"
          >
            <Plus className="w-4 h-4" /> Asignar Cátedra
          </button>
        )}
      </div>

      {/* Filter and Search */}
      <div className="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs flex flex-wrap gap-2.5 items-center">
        <div className="relative flex-1 min-w-[240px]">
          <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Buscar por asignatura, docente o cédula..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-unerg-blue"
          />
        </div>

        <select
          value={selectedSede}
          onChange={(e) => setSelectedSede(e.target.value)}
          className="text-xs p-1.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-700 font-medium"
        >
          <option value="">Todas las Sedes</option>
          {sedes.map(s => <option key={s.id} value={s.nombre}>{s.nombre}</option>)}
        </select>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-slate-100/70 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px] tracking-wider">
              <tr>
                <th className="py-3 px-4">Asignatura / Cátedra</th>
                <th className="py-3 px-4">Docente Asignado</th>
                <th className="py-3 px-4">Sede y Área</th>
                <th className="py-3 px-4">Programa</th>
                <th className="py-3 px-4">Carga Académica</th>
                <th className="py-3 px-4 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {filtered.map(site => {
                const teacher = teachers.find(t => t.cdi === site.teacher_cdi);

                return (
                  <tr key={site.id} className="hover:bg-slate-50/80 transition-colors">
                    <td className="py-3 px-4">
                      <div className="font-bold text-slate-900">{site.info}</div>
                      <span className="text-[10px] font-bold px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800">
                        {site.is_active ? 'Activa' : 'Inactiva'}
                      </span>
                    </td>

                    <td className="py-3 px-4">
                      <div className="font-semibold text-slate-800">
                        {teacher ? `${teacher.name} ${teacher.surName}` : 'Docente'}
                      </div>
                      <div className="text-[11px] text-slate-500 font-mono">V-{site.teacher_cdi}</div>
                    </td>

                    <td className="py-3 px-4">
                      <div className="text-slate-800 font-medium">{site.sede_nombre}</div>
                      <div className="text-[11px] text-slate-500">{site.area_nombre}</div>
                    </td>

                    <td className="py-3 px-4 text-slate-700">
                      {site.programa_nombre}
                    </td>

                    <td className="py-3 px-4">
                      <div className="font-medium text-slate-800">{site.uc} UC • {site.weekHours} hrs/sem</div>
                      <div className="text-[11px] text-slate-500">{site.sections} Secciones</div>
                    </td>

                    <td className="py-3 px-4 text-right">
                      {(isSuperAdmin || isAreaManager) && (
                        <button
                          onClick={() => {
                            if (confirm(`¿Eliminar la asignación de ${site.info}?`)) {
                              deleteSite(site.id);
                            }
                          }}
                          className="p-1.5 text-red-600 hover:bg-red-50 rounded transition-colors"
                        >
                          <Trash2 className="w-4 h-4" />
                        </button>
                      )}
                    </td>
                  </tr>
                );
              })}
            </tbody>
          </table>
        </div>
      </div>

      {/* Add Modal */}
      {showAddModal && (
        <div className="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg overflow-hidden animate-in fade-in zoom-in-95">
            <div className="bg-unerg-blue text-white p-4 flex items-center justify-between">
              <h3 className="font-bold text-sm flex items-center gap-2">
                <BookOpen className="w-4 h-4 text-amber-300" />
                Asignar Nueva Cátedra a Docente
              </h3>
              <button onClick={() => setShowAddModal(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleAddSubmit} className="p-6 space-y-3 text-xs">
              <div>
                <label className="block font-semibold text-slate-700 mb-1">Docente Responsable:</label>
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

              <div>
                <label className="block font-semibold text-slate-700 mb-1">Nombre de la Asignatura / Cátedra:</label>
                <input
                  type="text"
                  required
                  placeholder="Ej. Redes de Comunicación II"
                  value={formInfo}
                  onChange={(e) => setFormInfo(e.target.value)}
                  className="w-full p-2 border border-slate-300 rounded-lg"
                />
              </div>

              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Sede:</label>
                  <select
                    value={formSede}
                    onChange={(e) => setFormSede(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg"
                  >
                    {sedes.map(s => <option key={s.id} value={s.nombre}>{s.nombre}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Programa:</label>
                  <select
                    value={formPrograma}
                    onChange={(e) => setFormPrograma(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg"
                  >
                    {programas.map(p => <option key={p.id} value={p.nombre}>{p.nombre}</option>)}
                  </select>
                </div>
              </div>

              <div className="grid grid-cols-3 gap-3">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Unidades Crédito:</label>
                  <input
                    type="number"
                    min="1"
                    value={formUc}
                    onChange={(e) => setFormUc(Number(e.target.value))}
                    className="w-full p-2 border border-slate-300 rounded-lg font-mono"
                  />
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Horas Semanales:</label>
                  <input
                    type="number"
                    min="1"
                    value={formWeekHours}
                    onChange={(e) => setFormWeekHours(Number(e.target.value))}
                    className="w-full p-2 border border-slate-300 rounded-lg font-mono"
                  />
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Secciones:</label>
                  <input
                    type="number"
                    min="1"
                    value={formSections}
                    onChange={(e) => setFormSections(Number(e.target.value))}
                    className="w-full p-2 border border-slate-300 rounded-lg font-mono"
                  />
                </div>
              </div>

              <div className="pt-4 border-t border-slate-200 flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setShowAddModal(false)}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-unerg-blue hover:bg-unerg-blue-light text-white font-bold rounded-lg shadow-sm"
                >
                  Asignar Cátedra
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
