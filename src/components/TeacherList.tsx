import React, { useState, useMemo } from 'react';
import { useData } from '../context/DataContext';
import { useAuth } from '../context/AuthContext';
import { Teacher, Report } from '../types';
import { getDedicationRule } from '../utils/dedicationRules';
import { 
  Search, 
  Plus, 
  Filter, 
  Eye, 
  FileText, 
  Trash2, 
  GraduationCap, 
  Phone, 
  Mail, 
  Building2, 
  X,
  CheckCircle2,
  Download,
  LayoutGrid,
  List,
  SlidersHorizontal,
  MapPin,
  Clock,
  Award,
  RotateCcw
} from 'lucide-react';

interface TeacherListProps {
  onOpenTeacher: (teacher: Teacher) => void;
  onGenerateReport: (teacher: Teacher, type: Report['typeReport']) => void;
}

export const TeacherList: React.FC<TeacherListProps> = ({ onOpenTeacher, onGenerateReport }) => {
  const { 
    filteredTeachers, 
    categories, 
    dedications, 
    addTeacher, 
    deleteTeacher, 
    sedes, 
    areas, 
    programas,
    isDatabaseEmpty,
    restoreSampleData
  } = useData();
  const { isSuperAdmin, isAreaManager, isTeacher } = useAuth();

  const [searchQuery, setSearchQuery] = useState('');
  const [selectedSede, setSelectedSede] = useState('');
  const [selectedCategory, setSelectedCategory] = useState('');
  const [selectedDedication, setSelectedDedication] = useState('');
  const [viewMode, setViewMode] = useState<'table' | 'cards'>('table');
  const [showFiltersMobile, setShowFiltersMobile] = useState(false);

  // Count active filters
  const activeFiltersCount = [selectedSede, selectedCategory, selectedDedication].filter(Boolean).length;

  // New Teacher Modal
  const [showCreateModal, setShowCreateModal] = useState(false);
  const [formName, setFormName] = useState('');
  const [formSurName, setFormSurName] = useState('');
  const [formCdi, setFormCdi] = useState('');
  const [formGenre, setFormGenre] = useState<'M' | 'F'>('M');
  const [formPhone, setFormPhone] = useState('+58 412-');
  const [formEmail, setFormEmail] = useState('');
  const [formBirthDate, setFormBirthDate] = useState('1985-05-15');
  const [formDatePromotion, setFormDatePromotion] = useState(new Date().toISOString().split('T')[0]);
  const [formAsignature, setFormAsignature] = useState('Introducción a la Computación');
  const [formSede, setFormSede] = useState(sedes[0]?.nombre || '');
  const [formArea, setFormArea] = useState(areas[0]?.nombre || '');
  const [formPrograma, setFormPrograma] = useState(programas[0]?.nombre || '');

  // Filter logic
  const filtered = useMemo(() => {
    return filteredTeachers.filter(teacher => {
      const cat = categories.find(c => c.teacher_cdi === teacher.cdi);
      const ded = dedications.find(d => d.teacher_cdi === teacher.cdi);

      const matchesSearch = 
        `${teacher.name} ${teacher.surName}`.toLowerCase().includes(searchQuery.toLowerCase()) ||
        teacher.cdi.includes(searchQuery) ||
        teacher.email.toLowerCase().includes(searchQuery.toLowerCase()) ||
        teacher.asignaturePromotion.toLowerCase().includes(searchQuery.toLowerCase());

      const matchesSede = !selectedSede || teacher.sede_nombre === selectedSede;
      const matchesCat = !selectedCategory || cat?.current_category === selectedCategory;
      const matchesDed = !selectedDedication || ded?.name === selectedDedication;

      return matchesSearch && matchesSede && matchesCat && matchesDed;
    });
  }, [filteredTeachers, categories, dedications, searchQuery, selectedSede, selectedCategory, selectedDedication]);

  const handleCreateSubmit = (e: React.FormEvent) => {
    e.preventDefault();
    if (!formName || !formSurName || !formCdi || !formEmail) return;

    addTeacher({
      name: formName,
      surName: formSurName,
      cdi: formCdi,
      genre: formGenre,
      phone: formPhone,
      email: formEmail,
      birthDate: formBirthDate,
      datePromotion: formDatePromotion,
      asignaturePromotion: formAsignature,
      user_email: formEmail,
      sede_nombre: formSede,
      area_nombre: formArea,
      programa_nombre: formPrograma,
    });

    setShowCreateModal(false);
    // Reset form
    setFormName('');
    setFormSurName('');
    setFormCdi('');
    setFormEmail('');
  };

  // Export CSV
  const handleExportCsv = () => {
    const headers = ['id', 'name', 'surName', 'cdi', 'email', 'sede', 'area', 'programa'];
    const rows = filtered.map(t => [t.id, t.name, t.surName, t.cdi, t.email, t.sede_nombre, t.area_nombre, t.programa_nombre]);
    const csvContent = 'data:text/csv;charset=utf-8,' + [headers.join(';'), ...rows.map(e => e.join(';'))].join('\n');
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement('a');
    link.setAttribute('href', encodedUri);
    link.setAttribute('download', 'docentes_sigedor_unerg.csv');
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
  };

  return (
    <div className="space-y-4">
      {/* Action Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
          <h2 className="text-lg sm:text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <GraduationCap className="w-5 h-5 text-unerg-blue flex-shrink-0" />
            <span>{isTeacher ? 'Mi Expediente Académico Digital' : 'Expedientes Docentes'}</span>
          </h2>
          <p className="text-xs text-slate-500 mt-0.5">
            {isTeacher 
              ? 'Hoja de vida académica, adscripción, escalafón y emisión directa de documentos oficiales'
              : `Personal académico UNERG (${filtered.length} ${filtered.length === 1 ? 'docente' : 'docentes'} filtrados)`}
          </p>
        </div>

        <div className="flex items-center gap-2 flex-wrap sm:flex-nowrap">
          {/* View mode toggle */}
          <div className="flex items-center bg-slate-100 p-0.5 rounded-lg border border-slate-200 text-xs">
            <button
              onClick={() => setViewMode('table')}
              title="Vista de tabla"
              className={`p-1.5 rounded-md flex items-center gap-1 transition-colors ${
                viewMode === 'table' ? 'bg-white text-unerg-blue shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900'
              }`}
            >
              <List className="w-4 h-4" />
              <span className="hidden md:inline">Tabla</span>
            </button>
            <button
              onClick={() => setViewMode('cards')}
              title="Vista de tarjetas"
              className={`p-1.5 rounded-md flex items-center gap-1 transition-colors ${
                viewMode === 'cards' ? 'bg-white text-unerg-blue shadow-xs font-semibold' : 'text-slate-600 hover:text-slate-900'
              }`}
            >
              <LayoutGrid className="w-4 h-4" />
              <span className="hidden md:inline">Tarjetas</span>
            </button>
          </div>

          <button
            onClick={handleExportCsv}
            className="px-2.5 sm:px-3 py-1.5 sm:py-2 text-xs font-semibold text-slate-700 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg shadow-xs flex items-center gap-1.5 transition-colors"
          >
            <Download className="w-3.5 h-3.5" />
            <span className="hidden sm:inline">Exportar</span> CSV
          </button>

          {(isSuperAdmin || isAreaManager) && (
            <button
              onClick={() => setShowCreateModal(true)}
              className="px-3 sm:px-4 py-1.5 sm:py-2 text-xs font-bold text-white bg-unerg-blue hover:bg-unerg-blue-light rounded-lg shadow-xs flex items-center gap-1.5 transition-all"
            >
              <Plus className="w-4 h-4" />
              <span>Nuevo Docente</span>
            </button>
          )}
        </div>
      </div>

      {/* When the database is completely empty (Integration Mode) */}
      {filteredTeachers.length === 0 ? (
        <div className="bg-white rounded-2xl border-2 border-dashed border-slate-300 p-8 sm:p-12 text-center space-y-4 shadow-xs">
          <div className="w-16 h-16 rounded-2xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center mx-auto shadow-xs">
            <GraduationCap className="w-8 h-8" />
          </div>
          <div className="max-w-md mx-auto space-y-1">
            <h3 className="text-base sm:text-lg font-bold text-slate-900">
              Base de Datos en Blanco (Lista para Integración)
            </h3>
            <p className="text-xs text-slate-500 leading-relaxed">
              No hay expedientes docentes registrados actualmente. Puede comenzar la carga oficial de datos de su institución registrando el primer expediente docente, o restaurar la muestra institucional de prueba.
            </p>
          </div>
          <div className="flex items-center justify-center gap-3 flex-wrap pt-2">
            {(isSuperAdmin || isAreaManager) && (
              <button
                onClick={() => setShowCreateModal(true)}
                className="px-4 py-2 text-xs font-bold text-white bg-unerg-blue hover:bg-unerg-blue-light rounded-lg shadow-xs flex items-center gap-1.5 transition-all"
              >
                <Plus className="w-4 h-4" />
                Registrar Primer Docente
              </button>
            )}
            <button
              onClick={restoreSampleData}
              className="px-4 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg shadow-xs flex items-center gap-1.5 transition-colors"
            >
              <RotateCcw className="w-4 h-4 text-blue-600" />
              Cargar Datos de Muestra UNERG
            </button>
          </div>
        </div>
      ) : (
        <>
          {/* Search and Filters Bar */}
      <div className="bg-white p-3 sm:p-4 rounded-xl border border-slate-200 shadow-xs space-y-3">
        <div className="flex items-center gap-2">
          <div className="relative flex-1">
            <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
            <input
              type="text"
              placeholder="Buscar docente por nombre, cédula o cátedra..."
              value={searchQuery}
              onChange={(e) => setSearchQuery(e.target.value)}
              className="w-full pl-9 pr-8 py-2 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-unerg-blue"
            />
            {searchQuery && (
              <button
                onClick={() => setSearchQuery('')}
                className="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600"
              >
                <X className="w-3.5 h-3.5" />
              </button>
            )}
          </div>

          {/* Mobile Filter Collapsible Trigger */}
          <button
            onClick={() => setShowFiltersMobile(!showFiltersMobile)}
            className={`md:hidden flex items-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold border transition-colors ${
              activeFiltersCount > 0 || showFiltersMobile
                ? 'bg-blue-50 text-unerg-blue border-blue-200'
                : 'bg-slate-50 text-slate-700 border-slate-300'
            }`}
          >
            <SlidersHorizontal className="w-3.5 h-3.5" />
            <span>Filtros</span>
            {activeFiltersCount > 0 && (
              <span className="w-4 h-4 rounded-full bg-unerg-blue text-white text-[10px] flex items-center justify-center font-bold">
                {activeFiltersCount}
              </span>
            )}
          </button>
        </div>

        {/* Filter Dropdowns (always visible on desktop, toggleable on mobile) */}
        <div className={`${showFiltersMobile ? 'flex' : 'hidden md:flex'} flex-wrap gap-2.5 items-center pt-1 border-t md:border-t-0 border-slate-100`}>
          {/* Filter Sede */}
          <div className="w-full sm:w-auto">
            <label className="block md:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Sede</label>
            <select
              value={selectedSede}
              onChange={(e) => setSelectedSede(e.target.value)}
              className="w-full sm:w-auto text-xs p-2 sm:p-1.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-700 font-medium"
            >
              <option value="">Todas las Sedes</option>
              {sedes.map(s => (
                <option key={s.id} value={s.nombre}>{s.nombre}</option>
              ))}
            </select>
          </div>

          {/* Filter Escalafón */}
          <div className="w-full sm:w-auto">
            <label className="block md:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Escalafón</label>
            <select
              value={selectedCategory}
              onChange={(e) => setSelectedCategory(e.target.value)}
              className="w-full sm:w-auto text-xs p-2 sm:p-1.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-700 font-medium"
            >
              <option value="">Todo el Escalafón</option>
              <option value="Instructor">Instructor</option>
              <option value="Asistente">Asistente</option>
              <option value="Agregado">Agregado</option>
              <option value="Asociado">Asociado</option>
              <option value="Titular">Titular</option>
            </select>
          </div>

          {/* Filter Dedication */}
          <div className="w-full sm:w-auto">
            <label className="block md:hidden text-[10px] font-bold text-slate-500 uppercase mb-1">Dedicación</label>
            <select
              value={selectedDedication}
              onChange={(e) => setSelectedDedication(e.target.value)}
              className="w-full sm:w-auto text-xs p-2 sm:p-1.5 bg-slate-50 border border-slate-300 rounded-lg text-slate-700 font-medium"
            >
              <option value="">Toda Dedicación</option>
              <option value="Tiempo Convencional">2-7 TCV (Tiempo Convencional)</option>
              <option value="Medio Tiempo">18 MT (Medio Tiempo)</option>
              <option value="Tiempo Completo">30 TC (Tiempo Completo)</option>
              <option value="Exclusiva">35-36 DE (Dedicación Exclusiva)</option>
            </select>
          </div>

          {(searchQuery || selectedSede || selectedCategory || selectedDedication) && (
            <button
              onClick={() => {
                setSearchQuery('');
                setSelectedSede('');
                setSelectedCategory('');
                setSelectedDedication('');
              }}
              className="text-xs text-red-600 hover:text-red-800 font-semibold px-2 py-1.5 flex items-center gap-1 transition-colors"
            >
              <X className="w-3.5 h-3.5" /> Limpiar filtros ({activeFiltersCount + (searchQuery ? 1 : 0)})
            </button>
          )}
        </div>
      </div>

      {/* Teachers Display: Cards vs Table */}
      {viewMode === 'cards' ? (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
          {filtered.length === 0 ? (
            <div className="col-span-full py-12 text-center text-slate-500 bg-white rounded-xl border border-slate-200 space-y-2">
              <p className="text-xs">No se encontraron docentes con los criterios de búsqueda seleccionados.</p>
              <button
                onClick={() => {
                  setSearchQuery('');
                  setSelectedSede('');
                  setSelectedCategory('');
                  setSelectedDedication('');
                }}
                className="text-xs text-unerg-blue font-semibold hover:underline"
              >
                Limpiar todos los filtros
              </button>
            </div>
          ) : (
            filtered.map(teacher => {
              const cat = categories.find(c => c.teacher_cdi === teacher.cdi);
              const ded = dedications.find(d => d.teacher_cdi === teacher.cdi);

              return (
                <div 
                  key={teacher.id}
                  className="bg-white rounded-xl border border-slate-200 shadow-xs hover:shadow-md transition-shadow p-4 flex flex-col justify-between"
                >
                  <div className="space-y-2.5">
                    {/* Top row */}
                    <div className="flex items-start gap-3">
                      <div className="w-11 h-11 rounded-xl bg-unerg-blue text-white flex items-center justify-center font-bold text-sm shadow-xs flex-shrink-0">
                        {teacher.name[0]}{teacher.surName[0]}
                      </div>
                      <div className="flex-1 min-w-0">
                        <h4 className="font-bold text-slate-900 text-sm truncate">
                          {teacher.name} {teacher.surName}
                        </h4>
                        <div className="text-[11px] text-slate-500 font-mono">
                          V-{teacher.cdi}
                        </div>
                        <div className="text-[11px] text-slate-500 truncate">
                          {teacher.email}
                        </div>
                      </div>
                    </div>

                    {/* Sede & Área */}
                    <div className="bg-slate-50 p-2 rounded-lg text-[11px] space-y-0.5 border border-slate-100">
                      <div className="flex items-center gap-1.5 text-slate-700 font-medium truncate">
                        <MapPin className="w-3 h-3 text-unerg-blue flex-shrink-0" />
                        <span className="truncate">{teacher.sede_nombre}</span>
                      </div>
                      <div className="text-slate-500 truncate pl-4.5">
                        {teacher.area_nombre}
                      </div>
                    </div>

                    {/* Badges */}
                    <div className="flex flex-wrap gap-1.5">
                      <span className={`inline-flex px-2 py-0.5 rounded text-[11px] font-bold ${
                        cat?.current_category === 'Titular'
                          ? 'bg-amber-100 text-amber-800 border border-amber-300'
                          : cat?.current_category === 'Asociado'
                          ? 'bg-blue-100 text-blue-800 border border-blue-200'
                          : cat?.current_category === 'Agregado'
                          ? 'bg-indigo-100 text-indigo-800 border border-indigo-200'
                          : cat?.current_category === 'Asistente'
                          ? 'bg-emerald-100 text-emerald-800 border border-emerald-200'
                          : 'bg-slate-100 text-slate-800 border border-slate-200'
                      }`}>
                        {cat?.current_category || 'Instructor'}
                      </span>

                      {ded && (() => {
                        const rule = getDedicationRule(ded.name);
                        return (
                          <span className={`inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold border ${rule.badgeBg} ${rule.badgeText} ${rule.badgeBorder}`}>
                            <Clock className="w-3 h-3" />
                            {ded.hours}h ({rule.code})
                          </span>
                        );
                      })()}
                    </div>
                  </div>

                  {/* Actions */}
                  <div className="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between gap-1.5">
                    <button
                      onClick={() => onOpenTeacher(teacher)}
                      className="flex-1 flex items-center justify-center gap-1 px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-unerg-blue rounded-lg text-xs font-semibold border border-blue-200 transition-colors"
                    >
                      <Eye className="w-3.5 h-3.5" />
                      <span>Expediente</span>
                    </button>
                    <button
                      onClick={() => onGenerateReport(teacher, 'Constancia de Trabajo')}
                      title="Generar constancia oficial"
                      className="flex-1 flex items-center justify-center gap-1 px-2.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold border border-emerald-200 transition-colors"
                    >
                      <FileText className="w-3.5 h-3.5" />
                      <span>Constancia</span>
                    </button>
                    {isSuperAdmin && (
                      <button
                        onClick={() => {
                          if (confirm(`¿Eliminar al docente ${teacher.name} ${teacher.surName}?`)) {
                            deleteTeacher(teacher.id);
                          }
                        }}
                        title="Eliminar docente"
                        className="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-red-200"
                      >
                        <Trash2 className="w-3.5 h-3.5" />
                      </button>
                    )}
                  </div>
                </div>
              );
            })
          )}
        </div>
      ) : (
        /* Table View */
        <div className="bg-white rounded-xl border border-slate-200 shadow-xs overflow-hidden">
          <div className="overflow-x-auto">
            <table className="w-full text-left text-xs">
              <thead className="bg-slate-100/70 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px] tracking-wider">
                <tr>
                  <th className="py-3 px-4">Docente / Cédula</th>
                  <th className="py-3 px-4">Sede / Área</th>
                  <th className="py-3 px-4">Programa / Cátedra</th>
                  <th className="py-3 px-4">Escalafón</th>
                  <th className="py-3 px-4">Dedicación</th>
                  <th className="py-3 px-4 text-right">Acciones</th>
                </tr>
              </thead>
              <tbody className="divide-y divide-slate-100">
                {filtered.length === 0 ? (
                  <tr>
                    <td colSpan={6} className="py-10 text-center text-slate-500">
                      <p className="text-xs">No se encontraron docentes con los criterios de búsqueda seleccionados.</p>
                      <button
                        onClick={() => {
                          setSearchQuery('');
                          setSelectedSede('');
                          setSelectedCategory('');
                          setSelectedDedication('');
                        }}
                        className="text-xs text-unerg-blue font-semibold hover:underline mt-2 inline-block"
                      >
                        Limpiar todos los filtros
                      </button>
                    </td>
                  </tr>
                ) : (
                  filtered.map(teacher => {
                    const cat = categories.find(c => c.teacher_cdi === teacher.cdi);
                    const ded = dedications.find(d => d.teacher_cdi === teacher.cdi);

                    return (
                      <tr key={teacher.id} className="hover:bg-slate-50/80 transition-colors">
                        <td className="py-3 px-4">
                          <div className="font-bold text-slate-900 text-sm">
                            {teacher.name} {teacher.surName}
                          </div>
                          <div className="text-[11px] text-slate-500 font-mono flex items-center gap-2">
                            <span>V-{teacher.cdi}</span> • <span>{teacher.email}</span>
                          </div>
                        </td>

                        <td className="py-3 px-4">
                          <div className="font-medium text-slate-800 truncate max-w-[200px]">
                            {teacher.sede_nombre}
                          </div>
                          <div className="text-[11px] text-slate-500 truncate max-w-[200px]">
                            {teacher.area_nombre}
                          </div>
                        </td>

                        <td className="py-3 px-4">
                          <div className="font-medium text-slate-800">{teacher.programa_nombre}</div>
                          <div className="text-[11px] text-slate-500 truncate max-w-[220px]">
                            {teacher.asignaturePromotion}
                          </div>
                        </td>

                        <td className="py-3 px-4">
                          <span className={`inline-flex px-2 py-0.5 rounded-full text-[11px] font-bold ${
                            cat?.current_category === 'Titular'
                              ? 'bg-amber-100 text-amber-800 border border-amber-300'
                              : cat?.current_category === 'Asociado'
                              ? 'bg-blue-100 text-blue-800 border border-blue-200'
                              : cat?.current_category === 'Agregado'
                              ? 'bg-indigo-100 text-indigo-800 border border-indigo-200'
                              : cat?.current_category === 'Asistente'
                              ? 'bg-emerald-100 text-emerald-800 border border-emerald-200'
                              : 'bg-slate-100 text-slate-800 border border-slate-200'
                          }`}>
                            {cat?.current_category || 'Instructor'}
                          </span>
                        </td>

                        <td className="py-3 px-4">
                          {ded ? (() => {
                            const rule = getDedicationRule(ded.name);
                            return (
                              <div>
                                <div className="flex items-center gap-1.5">
                                  <span className={`font-mono font-bold text-[10px] px-1.5 py-0.5 rounded border ${rule.badgeBg} ${rule.badgeText} ${rule.badgeBorder}`}>
                                    {rule.denotation}
                                  </span>
                                  <span className="font-semibold text-slate-800 text-xs">{ded.name}</span>
                                </div>
                                <div className="text-[11px] text-slate-500 mt-0.5 font-mono">{ded.hours} hrs/sem</div>
                              </div>
                            );
                          })() : (
                            <span className="text-slate-400 italic text-xs">Sin asignar</span>
                          )}
                        </td>

                        <td className="py-3 px-4 text-right">
                          <div className="flex items-center justify-end gap-1.5">
                            <button
                              onClick={() => onOpenTeacher(teacher)}
                              title="Ver Expediente 360°"
                              className="p-1.5 text-unerg-blue hover:bg-blue-50 rounded-lg transition-colors border border-blue-200"
                            >
                              <Eye className="w-4 h-4" />
                            </button>
                            <button
                              onClick={() => onGenerateReport(teacher, 'Constancia de Trabajo')}
                              title="Generar Constancia Oficial"
                              className="p-1.5 text-emerald-700 hover:bg-emerald-50 rounded-lg transition-colors border border-emerald-200"
                            >
                              <FileText className="w-4 h-4" />
                            </button>
                            {isSuperAdmin && (
                              <button
                                onClick={() => {
                                  if (confirm(`¿Eliminar al docente ${teacher.name} ${teacher.surName}?`)) {
                                    deleteTeacher(teacher.id);
                                  }
                                }}
                                title="Eliminar registro"
                                className="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-colors border border-red-200"
                              >
                                <Trash2 className="w-4 h-4" />
                              </button>
                            )}
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
      )}
      </>
      )}

      {/* Modal: Crear Nuevo Docente */}
      {showCreateModal && (
        <div className="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
          <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in-95">
            <div className="bg-unerg-blue text-white p-4 flex items-center justify-between">
              <h3 className="font-bold text-sm flex items-center gap-2">
                <GraduationCap className="w-5 h-5 text-amber-400" />
                Registrar Nuevo Expediente Docente
              </h3>
              <button onClick={() => setShowCreateModal(false)} className="text-slate-300 hover:text-white">
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleCreateSubmit} className="p-6 space-y-4 text-xs">
              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Nombres:</label>
                  <input
                    type="text"
                    required
                    placeholder="Ej. Frank Alfonso"
                    value={formName}
                    onChange={(e) => setFormName(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg"
                  />
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Apellidos:</label>
                  <input
                    type="text"
                    required
                    placeholder="Ej. Sousa Rivas"
                    value={formSurName}
                    onChange={(e) => setFormSurName(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Cédula (CDI):</label>
                  <input
                    type="text"
                    required
                    placeholder="10101099"
                    value={formCdi}
                    onChange={(e) => setFormCdi(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg font-mono"
                  />
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Sexo / Género:</label>
                  <select
                    value={formGenre}
                    onChange={(e) => setFormGenre(e.target.value as 'M' | 'F')}
                    className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                  >
                    <option value="M">Masculino</option>
                    <option value="F">Femenino</option>
                  </select>
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Teléfono:</label>
                  <input
                    type="text"
                    value={formPhone}
                    onChange={(e) => setFormPhone(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Correo Institucional:</label>
                  <input
                    type="email"
                    required
                    placeholder="usuario@sigedor.com"
                    value={formEmail}
                    onChange={(e) => setFormEmail(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg"
                  />
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Asignatura por Concurso:</label>
                  <input
                    type="text"
                    required
                    value={formAsignature}
                    onChange={(e) => setFormAsignature(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg"
                  />
                </div>
              </div>

              <div className="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Sede / Núcleo:</label>
                  <select
                    value={formSede}
                    onChange={(e) => setFormSede(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                  >
                    {sedes.map(s => <option key={s.id} value={s.nombre}>{s.nombre}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Área Académica:</label>
                  <select
                    value={formArea}
                    onChange={(e) => setFormArea(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                  >
                    {areas.map(a => <option key={a.id} value={a.nombre}>{a.nombre}</option>)}
                  </select>
                </div>
                <div>
                  <label className="block font-semibold text-slate-700 mb-1">Programa:</label>
                  <select
                    value={formPrograma}
                    onChange={(e) => setFormPrograma(e.target.value)}
                    className="w-full p-2 border border-slate-300 rounded-lg font-medium"
                  >
                    {programas.map(p => <option key={p.id} value={p.nombre}>{p.nombre}</option>)}
                  </select>
                </div>
              </div>

              <div className="pt-4 border-t border-slate-200 flex justify-end gap-2">
                <button
                  type="button"
                  onClick={() => setShowCreateModal(false)}
                  className="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  className="px-4 py-2 bg-unerg-blue hover:bg-unerg-blue-light text-white font-bold rounded-lg shadow-sm"
                >
                  Registrar Expediente
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
