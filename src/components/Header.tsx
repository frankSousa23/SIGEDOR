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
  User as UserIcon,
  CheckCircle2,
  Menu,
  X
} from 'lucide-react';
import { UserRole } from '../types';

interface HeaderProps {
  onOpenApiDocs: () => void;
  onToggleMobileMenu?: () => void;
  isMobileMenuOpen?: boolean;
}

export const Header: React.FC<HeaderProps> = ({ 
  onOpenApiDocs, 
  onToggleMobileMenu,
  isMobileMenuOpen = false 
}) => {
  const { currentUser, activeRole, setActiveRole, logout } = useAuth();
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
    <header className="no-print sticky top-0 z-40 bg-white border-b border-slate-200 shadow-xs">
      <div className="w-full px-3 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Brand & Mobile Hamburger */}
          <div className="flex items-center gap-2 sm:gap-3">
            {onToggleMobileMenu && (
              <button
                type="button"
                id="mobile-menu-button"
                onClick={onToggleMobileMenu}
                className="md:hidden p-2 -ml-1 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-unerg-blue"
                aria-label="Abrir o cerrar menú lateral"
              >
                {isMobileMenuOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
              </button>
            )}

            <div className="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-lg bg-unerg-blue text-white shadow-xs overflow-hidden p-1 flex-shrink-0">
              <img 
                src="/images/LogoUnerg.png" 
                alt="Logo UNERG" 
                className="w-full h-full object-contain"
                onError={(e) => {
                  (e.currentTarget as HTMLElement).style.display = 'none';
                }}
              />
            </div>
            <div>
              <div className="flex items-center gap-1.5 sm:gap-2">
                <span className="text-base sm:text-lg font-bold text-slate-900 tracking-tight">SIGEDOR</span>
                <span className="text-[10px] sm:text-xs font-semibold px-1.5 sm:px-2 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-300">
                  UNERG v2.0
                </span>
              </div>
              <p className="text-[11px] text-slate-500 hidden md:block">
                Sistema de Gestión Docente y Reportes Oficiales
              </p>
            </div>
          </div>

          {/* Controls & User Profile */}
          <div className="flex items-center gap-2 sm:gap-3">
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

                  <div className="px-4 py-3 space-y-2">
                    <div className="flex items-center justify-between text-xs text-slate-600 bg-slate-50 p-2 rounded-lg border border-slate-200/80">
                      <span className="flex items-center gap-1.5">
                        <CheckCircle2 className="w-3.5 h-3.5 text-emerald-500" />
                        Estado Cuenta
                      </span>
                      <span className="font-semibold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded text-[11px] border border-emerald-200">
                        {currentUser.is_approved && currentUser.is_active ? 'Activa y Verificada' : 'Pendiente'}
                      </span>
                    </div>

                    <div className="pt-1">
                      <button
                        onClick={() => {
                          logout();
                          setShowUserMenu(false);
                        }}
                        className="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg border border-rose-200 transition-colors"
                      >
                        <LogOut className="w-3.5 h-3.5" />
                        Cerrar Sesión / Reautenticar
                      </button>
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
