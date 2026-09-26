import React, { useState, useRef } from 'react';
import { useData } from '../context/DataContext';
import { 
  Database, 
  Trash2, 
  RotateCcw, 
  Download, 
  Upload, 
  CheckCircle2, 
  AlertTriangle, 
  X, 
  Terminal, 
  FileJson,
  Layers,
  GraduationCap,
  ShieldCheck,
  Server
} from 'lucide-react';

interface DataManagementModalProps {
  isOpen: boolean;
  onClose: () => void;
}

export const DataManagementModal: React.FC<DataManagementModalProps> = ({ isOpen, onClose }) => {
  const { 
    teachers, 
    categories, 
    dedications, 
    sites, 
    permissions, 
    reports, 
    isDatabaseEmpty, 
    isSampleData,
    clearAllTestData, 
    restoreSampleData, 
    exportDatabaseBackup, 
    importDatabaseBackup 
  } = useData();

  const [confirmClear, setConfirmClear] = useState(false);
  const [notification, setNotification] = useState<{ type: 'success' | 'error'; message: string } | null>(null);
  const [activeTab, setActiveTab] = useState<'manage' | 'guide'>('manage');
  const fileInputRef = useRef<HTMLInputElement>(null);

  if (!isOpen) return null;

  const handleClear = () => {
    clearAllTestData();
    setConfirmClear(false);
    setNotification({
      type: 'success',
      message: 'Base de datos limpiada con éxito. El sistema está en blanco y listo para comenzar la integración oficial con información real.',
    });
    setTimeout(() => setNotification(null), 5000);
  };

  const handleRestore = () => {
    restoreSampleData();
    setNotification({
      type: 'success',
      message: 'Datos de prueba UNERG restaurados correctamente con más de 30 expedientes docentes interconectados.',
    });
    setTimeout(() => setNotification(null), 5000);
  };

  const handleFileChange = (e: React.ChangeEvent<HTMLInputElement>) => {
    const file = e.target.files?.[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = (event) => {
      const content = event.target?.result as string;
      const success = importDatabaseBackup(content);
      if (success) {
        setNotification({
          type: 'success',
          message: 'Respaldo JSON importado exitosamente. Toda la información ha sido actualizada.',
        });
      } else {
        setNotification({
          type: 'error',
          message: 'Error al importar: el formato del archivo JSON no es válido para SIGEDOR.',
        });
      }
      setTimeout(() => setNotification(null), 5000);
    };
    reader.readAsText(file);
    if (fileInputRef.current) fileInputRef.current.value = '';
  };

  return (
    <div className="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4">
      <div className="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-2xl overflow-hidden animate-in fade-in zoom-in-95">
        {/* Header */}
        <div className="bg-gradient-to-r from-slate-900 via-unerg-blue-dark to-unerg-blue text-white p-5 flex items-center justify-between">
          <div className="flex items-center gap-3">
            <div className="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center border border-white/20">
              <Database className="w-5 h-5 text-amber-400" />
            </div>
            <div>
              <h3 className="font-bold text-base leading-tight">Control de Base de Datos y Despliegue</h3>
              <p className="text-xs text-slate-300 mt-0.5">
                Gestione datos de prueba, integración limpia o respaldos JSON del sistema
              </p>
            </div>
          </div>
          <button 
            onClick={onClose}
            className="p-1 text-slate-300 hover:text-white rounded-lg hover:bg-white/10 transition-colors"
          >
            <X className="w-5 h-5" />
          </button>
        </div>

        {/* Tab Navigation */}
        <div className="flex border-b border-slate-200 bg-slate-50 px-5 pt-3 gap-2">
          <button
            onClick={() => setActiveTab('manage')}
            className={`pb-2.5 px-3 text-xs font-bold border-b-2 flex items-center gap-1.5 transition-colors ${
              activeTab === 'manage'
                ? 'border-unerg-blue text-unerg-blue'
                : 'border-transparent text-slate-500 hover:text-slate-800'
            }`}
          >
            <Layers className="w-4 h-4" />
            Gestión en Memoria / Local
          </button>
          <button
            onClick={() => setActiveTab('guide')}
            className={`pb-2.5 px-3 text-xs font-bold border-b-2 flex items-center gap-1.5 transition-colors ${
              activeTab === 'guide'
                ? 'border-unerg-blue text-unerg-blue'
                : 'border-transparent text-slate-500 hover:text-slate-800'
            }`}
          >
            <Server className="w-4 h-4" />
            Instrucciones de Despliegue
          </button>
        </div>

        {/* Notification Toast */}
        {notification && (
          <div className={`mx-5 mt-4 p-3 rounded-xl border flex items-start gap-2 text-xs animate-in fade-in ${
            notification.type === 'success' 
              ? 'bg-emerald-50 border-emerald-200 text-emerald-900' 
              : 'bg-red-50 border-red-200 text-red-900'
          }`}>
            <CheckCircle2 className="w-4 h-4 text-emerald-600 flex-shrink-0 mt-0.5" />
            <p className="font-medium flex-1">{notification.message}</p>
          </div>
        )}

        <div className="p-5 max-h-[75vh] overflow-y-auto space-y-5">
          {activeTab === 'manage' ? (
            <>
              {/* Status Overview Card */}
              <div className="bg-slate-50 rounded-xl p-4 border border-slate-200">
                <div className="flex items-center justify-between mb-3">
                  <span className="text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Estado Actual del Almacén
                  </span>
                  {isDatabaseEmpty ? (
                    <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
                      <span className="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                      Base de Datos Limpia (0 expedientes)
                    </span>
                  ) : isSampleData ? (
                    <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-900 border border-blue-300">
                      <span className="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                      Datos de Prueba UNERG Activos ({teachers.length} docentes)
                    </span>
                  ) : (
                    <span className="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-300">
                      <span className="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                      Datos Personalizados ({teachers.length} docentes)
                    </span>
                  )}
                </div>

                <div className="grid grid-cols-2 sm:grid-cols-4 gap-2 text-center text-xs">
                  <div className="bg-white p-2.5 rounded-lg border border-slate-200 shadow-xs">
                    <span className="text-slate-400 block text-[11px]">Docentes</span>
                    <strong className="text-lg font-bold text-slate-800">{teachers.length}</strong>
                  </div>
                  <div className="bg-white p-2.5 rounded-lg border border-slate-200 shadow-xs">
                    <span className="text-slate-400 block text-[11px]">Escalafón</span>
                    <strong className="text-lg font-bold text-slate-800">{categories.length}</strong>
                  </div>
                  <div className="bg-white p-2.5 rounded-lg border border-slate-200 shadow-xs">
                    <span className="text-slate-400 block text-[11px]">Permisos</span>
                    <strong className="text-lg font-bold text-slate-800">{permissions.length}</strong>
                  </div>
                  <div className="bg-white p-2.5 rounded-lg border border-slate-200 shadow-xs">
                    <span className="text-slate-400 block text-[11px]">Reportes</span>
                    <strong className="text-lg font-bold text-slate-800">{reports.length}</strong>
                  </div>
                </div>
              </div>

              {/* Action 1: Clear Data to start fresh */}
              <div className="border border-slate-200 rounded-xl p-4 space-y-3 bg-white hover:border-slate-300 transition-colors">
                <div className="flex items-start justify-between gap-3">
                  <div>
                    <h4 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                      <Trash2 className="w-4 h-4 text-red-600" />
                      Limpiar Datos de Prueba (Empezar desde Cero)
                    </h4>
                    <p className="text-xs text-slate-600 mt-1 leading-relaxed">
                      Elimina todos los expedientes de demostración, asignaciones de sedes, licencias y memorandos de prueba. Conserva la cuenta de Administrador General para que pueda comenzar a registrar la información real de su institución.
                    </p>
                  </div>
                </div>

                {!confirmClear ? (
                  <button
                    onClick={() => setConfirmClear(true)}
                    className="px-4 py-2 text-xs font-bold text-red-700 bg-red-50 hover:bg-red-100 border border-red-200 rounded-lg transition-colors flex items-center gap-1.5"
                  >
                    <Trash2 className="w-4 h-4" />
                    Limpiar todo para integración
                  </button>
                ) : (
                  <div className="p-3 bg-red-50 border border-red-300 rounded-xl space-y-2">
                    <div className="flex items-center gap-2 text-xs font-bold text-red-900">
                      <AlertTriangle className="w-4 h-4 text-red-600 flex-shrink-0" />
                      ¿Confirmar limpieza total de datos de prueba?
                    </div>
                    <p className="text-[11px] text-red-700">
                      Esta acción vaciará los expedientes docentes. Podrá restaurar los datos de prueba en cualquier momento con el botón inferior.
                    </p>
                    <div className="flex items-center gap-2 pt-1">
                      <button
                        onClick={handleClear}
                        className="px-3 py-1.5 text-xs font-bold text-white bg-red-600 hover:bg-red-700 rounded-lg transition-colors"
                      >
                        Sí, limpiar y empezar de cero
                      </button>
                      <button
                        onClick={() => setConfirmClear(false)}
                        className="px-3 py-1.5 text-xs font-semibold text-slate-700 hover:bg-slate-200 rounded-lg transition-colors"
                      >
                        Cancelar
                      </button>
                    </div>
                  </div>
                )}
              </div>

              {/* Action 2: Restore sample data */}
              <div className="border border-slate-200 rounded-xl p-4 space-y-3 bg-white hover:border-slate-300 transition-colors">
                <div className="flex items-start justify-between gap-3">
                  <div>
                    <h4 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                      <RotateCcw className="w-4 h-4 text-blue-600" />
                      Restaurar Datos de Demostración UNERG
                    </h4>
                    <p className="text-xs text-slate-600 mt-1 leading-relaxed">
                      Carga el conjunto completo de 10 expedientes docentes con diferentes categorías de escalafón (Instructor a Titular), dedicaciones y sedes para evaluar el funcionamiento del sistema.
                    </p>
                  </div>
                </div>

                <button
                  onClick={handleRestore}
                  className="px-4 py-2 text-xs font-bold text-blue-700 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-lg transition-colors flex items-center gap-1.5"
                >
                  <RotateCcw className="w-4 h-4" />
                  Restaurar muestra institucional
                </button>
              </div>

              {/* Action 3 & 4: Backup Export & Import */}
              <div className="border border-slate-200 rounded-xl p-4 space-y-3 bg-white">
                <div>
                  <h4 className="text-sm font-bold text-slate-900 flex items-center gap-2">
                    <FileJson className="w-4 h-4 text-indigo-600" />
                    Respaldo y Migración de Datos (JSON)
                  </h4>
                  <p className="text-xs text-slate-600 mt-1 leading-relaxed">
                    Descargue una copia de seguridad con todos los registros actuales o cargue un archivo JSON institucional para transferir datos.
                  </p>
                </div>

                <div className="flex items-center gap-3 flex-wrap">
                  <button
                    onClick={exportDatabaseBackup}
                    className="px-4 py-2 text-xs font-bold text-slate-800 bg-slate-100 hover:bg-slate-200 border border-slate-300 rounded-lg transition-colors flex items-center gap-1.5"
                  >
                    <Download className="w-4 h-4 text-slate-600" />
                    Exportar Respaldo JSON
                  </button>

                  <input 
                    type="file" 
                    ref={fileInputRef} 
                    onChange={handleFileChange} 
                    accept=".json" 
                    className="hidden" 
                  />
                  <button
                    onClick={() => fileInputRef.current?.click()}
                    className="px-4 py-2 text-xs font-bold text-indigo-700 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg transition-colors flex items-center gap-1.5"
                  >
                    <Upload className="w-4 h-4" />
                    Importar Respaldo JSON
                  </button>
                </div>
              </div>
            </>
          ) : (
            /* Guide Tab for Deployment */
            <div className="space-y-4 text-xs">
              <div className="bg-slate-900 text-slate-100 p-4 rounded-xl font-mono text-[11px] leading-relaxed space-y-3">
                <div className="text-amber-400 font-bold flex items-center gap-1.5 text-xs">
                  <Terminal className="w-4 h-4" />
                  Guía Rápida de Despliegue en Servidor
                </div>

                <div>
                  <p className="text-slate-400 mb-1"># 1. Clonar el repositorio e instalar dependencias:</p>
                  <p className="text-emerald-400">git clone https://github.com/tu-usuario/sigedor-unerg.git</p>
                  <p className="text-emerald-400">composer install && npm install</p>
                </div>

                <div>
                  <p className="text-slate-400 mb-1"># 2. Configurar variables de entorno y clave de aplicación:</p>
                  <p className="text-emerald-400">cp .env.example .env</p>
                  <p className="text-emerald-400">php artisan key:generate</p>
                </div>

                <div>
                  <p className="text-slate-400 mb-1"># 3A. Para desplegar con datos de prueba (evaluación):</p>
                  <p className="text-blue-300">php artisan migrate --seed</p>
                </div>

                <div>
                  <p className="text-slate-400 mb-1"># 3B. Para desplegar completamente limpio (producción de cero):</p>
                  <p className="text-amber-300">php artisan migrate:fresh</p>
                  <p className="text-amber-300">php artisan make:filament-user</p>
                </div>

                <div>
                  <p className="text-slate-400 mb-1"># 4. Compilar activos y servir:</p>
                  <p className="text-emerald-400">npm run build</p>
                  <p className="text-emerald-400">php artisan serve</p>
                </div>
              </div>

              <div className="bg-blue-50 border border-blue-200 rounded-xl p-3.5 space-y-1.5 text-blue-900">
                <div className="font-bold flex items-center gap-1.5">
                  <ShieldCheck className="w-4 h-4 text-unerg-blue" />
                  Modo de Integración en el Frontend
                </div>
                <p className="text-blue-800 leading-relaxed text-[11px]">
                  En este entorno web interactivo, cualquier cambio que realice (agregar docentes, ascensos, licencias o limpieza) se guarda automáticamente en el almacenamiento local de su navegador (<code>localStorage</code>), lo que le permite simular fielmente el comportamiento de producción sin perder sus datos al recargar la página.
                </p>
              </div>
            </div>
          )}
        </div>

        {/* Footer */}
        <div className="bg-slate-50 border-t border-slate-200 p-4 flex justify-end">
          <button
            onClick={onClose}
            className="px-4 py-2 text-xs font-semibold text-slate-700 hover:bg-slate-200 rounded-lg transition-colors"
          >
            Cerrar
          </button>
        </div>
      </div>
    </div>
  );
};
