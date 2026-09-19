import React, { useState } from 'react';
import { useData } from '../context/DataContext';
import { useAuth } from '../context/AuthContext';
import { Clock, Search, Edit3, CheckCircle2 } from 'lucide-react';
import { Dedication } from '../types';

export const DedicationManagement: React.FC = () => {
  const { dedications, teachers, updateDedication } = useData();
  const { isSuperAdmin, isAreaManager } = useAuth();
  const [search, setSearch] = useState('');
  const [editingCdi, setEditingCdi] = useState<string | null>(null);
  const [editHours, setEditHours] = useState(12);
  const [editName, setEditName] = useState<Dedication['name']>('Tiempo Convencional');

  const filtered = dedications.filter(d => {
    const teacher = teachers.find(t => t.cdi === d.teacher_cdi);
    const name = teacher ? `${teacher.name} ${teacher.surName}`.toLowerCase() : '';
    return name.includes(search.toLowerCase()) || d.teacher_cdi.includes(search);
  });

  const handleSaveEdit = (cdi: string) => {
    updateDedication(cdi, {
      name: editName,
      hours: editHours,
    });
    setEditingCdi(null);
  };

  return (
    <div className="space-y-4">
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <Clock className="w-5 h-5 text-blue-600" />
            Régimen de Dedicación y Carga Horaria
          </h2>
          <p className="text-xs text-slate-500">
            Control de dedicación contractual (TCV 12h, MT 18h, TC 30h, EX 36h) y horas de docencia
          </p>
        </div>
      </div>

      <div className="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs">
        <div className="relative max-w-md">
          <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Buscar por docente o cédula..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-unerg-blue"
          />
        </div>
      </div>

      {/* Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-slate-100/70 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px] tracking-wider">
              <tr>
                <th className="py-3 px-4">Docente</th>
                <th className="py-3 px-4">Modalidad</th>
                <th className="py-3 px-4">Horas Semanales</th>
                <th className="py-3 px-4">Cargo Directivo</th>
                <th className="py-3 px-4">Atención a Estudiantes</th>
                <th className="py-3 px-4 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {filtered.length === 0 ? (
                <tr>
                  <td colSpan={6} className="py-10 text-center text-slate-500 text-xs">
                    No hay registros de dedicación académica disponibles.
                  </td>
                </tr>
              ) : (
                filtered.map(ded => {
                const teacher = teachers.find(t => t.cdi === ded.teacher_cdi);
                const isEditing = editingCdi === ded.teacher_cdi;

                return (
                  <tr key={ded.id} className="hover:bg-slate-50/80 transition-colors">
                    <td className="py-3 px-4">
                      <div className="font-bold text-slate-900">
                        {teacher ? `${teacher.name} ${teacher.surName}` : 'Docente'}
                      </div>
                      <div className="text-[11px] font-mono text-slate-500">V-{ded.teacher_cdi}</div>
                    </td>

                    <td className="py-3 px-4 font-semibold text-slate-800">
                      {isEditing ? (
                        <select
                          value={editName}
                          onChange={(e) => {
                            const val = e.target.value as Dedication['name'];
                            setEditName(val);
                            if (val === 'Tiempo Convencional') setEditHours(12);
                            if (val === 'Medio Tiempo') setEditHours(18);
                            if (val === 'Tiempo Completo') setEditHours(30);
                            if (val === 'Exclusiva') setEditHours(36);
                          }}
                          className="p-1 border border-slate-300 rounded text-xs"
                        >
                          <option value="Tiempo Convencional">Tiempo Convencional</option>
                          <option value="Medio Tiempo">Medio Tiempo</option>
                          <option value="Tiempo Completo">Tiempo Completo</option>
                          <option value="Exclusiva">Exclusiva</option>
                        </select>
                      ) : (
                        ded.name
                      )}
                    </td>

                    <td className="py-3 px-4">
                      {isEditing ? (
                        <input
                          type="number"
                          value={editHours}
                          onChange={(e) => setEditHours(Number(e.target.value))}
                          className="w-16 p-1 border border-slate-300 rounded text-xs font-mono"
                        />
                      ) : (
                        <span className="font-mono font-bold text-slate-900 bg-slate-100 px-2 py-0.5 rounded">
                          {ded.hours} hrs
                        </span>
                      )}
                    </td>

                    <td className="py-3 px-4 text-slate-700">
                      {ded.director || <span className="text-slate-400 italic">Docente Regular</span>}
                    </td>

                    <td className="py-3 px-4 text-slate-600">
                      {ded.studentNumber} estudiantes ({ded.studentHours} hrs asesoría)
                    </td>

                    <td className="py-3 px-4 text-right">
                      {(isSuperAdmin || isAreaManager) && (
                        isEditing ? (
                          <button
                            onClick={() => handleSaveEdit(ded.teacher_cdi)}
                            className="px-2.5 py-1 bg-emerald-600 text-white font-semibold rounded text-[11px]"
                          >
                            Guardar
                          </button>
                        ) : (
                          <button
                            onClick={() => {
                              setEditingCdi(ded.teacher_cdi);
                              setEditName(ded.name);
                              setEditHours(ded.hours);
                            }}
                            className="p-1.5 text-slate-600 hover:text-unerg-blue hover:bg-slate-100 rounded transition-colors"
                          >
                            <Edit3 className="w-4 h-4" />
                          </button>
                        )
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
