import React, { useState } from 'react';
import { useAuth } from '../context/AuthContext';
import { 
  ShieldCheck, 
  UserCheck, 
  GraduationCap, 
  ChevronDown, 
  Code2, 
  Building2, 
  FileCheck2,
  LogOut,
  Sparkles
} from 'lucide-react';
import { UserRole } from '../types';

interface HeaderProps {
  onOpenApiDocs: () => void;
}

export const Header: React.FC<HeaderProps> = ({ onOpenApiDocs }) => {
  const { currentUser, activeRole, setActiveRole, switchUserById, users } = useAuth();
  const [showUserMenu, setShowUserMenu] = useState(false);

  const getRoleBadge = (role: UserRole) => {
    switch (role) {
      case 'admin':
        return (
          <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-800 border border-red-200">
            <ShieldCheck className="w-3.5 h-3.5" />
            Super Administrador
          </span>
        );
      case 'area_manager':
        return (
          <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
            <Building2 className="w-3.5 h-3.5" />
            Jefe de Área
          </span>
        );
      case 'teacher':
        return (
          <span className="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
            <GraduationCap className="w-3.5 h-3.5" />
            Docente Académico
          </span>
        );
    }
  };

  return (
    <header className="no-print sticky top-0 z-40 bg-white border-b border-slate-200 shadow-sm">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Brand & Logo */}
          <div className="flex items-center gap-3">
            <div className="flex items-center justify-center w-10 h-10 rounded-lg bg-unerg-blue text-white shadow-md overflow-hidden p-1">
              <img 
                src="/images/LogoUnerg.png" 
                alt="Logo UNERG" 
                className="w-full h-full object-contain"
                onError={(e) => {
                  // Fallback to icon if image fails
                  (e.currentTarget as HTMLElement).style.display = 'none';
                }}
              />
            </div>
            <div>
              <div className="flex items-center gap-2">
                <span className="text-lg font-bold text-slate-900 tracking-tight">SIGEDOR</span>
                <span className="text-xs font-semibold px-2 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-300">
                  UNERG v2.0
                </span>
              </div>
              <p className="text-xs text-slate-500 hidden sm:block">
                Sistema de Gestión Docente y Reportes Oficiales
              </p>
            </div>
          </div>

          {/* Quick Demo Role Switcher & Controls */}
          <div className="flex items-center gap-3">
            <button
              id="api-docs-button"
              onClick={onOpenApiDocs}
              className="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-md border border-slate-300 transition-colors"
              title="Explorar API OpenAPI / Swagger"
            >
              <Code2 className="w-3.5 h-3.5 text-indigo-600" />
              <span className="hidden md:inline">API v1 / Swagger</span>
            </button>

            {/* Role / User selector dropdown */}
            <div className="relative">
              <button
                id="user-menu-trigger"
                onClick={() => setShowUserMenu(!showUserMenu)}
                className="flex items-center gap-2 p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 transition-colors"
              >
                <div className="w-8 h-8 rounded-full bg-slate-800 text-white flex items-center justify-center text-xs font-bold">
                  {currentUser.name.split(' ').map(n => n[0]).slice(0, 2).join('')}
                </div>
                <div className="text-left hidden lg:block">
                  <div className="text-xs font-semibold text-slate-800 leading-tight">
                    {currentUser.name}
                  </div>
                  <div className="text-[11px] text-slate-500 truncate max-w-[140px]">
                    {currentUser.area_nombre}
                  </div>
                </div>
                <div className="hidden sm:block">
                  {getRoleBadge(activeRole)}
                </div>
                <ChevronDown className="w-4 h-4 text-slate-400" />
              </button>

              {showUserMenu && (
                <div className="absolute right-0 mt-2 w-80 bg-white rounded-xl shadow-xl border border-slate-200 py-2 z-50 animate-in fade-in slide-in-from-top-2 duration-150">
                  <div className="px-4 py-2 border-b border-slate-100">
                    <div className="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                      Usuario Conectado
                    </div>
                    <div className="font-semibold text-slate-900 text-sm mt-0.5">{currentUser.name}</div>
                    <div className="text-xs text-slate-500">{currentUser.email}</div>
                    <div className="text-xs text-unerg-blue mt-1 font-medium">{currentUser.sede_nombre}</div>

                    {/* If multi-role user, allow toggling active role */}
                    {currentUser.roles.length > 1 && (
                      <div className="mt-3 pt-2 border-t border-slate-100">
                        <span className="text-[11px] font-semibold text-slate-500 uppercase block mb-1.5">
                          Cambiar Rol Activo (Multi-Rol):
                        </span>
                        <div className="flex gap-1.5">
                          {currentUser.roles.map(r => (
                            <button
                              key={r}
                              onClick={() => {
                                setActiveRole(r);
                                setShowUserMenu(false);
                              }}
                              className={`px-2 py-1 text-xs rounded font-medium transition-all ${
                                activeRole === r
                                  ? 'bg-unerg-blue text-white shadow-sm'
                                  : 'bg-slate-100 text-slate-700 hover:bg-slate-200'
                              }`}
                            >
                              {r === 'admin' ? 'Admin' : r === 'area_manager' ? 'Jefe Área' : 'Docente'}
                            </button>
                          ))}
                        </div>
                      </div>
                    )}
                  </div>

                  <div className="px-4 py-2">
                    <div className="text-[11px] font-semibold text-slate-500 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                      <Sparkles className="w-3 h-3 text-amber-500" />
                      Probar Cuentas Demostrativas:
                    </div>
                    <div className="space-y-1">
                      {users.slice(0, 4).map(u => (
                        <button
                          key={u.id}
                          onClick={() => {
                            switchUserById(u.id);
                            setShowUserMenu(false);
                          }}
                          className={`w-full text-left px-2.5 py-1.5 rounded-md text-xs flex items-center justify-between transition-colors ${
                            currentUser.id === u.id
                              ? 'bg-blue-50 text-unerg-blue font-semibold border border-blue-200'
                              : 'hover:bg-slate-50 text-slate-700'
                          }`}
                        >
                          <div className="truncate pr-2">
                            <div>{u.name}</div>
                            <div className="text-[10px] text-slate-400 truncate">{u.email}</div>
                          </div>
                          <span className="text-[10px] uppercase font-bold px-1.5 py-0.5 rounded bg-slate-100 text-slate-600">
                            {u.roles.join('+')}
                          </span>
                        </button>
                      ))}
                    </div>
                  </div>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>
    </header>
  );
};
