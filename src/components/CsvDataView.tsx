import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import {
  FileSpreadsheet,
  Upload,
  Download,
  CheckCircle2,
  AlertCircle,
  FileText,
  Copy,
  Layers,
  HelpCircle
} from 'lucide-react';

export const CsvDataView: React.FC = () => {
  const { importCsv, exportCsv, teachers, categories, dedications, sites, users } = useApp();

  const [activeImportType, setActiveImportType] = useState<'teachers' | 'categories' | 'dedications' | 'sites' | 'users'>('teachers');
  const [csvInput, setCsvInput] = useState('');
  const [importResult, setImportResult] = useState<{ success: boolean; count: number; error?: string } | null>(null);

  // Sample CSV Templates matching database/seeders/data/
  const samples = {
    teachers: `id;name;surName;cdi;genre;phone;email;birthDate;datePromotion;asignaturePromotion;user_email;sede_nombre;area_nombre;programa_nombre
101;Manuel Alejandro;Rojas Silva;10101999;M;+58 412 9999999;manuel.rojas@unerg.edu.ve;1980-05-12;2012-09-15;Inteligencia Artificial;manuel.rojas@unerg.edu.ve;Sede Central/San Juan de los Morros;Ingeniería de sistemas;Ingeniería en Informática
102;Ana María;Gutiérrez Páez;10102000;F;+58 414 8888888;ana.gutierrez@unerg.edu.ve;1985-11-20;2016-04-10;Pediatría Médica;ana.gutierrez@unerg.edu.ve;Sede Central/San Juan de los Morros;Ciencias de la salud;Medicina`,

    categories: `id;teacher_cdi;preTitle;lastTitle;current_category;instructor;asistente;agregado;asociado;titular;disable_assistant_rule;info
201;10101999;Ingeniero en Computación;Doctor en Ciencias Técnicas;Titular;2012-09-15;2015-09-15;2018-09-15;2021-09-15;2024-09-15;0;Acreditación por mérito científico
202;10102000;Médico Cirujano;Especialista en Pediatría;Agregado;2016-04-10;2019-04-10;2023-05-10;;;0;Docente asistencial en hospital universitario`,

    dedications: `id;teacher_cdi;name;hours;director;studentNumber;studentHours;info
301;10101999;Exclusiva;36;Director;40;8;Director del Centro de Inteligencia Computacional
302;10102000;Tiempo Completo;30;;35;6;Docente regular de clínica médica pediátrica`,

    sites: `id;teacher_cdi;sede_nombre;area_nombre;programa_nombre;uc;weekHours;sections;info;is_active
401;10101999;Sede Central/San Juan de los Morros;Ingeniería de sistemas;Ingeniería en Informática;4;6;2;Cátedra de Aprendizaje Automático;1
402;10102000;Sede Central/San Juan de los Morros;Ciencias de la salud;Medicina;5;8;2;Cátedra de Clínica Pediátrica I;1`,

    users: `id;name;email;sede_nombre;area_nombre;rol_name;is_active;is_approved
501;Prof. Manuel Alejandro Rojas;manuel.rojas@unerg.edu.ve;Sede Central/San Juan de los Morros;Ingeniería de sistemas;teacher;1;1
502;Dra. Ana María Gutiérrez;ana.gutierrez@unerg.edu.ve;Sede Central/San Juan de los Morros;Ciencias de la salud;teacher;1;1`
  };

  const handleLoadSample = () => {
    setCsvInput(samples[activeImportType]);
    setImportResult(null);
  };

  const handleFileUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (evt) => {
      const text = evt.target?.result as string;
      setCsvInput(text);
      setImportResult(null);
    };
    reader.readAsText(file);
  };

  const handleExecuteImport = () => {
    if (!csvInput.trim()) {
      setImportResult({ success: false, count: 0, error: 'Por favor pegue texto CSV o cargue un archivo.' });
      return;
    }
    const result = importCsv(activeImportType, csvInput);
    setImportResult(result);
  };

  return (
    <div className="space-y-6">
      {/* Header */}
      <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 className="text-xl font-bold text-slate-900 font-serif">Ingesta y Exportación Masiva CSV</h1>
          <p className="text-xs text-slate-500">
            Pipeline de carga de datos tabulares compatible con las estructuras de base de datos de SIGEDOR / UNERG
          </p>
        </div>
      </div>

      {/* Export Datasets Bar */}
      <div className="bg-white p-5 rounded-xl border border-slate-200 shadow-sm space-y-3">
        <h3 className="font-bold text-slate-900 text-sm flex items-center space-x-2">
          <Download className="w-4 h-4 text-emerald-600" />
          <span>Exportación de Colecciones Actuales (.CSV)</span>
        </h3>
        <p className="text-xs text-slate-500">
          Descargue copias de seguridad de la base de datos en formato delimitado por punto y coma (;) para respaldo o auditoría.
        </p>

        <div className="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 pt-2">
          {[
            { type: 'teachers' as const, label: 'Docentes', count: teachers.length, color: 'hover:border-blue-500 text-blue-700' },
            { type: 'categories' as const, label: 'Escalafón', count: categories.length, color: 'hover:border-amber-500 text-amber-700' },
            { type: 'dedications' as const, label: 'Dedicación', count: dedications.length, color: 'hover:border-emerald-500 text-emerald-700' },
            { type: 'sites' as const, label: 'Sedes y Cátedras', count: sites.length, color: 'hover:border-indigo-500 text-indigo-700' },
            { type: 'users' as const, label: 'Usuarios', count: users.length, color: 'hover:border-purple-500 text-purple-700' },
          ].map(item => (
            <button
              key={item.type}
              onClick={() => exportCsv(item.type)}
              className={`p-3 bg-slate-50 border border-slate-200 rounded-lg text-left transition-all hover:bg-white hover:shadow-sm ${item.color}`}
            >
              <div className="font-bold text-xs">{item.label}</div>
              <div className="text-[11px] text-slate-500 mt-1 flex items-center justify-between">
                <span>{item.count} registros</span>
                <Download className="w-3.5 h-3.5" />
              </div>
            </button>
          ))}
        </div>
      </div>

      {/* Import Section */}
      <div className="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div className="bg-[#003366] text-white p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-amber-500">
          <div className="flex items-center space-x-2">
            <Upload className="w-5 h-5 text-amber-400" />
            <div>
              <h3 className="font-bold text-sm">Importador de Datos CSV</h3>
              <p className="text-xs text-slate-200">Seleccione la entidad e ingrese las filas correspondientes</p>
            </div>
          </div>

          {/* Type Selector Pills */}
          <div className="flex bg-black/20 p-1 rounded-lg text-xs font-semibold overflow-x-auto">
            {(['teachers', 'categories', 'dedications', 'sites', 'users'] as const).map(t => (
              <button
                key={t}
                onClick={() => {
                  setActiveImportType(t);
                  setImportResult(null);
                }}
                className={`px-3 py-1 rounded transition-colors uppercase text-[10px] tracking-wider whitespace-nowrap ${
                  activeImportType === t ? 'bg-amber-500 text-slate-950 font-bold' : 'text-slate-200 hover:text-white'
                }`}
              >
                {t === 'teachers' ? 'Docentes' :
                 t === 'categories' ? 'Escalafón' :
                 t === 'dedications' ? 'Dedicación' :
                 t === 'sites' ? 'Sedes/Cátedras' : 'Usuarios'}
              </button>
            ))}
          </div>
        </div>

        <div className="p-6 space-y-4">
          {/* File Selector & Sample Helper */}
          <div className="flex flex-wrap items-center justify-between gap-3 pb-3 border-b border-slate-100">
            <div className="flex items-center space-x-3">
              <label className="bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-semibold px-3 py-1.5 rounded-lg border border-slate-300 cursor-pointer flex items-center space-x-1.5 transition-colors">
                <Upload className="w-3.5 h-3.5 text-slate-600" />
                <span>Seleccionar Archivo .CSV</span>
                <input
                  type="file"
                  accept=".csv,.txt"
                  onChange={handleFileUpload}
                  className="hidden"
                />
              </label>
              <span className="text-xs text-slate-500 hidden sm:inline">o pegue el texto delimitado abajo:</span>
            </div>

            <button
              onClick={handleLoadSample}
              className="text-xs text-[#003366] hover:underline font-semibold flex items-center space-x-1"
            >
              <Copy className="w-3.5 h-3.5" />
              <span>Cargar Ejemplo de Prueba ({activeImportType})</span>
            </button>
          </div>

          {/* Text Area */}
          <div>
            <textarea
              rows={8}
              value={csvInput}
              onChange={(e) => setCsvInput(e.target.value)}
              className="w-full p-3 font-mono text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-[#003366] focus:outline-none bg-slate-50 text-slate-800 leading-relaxed"
              placeholder="id;name;surName;cdi;genre;phone;email..."
            />
          </div>

          {/* Result Alert */}
          {importResult && (
            <div className={`p-4 rounded-lg flex items-center gap-3 text-xs ${
              importResult.success ? 'bg-emerald-50 text-emerald-900 border border-emerald-200' : 'bg-red-50 text-red-900 border border-red-200'
            }`}>
              {importResult.success ? (
                <>
                  <CheckCircle2 className="w-5 h-5 text-emerald-600 flex-shrink-0" />
                  <div>
                    <span className="font-bold block">¡Importación Exitosa!</span>
                    <span>Se han procesado e incorporado <strong>{importResult.count}</strong> registros de {activeImportType} a la base de datos.</span>
                  </div>
                </>
              ) : (
                <>
                  <AlertCircle className="w-5 h-5 text-red-600 flex-shrink-0" />
                  <div>
                    <span className="font-bold block">Error al procesar el archivo CSV</span>
                    <span>{importResult.error || 'Verifique el formato de los encabezados y separadores.'}</span>
                  </div>
                </>
              )}
            </div>
          )}

          {/* Action Button */}
          <div className="flex justify-end pt-2">
            <button
              onClick={handleExecuteImport}
              className="bg-[#003366] hover:bg-[#002244] text-white font-bold text-xs px-6 py-2.5 rounded-lg transition-colors shadow flex items-center space-x-2"
            >
              <Upload className="w-4 h-4 text-amber-400" />
              <span>Ejecutar Importación Masiva</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  );
};
