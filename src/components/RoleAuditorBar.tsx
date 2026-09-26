import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { useData } from '../context/DataContext';
import { 
  ShieldCheck, 
  Building2, 
  GraduationCap, 
  Eye, 
  RotateCcw, 
  ChevronDown, 
  ChevronUp, 
  CheckCircle2, 
  Sparkles,
  Info,
  Users
} from 'lucide-react';
import { User, UserRole } from '../types';

export const RoleAuditorBar: React.FC = () => {
  const { currentUser, activeRole, isSimulating, simulateUser, exitSimulation, users, isSuperAdmin } = useAuth();
  const { filteredTeachers } = useData();
  const [isExpanded, setIsExpanded] = useState(false);

  // Group preset test personas for quick auditing
  const adminUser = users.find(u => u.roles.includes('admin')) || users[0];
  const areaManagers = users.filter(u => u.roles.includes('area_manager'));
  const teachers = users.filter(u => u.roles.includes('teacher') && !u.roles.includes('admin'));

  return (
    <div className="no-print bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white border-b border-indigo-800/40 shadow-sm transition-all">
      <div className="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 py-2">
        {/* Top active simulation or toggle strip */}
        <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
          <div className="flex items-center gap-2 text-xs flex-wrap">
            <span className="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full font-bold text-[10px] uppercase tracking-wider bg-indigo-500/20 text-indigo-300 border border-indigo-400/30">
              <Eye className="w-3 h-3 text-indigo-300 animate-pulse" />
              Auditoría Multi-Rol
            </span>

            {isSimulating ? (
              <span className="flex items-center gap-1.5 text-xs text-amber-300 font-medium">
                <span>Simulando perfil:</span>
                <strong className="text-white bg-amber-500/20 px-2 py-0.5 rounded border border-amber-400/30 flex items-center gap-1">
                  {activeRole === 'area_manager' ? <Building2 className="w-3 h-3 text-amber-300" /> : <GraduationCap className="w-3 h-3 text-emerald-300" />}
                  {currentUser.name} ({activeRole === 'area_manager' ? 'Jefe de Área' : 'Docente'})
                </strong>
                <span className="text-slate-300 text-[11px] hidden md:inline">
                  • {currentUser.area_nombre} ({filteredTeachers.length} docentes visibles en este ámbito)
                </span>
              </span>
            ) : (
              <span className="text-xs text-slate-300 flex items-center gap-1">
                <span>Vista activa:</span>
                <strong className="text-emerald-400 flex items-center gap-1 bg-emerald-500/10 px-2 py-0.5 rounded border border-emerald-500/20">
                  <ShieldCheck className="w-3 h-3 text-emerald-400" /> Super Administrador
                </strong>
                <span className="text-slate-400 hidden lg:inline">• Acceso irrestricto a todas las sedes y facultades ({filteredTeachers.length} docentes)</span>
              </span>
            )}
          </div>

          {/* Action buttons */}
          <div className="flex items-center gap-2">
            {isSimulating && (
              <button
                onClick={exitSimulation}
                className="px-2.5 py-1 text-xs font-bold text-white bg-amber-600 hover:bg-amber-500 rounded-lg shadow-xs flex items-center gap-1 transition-colors"
                title="Volver a los permisos totales de Super Administrador"
              >
                <RotateCcw className="w-3 h-3" />
                <span>Restablecer Super Admin</span>
              </button>
            )}

            <button
              onClick={() => setIsExpanded(!isExpanded)}
              className="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-slate-300 hover:text-white bg-white/10 hover:bg-white/15 rounded-lg border border-white/10 transition-colors"
            >
              <span>{isExpanded ? 'Ocultar roles' : 'Cambiar rol / facultad'}</span>
              {isExpanded ? <ChevronUp className="w-3 h-3" /> : <ChevronDown className="w-3 h-3" />}
            </button>
          </div>
        </div>

        {/* Expandable Persona Selector Drawer */}
        {isExpanded && (
          <div className="mt-3 pt-3 border-t border-indigo-800/40 space-y-3 text-xs animate-fadeIn">
            <p className="text-[11px] text-slate-300 leading-relaxed">
              Selecciona cualquier perfil para comprobar en tiempo real cómo cambia la interfaz, qué botones de acción se activan o restringen (RBAC) y cómo se segmentan los expedientes según el área académica:
            </p>

            <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
              {/* Card 1: Super Admin */}
              <div 
                onClick={() => {
                  exitSimulation();
                  setIsExpanded(false);
                }}
                className={`p-2.5 rounded-xl border cursor-pointer transition-all ${
                  !isSimulating && activeRole === 'admin'
                    ? 'bg-indigo-900/70 border-indigo-400 ring-2 ring-indigo-400/40 shadow-xs'
                    : 'bg-slate-800/60 border-slate-700/60 hover:bg-slate-800 hover:border-slate-500'
                }`}
              >
                <div className="flex items-center justify-between text-xs">
                  <span className="font-bold text-red-300 flex items-center gap-1.5">
                    <ShieldCheck className="w-3.5 h-3.5 text-red-400" />
                    Super Administrador
                  </span>
                  {!isSimulating && <span className="text-[10px] bg-indigo-500/40 text-indigo-200 px-1.5 py-0.2 rounded font-bold">Activo</span>}
                </div>
                <div className="text-white font-semibold text-xs mt-1">Administrador General</div>
                <div className="text-[10px] text-slate-300 mt-0.5">Control institucional de las 7 sedes, 9 áreas y gestión de usuarios RBAC.</div>
              </div>

              {/* Area Managers */}
              {areaManagers.slice(0, 4).map(mgr => {
                const isSelected = isSimulating && currentUser.id === mgr.id && activeRole === 'area_manager';
                return (
                  <div
                    key={mgr.id}
                    onClick={() => {
                      simulateUser(mgr, 'area_manager');
                      setIsExpanded(false);
                    }}
                    className={`p-2.5 rounded-xl border cursor-pointer transition-all ${
                      isSelected
                        ? 'bg-blue-900/70 border-blue-400 ring-2 ring-blue-400/40 shadow-xs'
                        : 'bg-slate-800/60 border-slate-700/60 hover:bg-slate-800 hover:border-blue-500/50'
                    }`}
                  >
                    <div className="flex items-center justify-between text-xs">
                      <span className="font-bold text-blue-300 flex items-center gap-1.5">
                        <Building2 className="w-3.5 h-3.5 text-blue-400" />
                        Jefe de Área
                      </span>
                      {isSelected && <span className="text-[10px] bg-blue-500/40 text-blue-200 px-1.5 py-0.2 rounded font-bold">Activo</span>}
                    </div>
                    <div className="text-white font-semibold text-xs mt-1 truncate">{mgr.name}</div>
                    <div className="text-[10px] text-slate-300 mt-0.5 truncate">{mgr.area_nombre}</div>
                    <div className="text-[10px] text-slate-400 truncate">{mgr.sede_nombre.split('/')[0]}</div>
                  </div>
                );
              })}

              {/* Teacher Persona */}
              {teachers.slice(0, 2).map(tch => {
                const isSelected = isSimulating && currentUser.id === tch.id && activeRole === 'teacher';
                return (
                  <div
                    key={tch.id}
                    onClick={() => {
                      simulateUser(tch, 'teacher');
                      setIsExpanded(false);
                    }}
                    className={`p-2.5 rounded-xl border cursor-pointer transition-all ${
                      isSelected
                        ? 'bg-emerald-900/70 border-emerald-400 ring-2 ring-emerald-400/40 shadow-xs'
                        : 'bg-slate-800/60 border-slate-700/60 hover:bg-slate-800 hover:border-emerald-500/50'
                    }`}
                  >
                    <div className="flex items-center justify-between text-xs">
                      <span className="font-bold text-emerald-300 flex items-center gap-1.5">
                        <GraduationCap className="w-3.5 h-3.5 text-emerald-400" />
                        Docente Ordinario
                      </span>
                      {isSelected && <span className="text-[10px] bg-emerald-500/40 text-emerald-200 px-1.5 py-0.2 rounded font-bold">Activo</span>}
                    </div>
                    <div className="text-white font-semibold text-xs mt-1 truncate">{tch.name}</div>
                    <div className="text-[10px] text-slate-300 mt-0.5 truncate">Portal individual (Expediente, Cátedras, Permisos)</div>
                  </div>
                );
              })}
            </div>
          </div>
        )}
      </div>
    </div>
  );
};
