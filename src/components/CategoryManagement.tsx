import React, { useState } from 'react';
import { useData } from '../context/DataContext';
import { useAuth } from '../context/AuthContext';
import { Award, Search, CheckCircle2, AlertCircle, ArrowUpRight } from 'lucide-react';
import { CategoryLevel } from '../types';

export const CategoryManagement: React.FC = () => {
  const { categories, teachers, promoteTeacher } = useData();
  const { isSuperAdmin, isAreaManager } = useAuth();
  const [search, setSearch] = useState('');
  const [filterCat, setFilterCat] = useState('');

  const categoryOrder: CategoryLevel[] = ['Instructor', 'Asistente', 'Agregado', 'Asociado', 'Titular'];

  const filtered = categories.filter(c => {
    const teacher = teachers.find(t => t.cdi === c.teacher_cdi);
    const teacherName = teacher ? `${teacher.name} ${teacher.surName}`.toLowerCase() : '';
    const matchesSearch = teacherName.includes(search.toLowerCase()) || c.teacher_cdi.includes(search) || (c.lastTitle && c.lastTitle.toLowerCase().includes(search.toLowerCase()));
    const matchesCat = !filterCat || c.current_category === filterCat;
    return matchesSearch && matchesCat;
  });

  return (
    <div className="space-y-4">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <Award className="w-5 h-5 text-amber-500" />
            Control de Escalafón Docente Universitario
          </h2>
          <p className="text-xs text-slate-500">
            Reglamento de Ascensos UNERG: Instructor, Asistente, Agregado, Asociado y Titular ({filtered.length} registrados)
          </p>
        </div>
      </div>

      {/* Search and Filters */}
      <div className="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs flex flex-wrap gap-2.5 items-center">
        <div className="relative flex-1 min-w-[240px]">
          <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Buscar docente o título por posgrado..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-unerg-blue"
          />
        </div>

        <select
          value={filterCat}
          onChange={(e) => setFilterCat(e.target.value)}
          className="text-xs p-1.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-700 font-medium"
        >
          <option value="">Todas las Categorías</option>
          {categoryOrder.map(c => <option key={c} value={c}>{c}</option>)}
        </select>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-slate-100/70 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px] tracking-wider">
              <tr>
                <th className="py-3 px-4">Docente</th>
                <th className="py-3 px-4">Categoría Vigente</th>
                <th className="py-3 px-4">Pregrado</th>
                <th className="py-3 px-4">Último Posgrado / Doctorado</th>
                <th className="py-3 px-4">Información / PEII</th>
                <th className="py-3 px-4 text-right">Línea de Ascenso</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {filtered.length === 0 ? (
                <tr>
                  <td colSpan={6} className="py-10 text-center text-slate-500 text-xs">
                    No hay registros de escalafón docente disponibles.
                  </td>
                </tr>
              ) : (
                filtered.map(cat => {
                const teacher = teachers.find(t => t.cdi === cat.teacher_cdi);
                const currentIdx = categoryOrder.indexOf(cat.current_category);
                const canPromote = currentIdx < categoryOrder.length - 1;

                return (
                  <tr key={cat.id} className="hover:bg-slate-50/80 transition-colors">
                    <td className="py-3 px-4">
                      <div className="font-bold text-slate-900">
                        {teacher ? `${teacher.name} ${teacher.surName}` : 'Docente'}
                      </div>
                      <div className="text-[11px] font-mono text-slate-500">V-{cat.teacher_cdi}</div>
                    </td>

                    <td className="py-3 px-4">
                      <span className={`inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold ${
                        cat.current_category === 'Titular'
                          ? 'bg-amber-100 text-amber-900 border border-amber-300'
                          : cat.current_category === 'Asociado'
                          ? 'bg-blue-100 text-blue-900 border border-blue-200'
                          : cat.current_category === 'Agregado'
                          ? 'bg-indigo-100 text-indigo-900 border border-indigo-200'
                          : cat.current_category === 'Asistente'
                          ? 'bg-emerald-100 text-emerald-900 border border-emerald-200'
                          : 'bg-slate-100 text-slate-900 border border-slate-200'
                      }`}>
                        {cat.current_category}
                      </span>
                    </td>

                    <td className="py-3 px-4 text-slate-700 font-medium">
                      {cat.preTitle}
                    </td>

                    <td className="py-3 px-4 text-slate-800 font-semibold">
                      {cat.lastTitle || <span className="text-slate-400 font-normal italic">Sin registro</span>}
                    </td>

                    <td className="py-3 px-4 text-slate-600 text-[11px]">
                      {cat.info || 'Cumplimiento regular de requisitos'}
                    </td>

                    <td className="py-3 px-4 text-right">
                      {(isSuperAdmin || isAreaManager) && canPromote ? (
                        <button
                          onClick={() => {
                            const next = categoryOrder[currentIdx + 1];
                            const date = new Date().toISOString().split('T')[0];
                            if (confirm(`¿Promover al docente a ${next}?`)) {
                              promoteTeacher(cat.teacher_cdi, next, date);
                            }
                          }}
                          className="px-2.5 py-1 bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold rounded text-[11px] transition-colors border border-amber-300 inline-flex items-center gap-1"
                        >
                          Promover a {categoryOrder[currentIdx + 1]}
                        </button>
                      ) : (
                        <span className="text-emerald-700 font-semibold text-[11px] flex items-center justify-end gap-1">
                          <CheckCircle2 className="w-3.5 h-3.5" /> Máximo Escalafón
                        </span>
                      )}
                    </td>
                  </tr>
                );
              })
            )}
            </tbody>
          </table>
        </div>
      </div>
    </div>
  );
};
