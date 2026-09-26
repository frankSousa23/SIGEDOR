import React, { useState, useMemo } from 'react';
import { useData } from '../context/DataContext';
import { useAuth } from '../context/AuthContext';
import { 
  Clock, 
  Search, 
  Edit3, 
  CheckCircle2, 
  AlertTriangle, 
  Info, 
  BookOpen, 
  ChevronDown, 
  ChevronUp, 
  X, 
  Award,
  Users,
  ShieldCheck,
  Check
} from 'lucide-react';
import { Dedication, DedicationType } from '../types';
import { 
  DEDICATION_RULES, 
  getDedicationRule, 
  getDedicationCode, 
  getDedicationDenotation, 
  getAllowedHours, 
  getDefaultHours, 
  isValidDedicationHours,
  getDedicationHoursWarning,
  DedicationCode
} from '../utils/dedicationRules';

export const DedicationManagement: React.FC = () => {
  const { dedications, teachers, updateDedication } = useData();
  const { isSuperAdmin, isAreaManager } = useAuth();

  // Search & Filters
  const [search, setSearch] = useState('');
  const [selectedFilter, setSelectedFilter] = useState<'ALL' | DedicationCode>('ALL');
  const [showOnlyWarnings, setShowOnlyWarnings] = useState(false);
  const [showNormativeInfo, setShowNormativeInfo] = useState(false);

  // Edit Modal State
  const [editingDedication, setEditingDedication] = useState<Dedication | null>(null);
  const [modalName, setModalName] = useState<DedicationType>('Tiempo Completo');
  const [modalHours, setModalHours] = useState<number>(30);
  const [modalDirector, setModalDirector] = useState<string>('');
  const [modalStudentNumber, setModalStudentNumber] = useState<number>(30);
  const [modalStudentHours, setModalStudentHours] = useState<number>(4);
  const [modalInfo, setModalInfo] = useState<string>('');
  const [savedSuccessMsg, setSavedSuccessMsg] = useState<string | null>(null);

  // Quick inline edit state (fallback)
  const [inlineEditingCdi, setInlineEditingCdi] = useState<string | null>(null);
  const [inlineHours, setInlineHours] = useState<number>(30);
  const [inlineName, setInlineName] = useState<DedicationType>('Tiempo Completo');

  // Stats calculation
  const stats = useMemo(() => {
    const tcv = dedications.filter(d => d.name === 'Tiempo Convencional');
    const mt = dedications.filter(d => d.name === 'Medio Tiempo');
    const tc = dedications.filter(d => d.name === 'Tiempo Completo');
    const de = dedications.filter(d => d.name === 'Exclusiva');
    const warnings = dedications.filter(d => !isValidDedicationHours(d.name, d.hours));
    const total = dedications.length;
    const totalHours = dedications.reduce((acc, curr) => acc + curr.hours, 0);
    const avgHours = total > 0 ? (totalHours / total).toFixed(1) : '0';

    return {
      total,
      avgHours,
      warningsCount: warnings.length,
      tcv: { count: tcv.length, pct: total ? Math.round((tcv.length / total) * 100) : 0 },
      mt: { count: mt.length, pct: total ? Math.round((mt.length / total) * 100) : 0 },
      tc: { count: tc.length, pct: total ? Math.round((tc.length / total) * 100) : 0 },
      de: { count: de.length, pct: total ? Math.round((de.length / total) * 100) : 0 },
    };
  }, [dedications]);

  // Filtered List
  const filtered = useMemo(() => {
    return dedications.filter(d => {
      const teacher = teachers.find(t => t.cdi === d.teacher_cdi);
      const name = teacher ? `${teacher.name} ${teacher.surName}`.toLowerCase() : '';
      const cdi = d.teacher_cdi;
      const code = getDedicationCode(d.name);

      const matchesSearch = 
        name.includes(search.toLowerCase()) || 
        cdi.includes(search) ||
        (d.director && d.director.toLowerCase().includes(search.toLowerCase())) ||
        (teacher?.sede_nombre && teacher.sede_nombre.toLowerCase().includes(search.toLowerCase())) ||
        (teacher?.area_nombre && teacher.area_nombre.toLowerCase().includes(search.toLowerCase()));

      const matchesFilter = selectedFilter === 'ALL' || code === selectedFilter;
      const matchesWarning = !showOnlyWarnings || !isValidDedicationHours(d.name, d.hours);

      return matchesSearch && matchesFilter && matchesWarning;
    });
  }, [dedications, teachers, search, selectedFilter, showOnlyWarnings]);

  // Open Edit Modal
  const handleOpenEditModal = (ded: Dedication) => {
    setEditingDedication(ded);
    setModalName(ded.name);
    
    // If current hours are not valid for this name, suggest default
    if (!isValidDedicationHours(ded.name, ded.hours)) {
      setModalHours(getDefaultHours(ded.name));
    } else {
      setModalHours(ded.hours);
    }

    setModalDirector(ded.director || '');
    setModalStudentNumber(ded.studentNumber || 25);
    setModalStudentHours(ded.studentHours || 4);
    setModalInfo(ded.info || '');
  };

  // Change modality in modal
  const handleChangeModalName = (newName: DedicationType) => {
    setModalName(newName);
    setModalHours(getDefaultHours(newName));
  };

  // Save Modal
  const handleSaveModal = (e: React.FormEvent) => {
    e.preventDefault();
    if (!editingDedication) return;

    // Validate
    if (!isValidDedicationHours(modalName, modalHours)) {
      alert(`Horas no permitidas para ${modalName}. Consulte las opciones permitidas.`);
      return;
    }

    updateDedication(editingDedication.teacher_cdi, {
      name: modalName,
      hours: modalHours,
      director: modalDirector.trim() || undefined,
      studentNumber: Number(modalStudentNumber) || 0,
      studentHours: Number(modalStudentHours) || 0,
      info: modalInfo.trim() || undefined,
    });

    const teacher = teachers.find(t => t.cdi === editingDedication.teacher_cdi);
    const teacherName = teacher ? `${teacher.name} ${teacher.surName}` : `V-${editingDedication.teacher_cdi}`;
    setSavedSuccessMsg(`Dedicación de ${teacherName} actualizada exitosamente a ${modalHours}h (${getDedicationDenotation(modalName)}).`);
    setTimeout(() => setSavedSuccessMsg(null), 4000);

    setEditingDedication(null);
  };

  // Quick fix for out-of-norm hours
  const handleQuickFixHours = (ded: Dedication) => {
    const standardHours = getDefaultHours(ded.name);
    updateDedication(ded.teacher_cdi, {
      hours: standardHours
    });
    setSavedSuccessMsg(`Horas normalizadas a ${standardHours}h (${getDedicationDenotation(ded.name)}) para V-${ded.teacher_cdi}.`);
    setTimeout(() => setSavedSuccessMsg(null), 3000);
  };

  return (
    <div className="space-y-5">
      {/* Top Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
          <h2 className="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
            <Clock className="w-5 h-5 text-blue-600" />
            Régimen de Dedicación y Carga Horaria Docente
          </h2>
          <p className="text-xs text-slate-500 mt-0.5">
            Distribución reglamentaria de dedicaciones universitarias: <strong className="text-slate-700">2-7 TCV</strong> • <strong className="text-slate-700">18 MT</strong> • <strong className="text-slate-700">30 TC</strong> • <strong className="text-slate-700">35-36 DE</strong>
          </p>
        </div>

        <button
          onClick={() => setShowNormativeInfo(!showNormativeInfo)}
          className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-unerg-blue bg-blue-50 hover:bg-blue-100/80 border border-blue-200 rounded-lg transition-colors shadow-xs"
        >
          <BookOpen className="w-4 h-4 text-blue-600" />
          <span>Normativa de Dedicaciones</span>
          {showNormativeInfo ? <ChevronUp className="w-3.5 h-3.5 ml-0.5" /> : <ChevronDown className="w-3.5 h-3.5 ml-0.5" />}
        </button>
      </div>

      {/* Success Notification Alert */}
      {savedSuccessMsg && (
        <div className="p-3 bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs rounded-xl flex items-center gap-2 shadow-xs animate-fadeIn">
          <CheckCircle2 className="w-4 h-4 text-emerald-600 shrink-0" />
          <span>{savedSuccessMsg}</span>
        </div>
      )}

      {/* Normative Regulatory Guide Panel (Collapsible) */}
      {showNormativeInfo && (
        <div className="bg-slate-900 text-white rounded-xl p-4 sm:p-5 shadow-md border border-slate-800 space-y-4">
          <div className="flex items-start justify-between">
            <div className="flex items-center gap-2">
              <ShieldCheck className="w-5 h-5 text-blue-400" />
              <h3 className="text-sm font-bold text-white tracking-wide">
                Régimen de Asignación Horaria y Dedicación Académica (UNERG)
              </h3>
            </div>
            <button
              onClick={() => setShowNormativeInfo(false)}
              className="text-slate-400 hover:text-white p-1 rounded-md transition-colors"
            >
              <X className="w-4 h-4" />
            </button>
          </div>

          <p className="text-xs text-slate-300 leading-relaxed">
            De conformidad con la Ley de Universidades y la reglamentación académica vigente, las contrataciones y asignaciones de carga horaria docente se rigen por cuatro modalidades fundamentales. Los números de horas entre las distintas dedicaciones poco se asignan de forma arbitraria; la distribución consuetudinaria y legal se denota de la siguiente manera:
          </p>

          <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 text-xs pt-1">
            <div className="p-3 bg-slate-800/80 rounded-lg border border-teal-500/30">
              <div className="flex items-center justify-between font-bold text-teal-300 text-sm mb-1">
                <span>2-7 TCV</span>
                <span className="text-[10px] bg-teal-900/60 text-teal-200 px-1.5 py-0.5 rounded border border-teal-700/50">2 a 7 Horas</span>
              </div>
              <div className="font-medium text-slate-200 mb-1">Tiempo Convencional</div>
              <p className="text-[11px] text-slate-400 leading-tight">
                Contratación por horas semanales para cátedras específicas. Se asigna estrictamente en el rango de 2 a 7 horas de aula.
              </p>
            </div>

            <div className="p-3 bg-slate-800/80 rounded-lg border border-amber-500/30">
              <div className="flex items-center justify-between font-bold text-amber-300 text-sm mb-1">
                <span>18 MT</span>
                <span className="text-[10px] bg-amber-900/60 text-amber-200 px-1.5 py-0.5 rounded border border-amber-700/50">18 Horas Fijas</span>
              </div>
              <div className="font-medium text-slate-200 mb-1">Medio Tiempo</div>
              <p className="text-[11px] text-slate-400 leading-tight">
                Media jornada académica regular estipulada exactamente en 18 horas semanales, distribuidas entre docencia y preparación.
              </p>
            </div>

            <div className="p-3 bg-slate-800/80 rounded-lg border border-blue-500/30">
              <div className="flex items-center justify-between font-bold text-blue-300 text-sm mb-1">
                <span>30 TC</span>
                <span className="text-[10px] bg-blue-900/60 text-blue-200 px-1.5 py-0.5 rounded border border-blue-700/50">30 Horas Fijas</span>
              </div>
              <div className="font-medium text-slate-200 mb-1">Tiempo Completo</div>
              <p className="text-[11px] text-slate-400 leading-tight">
                Jornada académica integral establecida en 30 horas semanales, combinando docencia presencial, investigación y comisiones.
              </p>
            </div>

            <div className="p-3 bg-slate-800/80 rounded-lg border border-purple-500/30">
              <div className="flex items-center justify-between font-bold text-purple-300 text-sm mb-1">
                <span>35-36 DE</span>
                <span className="text-[10px] bg-purple-900/60 text-purple-200 px-1.5 py-0.5 rounded border border-purple-700/50">35 o 36 Horas</span>
              </div>
              <div className="font-medium text-slate-200 mb-1">Dedicación Exclusiva</div>
              <p className="text-[11px] text-slate-400 leading-tight">
                Máxima categoría de permanencia universitaria asignada a 35 o 36 horas semanales con alta carga investigativa y de gestión.
              </p>
            </div>
          </div>
        </div>
      )}

      {/* Modality Metric Cards (Quick Filter Cards) */}
      <div className="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        {/* 2-7 TCV */}
        <div 
          onClick={() => setSelectedFilter(selectedFilter === 'TCV' ? 'ALL' : 'TCV')}
          className={`cursor-pointer p-3.5 sm:p-4 rounded-xl border transition-all ${
            selectedFilter === 'TCV' 
              ? 'bg-teal-50 border-teal-400 ring-2 ring-teal-500/20 shadow-xs' 
              : 'bg-white border-slate-200 hover:border-teal-300 hover:bg-teal-50/30 shadow-xs'
          }`}
        >
          <div className="flex items-center justify-between text-xs mb-1.5">
            <span className="font-bold text-teal-700 bg-teal-100/70 border border-teal-200 px-2 py-0.5 rounded-md font-mono text-[11px]">
              2-7 TCV
            </span>
            <span className="text-[11px] text-slate-500 font-medium">{stats.tcv.pct}%</span>
          </div>
          <div className="text-xl sm:text-2xl font-extrabold text-slate-900">{stats.tcv.count}</div>
          <div className="text-xs font-semibold text-slate-700 mt-0.5">Tiempo Convencional</div>
          <div className="text-[11px] text-slate-500 mt-0.5">2 a 7 horas semanales</div>
        </div>

        {/* 18 MT */}
        <div 
          onClick={() => setSelectedFilter(selectedFilter === 'MT' ? 'ALL' : 'MT')}
          className={`cursor-pointer p-3.5 sm:p-4 rounded-xl border transition-all ${
            selectedFilter === 'MT' 
              ? 'bg-amber-50 border-amber-400 ring-2 ring-amber-500/20 shadow-xs' 
              : 'bg-white border-slate-200 hover:border-amber-300 hover:bg-amber-50/30 shadow-xs'
          }`}
        >
          <div className="flex items-center justify-between text-xs mb-1.5">
            <span className="font-bold text-amber-700 bg-amber-100/70 border border-amber-200 px-2 py-0.5 rounded-md font-mono text-[11px]">
              18 MT
            </span>
            <span className="text-[11px] text-slate-500 font-medium">{stats.mt.pct}%</span>
          </div>
          <div className="text-xl sm:text-2xl font-extrabold text-slate-900">{stats.mt.count}</div>
          <div className="text-xs font-semibold text-slate-700 mt-0.5">Medio Tiempo</div>
          <div className="text-[11px] text-slate-500 mt-0.5">18 horas fijas</div>
        </div>

        {/* 30 TC */}
        <div 
          onClick={() => setSelectedFilter(selectedFilter === 'TC' ? 'ALL' : 'TC')}
          className={`cursor-pointer p-3.5 sm:p-4 rounded-xl border transition-all ${
            selectedFilter === 'TC' 
              ? 'bg-blue-50 border-blue-400 ring-2 ring-blue-500/20 shadow-xs' 
              : 'bg-white border-slate-200 hover:border-blue-300 hover:bg-blue-50/30 shadow-xs'
          }`}
        >
          <div className="flex items-center justify-between text-xs mb-1.5">
            <span className="font-bold text-blue-700 bg-blue-100/70 border border-blue-200 px-2 py-0.5 rounded-md font-mono text-[11px]">
              30 TC
            </span>
            <span className="text-[11px] text-slate-500 font-medium">{stats.tc.pct}%</span>
          </div>
          <div className="text-xl sm:text-2xl font-extrabold text-slate-900">{stats.tc.count}</div>
          <div className="text-xs font-semibold text-slate-700 mt-0.5">Tiempo Completo</div>
          <div className="text-[11px] text-slate-500 mt-0.5">30 horas fijas</div>
        </div>

        {/* 35-36 DE */}
        <div 
          onClick={() => setSelectedFilter(selectedFilter === 'DE' ? 'ALL' : 'DE')}
          className={`cursor-pointer p-3.5 sm:p-4 rounded-xl border transition-all ${
            selectedFilter === 'DE' 
              ? 'bg-purple-50 border-purple-400 ring-2 ring-purple-500/20 shadow-xs' 
              : 'bg-white border-slate-200 hover:border-purple-300 hover:bg-purple-50/30 shadow-xs'
          }`}
        >
          <div className="flex items-center justify-between text-xs mb-1.5">
            <span className="font-bold text-purple-700 bg-purple-100/70 border border-purple-200 px-2 py-0.5 rounded-md font-mono text-[11px]">
              35-36 DE
            </span>
            <span className="text-[11px] text-slate-500 font-medium">{stats.de.pct}%</span>
          </div>
          <div className="text-xl sm:text-2xl font-extrabold text-slate-900">{stats.de.count}</div>
          <div className="text-xs font-semibold text-slate-700 mt-0.5">Dedicación Exclusiva</div>
          <div className="text-[11px] text-slate-500 mt-0.5">35 a 36 horas semanales</div>
        </div>
      </div>

      {/* Warning banner if any teachers have hours outside the normative */}
      {stats.warningsCount > 0 && (
        <div className="p-3.5 bg-amber-50 border border-amber-200 rounded-xl flex items-center justify-between gap-3 text-xs text-amber-900 shadow-xs">
          <div className="flex items-center gap-2.5">
            <AlertTriangle className="w-4 h-4 text-amber-600 shrink-0" />
            <div>
              <span className="font-bold">Alerta de Carga Horaria:</span> Hay {stats.warningsCount} docente(s) con asignación horaria fuera del régimen normativo estándar (2-7 TCV, 18 MT, 30 TC, 35-36 DE).
            </div>
          </div>
          <button
            onClick={() => setShowOnlyWarnings(!showOnlyWarnings)}
            className={`px-2.5 py-1 text-xs font-semibold rounded-md border transition-colors shrink-0 ${
              showOnlyWarnings 
                ? 'bg-amber-600 text-white border-amber-600' 
                : 'bg-white text-amber-800 border-amber-300 hover:bg-amber-100'
            }`}
          >
            {showOnlyWarnings ? 'Mostrar todos' : 'Filtrar desajustes'}
          </button>
        </div>
      )}

      {/* Search and Filters Bar */}
      <div className="bg-white p-3.5 rounded-xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-3">
        <div className="relative flex-1 max-w-md">
          <Search className="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" />
          <input
            type="text"
            placeholder="Buscar por docente, cédula, cargo o área..."
            value={search}
            onChange={(e) => setSearch(e.target.value)}
            className="w-full pl-9 pr-3 py-1.5 text-xs bg-slate-50 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-unerg-blue"
          />
        </div>

        {/* Filter pills */}
        <div className="flex items-center gap-1.5 overflow-x-auto pb-1 md:pb-0 text-xs">
          <span className="text-slate-400 font-semibold text-[11px] mr-1 hidden sm:inline">Régimen:</span>
          <button
            onClick={() => setSelectedFilter('ALL')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors shrink-0 ${
              selectedFilter === 'ALL'
                ? 'bg-slate-800 text-white shadow-xs'
                : 'bg-slate-100 text-slate-600 hover:bg-slate-200'
            }`}
          >
            Todos ({dedications.length})
          </button>

          <button
            onClick={() => setSelectedFilter('TCV')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors shrink-0 ${
              selectedFilter === 'TCV'
                ? 'bg-teal-600 text-white shadow-xs'
                : 'bg-teal-50 text-teal-700 hover:bg-teal-100 border border-teal-200'
            }`}
          >
            2-7 TCV
          </button>

          <button
            onClick={() => setSelectedFilter('MT')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors shrink-0 ${
              selectedFilter === 'MT'
                ? 'bg-amber-600 text-white shadow-xs'
                : 'bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200'
            }`}
          >
            18 MT
          </button>

          <button
            onClick={() => setSelectedFilter('TC')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors shrink-0 ${
              selectedFilter === 'TC'
                ? 'bg-blue-600 text-white shadow-xs'
                : 'bg-blue-50 text-blue-700 hover:bg-blue-100 border border-blue-200'
            }`}
          >
            30 TC
          </button>

          <button
            onClick={() => setSelectedFilter('DE')}
            className={`px-2.5 py-1 rounded-lg text-xs font-semibold transition-colors shrink-0 ${
              selectedFilter === 'DE'
                ? 'bg-purple-600 text-white shadow-xs'
                : 'bg-purple-50 text-purple-700 hover:bg-purple-100 border border-purple-200'
            }`}
          >
            35-36 DE
          </button>
        </div>
      </div>

      {/* Main Table */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="overflow-x-auto">
          <table className="w-full text-left text-xs">
            <thead className="bg-slate-100/70 border-b border-slate-200 text-slate-600 uppercase font-semibold text-[11px] tracking-wider">
              <tr>
                <th className="py-3 px-4">Docente</th>
                <th className="py-3 px-4">Régimen y Modalidad</th>
                <th className="py-3 px-4">Carga Horaria</th>
                <th className="py-3 px-4">Cargo Directivo / Coordinación</th>
                <th className="py-3 px-4">Atención a Estudiantes</th>
                <th className="py-3 px-4 text-right">Acciones</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-slate-100">
              {filtered.length === 0 ? (
                <tr>
                  <td colSpan={6} className="py-12 text-center text-slate-500 text-xs">
                    <Clock className="w-8 h-8 text-slate-300 mx-auto mb-2" />
                    No se encontraron registros de dedicación con los filtros aplicados.
                  </td>
                </tr>
              ) : (
                filtered.map(ded => {
                  const teacher = teachers.find(t => t.cdi === ded.teacher_cdi);
                  const rule = getDedicationRule(ded.name);
                  const isValidHours = isValidDedicationHours(ded.name, ded.hours);
                  const warningMsg = getDedicationHoursWarning(ded.name, ded.hours);

                  return (
                    <tr key={ded.id} className="hover:bg-slate-50/80 transition-colors">
                      {/* Teacher info */}
                      <td className="py-3.5 px-4">
                        <div className="font-bold text-slate-900">
                          {teacher ? `${teacher.name} ${teacher.surName}` : 'Docente'}
                        </div>
                        <div className="text-[11px] font-mono text-slate-500 flex items-center gap-1.5 mt-0.5">
                          <span>V-{ded.teacher_cdi}</span>
                          {teacher?.sede_nombre && (
                            <>
                              <span className="text-slate-300">•</span>
                              <span className="text-slate-600 truncate max-w-[140px]" title={teacher.sede_nombre}>
                                {teacher.sede_nombre.split('/')[0]}
                              </span>
                            </>
                          )}
                        </div>
                      </td>

                      {/* Modalidad and denotation */}
                      <td className="py-3.5 px-4">
                        <div className="flex items-center gap-1.5">
                          <span className={`inline-block font-mono font-bold text-[11px] px-2 py-0.5 rounded-md border ${rule.badgeBg} ${rule.badgeText} ${rule.badgeBorder}`}>
                            {rule.denotation}
                          </span>
                          <span className="font-semibold text-slate-800 text-xs">
                            {ded.name}
                          </span>
                        </div>
                        <div className="text-[10px] text-slate-400 mt-0.5">
                          {rule.hoursRangeText} regular
                        </div>
                      </td>

                      {/* Carga Horaria Semanal */}
                      <td className="py-3.5 px-4">
                        <div className="flex items-center gap-2">
                          <span className={`font-mono font-bold px-2 py-0.5 rounded text-xs border ${
                            isValidHours 
                              ? 'bg-slate-100 text-slate-900 border-slate-200' 
                              : 'bg-rose-50 text-rose-700 border-rose-300 ring-1 ring-rose-400/30'
                          }`}>
                            {ded.hours} hrs/sem
                          </span>

                          {!isValidHours && (
                            <span 
                              title={warningMsg || 'Horas fuera de norma'}
                              className="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200 cursor-help"
                            >
                              <AlertTriangle className="w-3 h-3 text-rose-500" />
                              Desajuste
                            </span>
                          )}
                        </div>

                        {!isValidHours && (
                          <div className="text-[10px] text-rose-600 mt-1 flex items-center gap-1">
                            <span>Debe ser {rule.hoursRangeText}.</span>
                            {(isSuperAdmin || isAreaManager) && (
                              <button
                                onClick={() => handleQuickFixHours(ded)}
                                className="underline font-semibold hover:text-rose-800"
                              >
                                Normalizar a {getDefaultHours(ded.name)}h
                              </button>
                            )}
                          </div>
                        )}
                      </td>

                      {/* Cargo directivo */}
                      <td className="py-3.5 px-4 text-slate-700">
                        {ded.director ? (
                          <span className="inline-flex items-center gap-1 bg-amber-50 text-amber-800 border border-amber-200 px-2 py-0.5 rounded-md text-[11px] font-semibold">
                            <Award className="w-3 h-3 text-amber-600" />
                            {ded.director}
                          </span>
                        ) : (
                          <span className="text-slate-400 text-xs italic">Docente Regular</span>
                        )}
                      </td>

                      {/* Atención estudiantil */}
                      <td className="py-3.5 px-4 text-slate-600">
                        <div className="font-semibold text-slate-800 text-xs">
                          {ded.studentNumber} estudiantes
                        </div>
                        <div className="text-[11px] text-slate-500">
                          {ded.studentHours} hrs/sem asesoría
                        </div>
                      </td>

                      {/* Acciones */}
                      <td className="py-3.5 px-4 text-right">
                        {(isSuperAdmin || isAreaManager) && (
                          <button
                            onClick={() => handleOpenEditModal(ded)}
                            className="inline-flex items-center gap-1.5 px-2.5 py-1.5 bg-slate-100 hover:bg-unerg-blue hover:text-white text-slate-700 text-xs font-semibold rounded-lg transition-colors border border-slate-200 shadow-2xs"
                            title="Modificar asignación de dedicación y horas"
                          >
                            <Edit3 className="w-3.5 h-3.5" />
                            <span>Editar</span>
                          </button>
                        )}
                      </td>
                    </tr>
                  );
                })
              )}
            </tbody>
          </table>
        </div>

        {/* Table footer summary */}
        <div className="bg-slate-50 px-4 py-2.5 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between text-[11px] text-slate-500 gap-2">
          <div>
            Mostrando <strong>{filtered.length}</strong> de <strong>{dedications.length}</strong> docentes registrados
          </div>
          <div className="flex items-center gap-3">
            <span>Promedio general: <strong className="text-slate-800 font-mono">{stats.avgHours} hrs/docente</strong></span>
            <span>•</span>
            <span className="text-slate-600">Distribución estándar: <strong>2-7 TCV</strong> | <strong>18 MT</strong> | <strong>30 TC</strong> | <strong>35-36 DE</strong></span>
          </div>
        </div>
      </div>

      {/* Edit Dedication Modal */}
      {editingDedication && (
        <div className="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 overflow-y-auto">
          <div className="bg-white rounded-2xl max-w-lg w-full p-5 sm:p-6 shadow-2xl border border-slate-200 animate-fadeIn my-8">
            <div className="flex items-start justify-between pb-3 border-b border-slate-200">
              <div>
                <h3 className="text-base font-bold text-slate-900 flex items-center gap-2">
                  <Clock className="w-5 h-5 text-unerg-blue" />
                  Modificar Carga Horaria y Dedicación
                </h3>
                {(() => {
                  const teacher = teachers.find(t => t.cdi === editingDedication.teacher_cdi);
                  return (
                    <p className="text-xs text-slate-500 mt-0.5">
                      {teacher ? `${teacher.name} ${teacher.surName}` : 'Docente'} • <span className="font-mono">V-{editingDedication.teacher_cdi}</span>
                    </p>
                  );
                })()}
              </div>
              <button
                onClick={() => setEditingDedication(null)}
                className="text-slate-400 hover:text-slate-600 p-1 rounded-lg"
              >
                <X className="w-5 h-5" />
              </button>
            </div>

            <form onSubmit={handleSaveModal} className="space-y-4 pt-4 text-xs">
              {/* Modality selector */}
              <div>
                <label className="block font-bold text-slate-700 uppercase tracking-wider text-[11px] mb-1.5">
                  Modalidad de Dedicación
                </label>
                <div className="grid grid-cols-2 gap-2">
                  {(Object.keys(DEDICATION_RULES) as DedicationType[]).map(type => {
                    const rule = DEDICATION_RULES[type];
                    const isSelected = modalName === type;
                    return (
                      <button
                        type="button"
                        key={type}
                        onClick={() => handleChangeModalName(type)}
                        className={`p-2.5 rounded-xl border text-left transition-all ${
                          isSelected
                            ? `${rule.badgeBg} ${rule.badgeBorder} ring-2 ring-unerg-blue/30`
                            : 'bg-white border-slate-200 hover:bg-slate-50'
                        }`}
                      >
                        <div className="flex items-center justify-between">
                          <span className="font-mono font-bold text-xs text-slate-900">
                            {rule.denotation}
                          </span>
                          {isSelected && <Check className="w-3.5 h-3.5 text-unerg-blue" />}
                        </div>
                        <div className="text-[11px] font-semibold text-slate-700 mt-0.5">{type}</div>
                        <div className="text-[10px] text-slate-500 mt-0.5">{rule.hoursRangeText}</div>
                      </button>
                    );
                  })}
                </div>
              </div>

              {/* Hours selection / allocation (Customized by dedication rule) */}
              <div className="bg-slate-50 p-3.5 rounded-xl border border-slate-200 space-y-2">
                <div className="flex items-center justify-between">
                  <label className="font-bold text-slate-800 text-[11px]">
                    Carga Horaria Semanal a Asignar
                  </label>
                  <span className="text-[11px] font-mono font-bold text-unerg-blue bg-white px-2 py-0.5 rounded border border-slate-200">
                    {modalHours} horas semanales
                  </span>
                </div>

                {/* Specific options depending on the rule */}
                {modalName === 'Tiempo Convencional' && (
                  <div>
                    <p className="text-[11px] text-slate-600 mb-2">
                      Régimen <strong className="text-teal-700">2-7 TCV</strong>: Se asigna con regularidad entre 2 y 7 horas semanales. Seleccione una de las cargas estándar:
                    </p>
                    <div className="grid grid-cols-6 gap-1.5">
                      {[2, 3, 4, 5, 6, 7].map(h => (
                        <button
                          type="button"
                          key={h}
                          onClick={() => setModalHours(h)}
                          className={`py-1.5 rounded-lg font-mono font-bold text-xs border transition-colors ${
                            modalHours === h
                              ? 'bg-teal-600 text-white border-teal-600 shadow-xs'
                              : 'bg-white text-slate-700 border-slate-200 hover:bg-teal-50'
                          }`}
                        >
                          {h}h
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {modalName === 'Medio Tiempo' && (
                  <div>
                    <p className="text-[11px] text-slate-600 mb-2">
                      Régimen <strong className="text-amber-700">18 MT</strong>: Carga contractual regular establecida fijamente en 18 horas semanales.
                    </p>
                    <button
                      type="button"
                      onClick={() => setModalHours(18)}
                      className="w-full py-1.5 rounded-lg font-mono font-bold text-xs bg-amber-600 text-white border border-amber-600 shadow-xs flex items-center justify-center gap-1.5"
                    >
                      <Check className="w-3.5 h-3.5" />
                      18 horas semanales (Fijada según normativa)
                    </button>
                  </div>
                )}

                {modalName === 'Tiempo Completo' && (
                  <div>
                    <p className="text-[11px] text-slate-600 mb-2">
                      Régimen <strong className="text-blue-700">30 TC</strong>: Carga contractual regular establecida fijamente en 30 horas semanales.
                    </p>
                    <button
                      type="button"
                      onClick={() => setModalHours(30)}
                      className="w-full py-1.5 rounded-lg font-mono font-bold text-xs bg-blue-600 text-white border border-blue-600 shadow-xs flex items-center justify-center gap-1.5"
                    >
                      <Check className="w-3.5 h-3.5" />
                      30 horas semanales (Fijada según normativa)
                    </button>
                  </div>
                )}

                {modalName === 'Exclusiva' && (
                  <div>
                    <p className="text-[11px] text-slate-600 mb-2">
                      Régimen <strong className="text-purple-700">35-36 DE</strong>: Dedicación exclusiva de 35 a 36 horas semanales. Seleccione:
                    </p>
                    <div className="grid grid-cols-2 gap-2">
                      {[35, 36].map(h => (
                        <button
                          type="button"
                          key={h}
                          onClick={() => setModalHours(h)}
                          className={`py-1.5 rounded-lg font-mono font-bold text-xs border transition-colors ${
                            modalHours === h
                              ? 'bg-purple-600 text-white border-purple-600 shadow-xs'
                              : 'bg-white text-slate-700 border-slate-200 hover:bg-purple-50'
                          }`}
                        >
                          {h} horas semanales
                        </button>
                      ))}
                    </div>
                  </div>
                )}

                {/* Validation warning if out of bounds */}
                {!isValidDedicationHours(modalName, modalHours) && (
                  <div className="text-[11px] text-rose-600 bg-rose-50 p-2 rounded-lg border border-rose-200 flex items-center gap-1.5 mt-2">
                    <AlertTriangle className="w-3.5 h-3.5 text-rose-500 shrink-0" />
                    <span>{getDedicationHoursWarning(modalName, modalHours)}</span>
                  </div>
                )}
              </div>

              {/* Cargo directivo */}
              <div>
                <label className="block font-bold text-slate-700 uppercase tracking-wider text-[11px] mb-1">
                  Cargo Directivo o Coordinación Académica
                </label>
                <div className="flex gap-2">
                  <select
                    value={modalDirector}
                    onChange={(e) => setModalDirector(e.target.value)}
                    className="w-full p-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-unerg-blue focus:outline-none"
                  >
                    <option value="">Docente Regular (Sin cargo directivo)</option>
                    <option value="Coordinador">Coordinador(a) Académico(a) / de Línea</option>
                    <option value="Jefe de Departamento">Jefe(a) de Departamento</option>
                    <option value="Director">Director(a) de Escuela / Centro</option>
                    <option value="Decano">Decano(a) de Área</option>
                    <option value="Sub-Director">Sub-Director(a)</option>
                  </select>
                </div>
              </div>

              {/* Student stats */}
              <div className="grid grid-cols-2 gap-3">
                <div>
                  <label className="block font-bold text-slate-700 uppercase tracking-wider text-[11px] mb-1">
                    Estudiantes Tutorados
                  </label>
                  <input
                    type="number"
                    min="0"
                    max="300"
                    value={modalStudentNumber}
                    onChange={(e) => setModalStudentNumber(Number(e.target.value))}
                    className="w-full p-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-mono focus:ring-2 focus:ring-unerg-blue focus:outline-none"
                  />
                </div>
                <div>
                  <label className="block font-bold text-slate-700 uppercase tracking-wider text-[11px] mb-1">
                    Horas Asesoría Estudiantil
                  </label>
                  <input
                    type="number"
                    min="0"
                    max="20"
                    value={modalStudentHours}
                    onChange={(e) => setModalStudentHours(Number(e.target.value))}
                    className="w-full p-2 bg-slate-50 border border-slate-300 rounded-lg text-xs font-mono focus:ring-2 focus:ring-unerg-blue focus:outline-none"
                  />
                </div>
              </div>

              {/* Observaciones */}
              <div>
                <label className="block font-bold text-slate-700 uppercase tracking-wider text-[11px] mb-1">
                  Observaciones / Asignación de Funciones
                </label>
                <textarea
                  rows={2}
                  value={modalInfo}
                  onChange={(e) => setModalInfo(e.target.value)}
                  placeholder="Detalles sobre asignaciones especiales, líneas de investigación, turnos, etc."
                  className="w-full p-2 bg-slate-50 border border-slate-300 rounded-lg text-xs focus:ring-2 focus:ring-unerg-blue focus:outline-none"
                />
              </div>

              {/* Buttons */}
              <div className="flex items-center justify-end gap-2 pt-3 border-t border-slate-200">
                <button
                  type="button"
                  onClick={() => setEditingDedication(null)}
                  className="px-3 py-2 text-xs font-semibold text-slate-600 hover:text-slate-800 bg-slate-100 rounded-lg hover:bg-slate-200 transition-colors"
                >
                  Cancelar
                </button>
                <button
                  type="submit"
                  disabled={!isValidDedicationHours(modalName, modalHours)}
                  className="px-4 py-2 text-xs font-bold text-white bg-unerg-blue rounded-lg hover:bg-blue-700 transition-colors shadow-xs disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-1.5"
                >
                  <CheckCircle2 className="w-4 h-4" />
                  Guardar Carga Horaria
                </button>
              </div>
            </form>
          </div>
        </div>
      )}
    </div>
  );
};
