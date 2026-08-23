import React, { useState } from 'react';
import { useApp } from '../context/AppContext';
import { 
  Building2, 
  UserCheck, 
  ShieldCheck, 
  GraduationCap, 
  RotateCcw, 
  ChevronDown, 
  Bell, 
  FileText,
  User as UserIcon,
  CheckCircle2,
  AlertCircle,
  Menu
} from 'lucide-react';

export interface HeaderProps {
  onToggleSidebar?: () => void;
}

export const Header: React.FC<HeaderProps> = ({ onToggleSidebar }) => {
  const { currentUser, setCurrentUser, users, toast, permissions, setActiveTab, resetToInitialData } = useApp();
  const [showUserMenu, setShowUserMenu] = useState(false);
  const [showNotifications, setShowNotifications] = useState(false);

  const pendingPermissions = permissions.filter(p => p.status === 'pending');

  const getRoleBadge = () => {
    if (currentUser.roles.includes('admin')) {
      return { label: 'Super Administrador', bg: 'bg-red-700 text-white', icon: ShieldCheck };
    }
    if (currentUser.roles.includes('area_manager')) {
      return { label: 'Jefe de Área', bg: 'bg-amber-600 text-white', icon: Building2 };
    }
    return { label: 'Docente Académico', bg: 'bg-sky-700 text-white', icon: GraduationCap };
  };

  const roleInfo = getRoleBadge();
  const RoleIcon = roleInfo.icon;

  return (
    <header className="bg-[#003366] text-white shadow-md border-b-2 border-amber-500 sticky top-0 z-40">
      {/* Toast Alert */}
      {toast && (
        <div className={`fixed top-4 right-4 z-50 flex items-center gap-3 px-4 py-3 rounded-lg shadow-xl text-white font-medium text-sm transition-all duration-300 ${
          toast.type === 'error' ? 'bg-red-600' : toast.type === 'info' ? 'bg-sky-600' : 'bg-emerald-600'
        }`}>
          {toast.type === 'error' ? <AlertCircle className="w-5 h-5 flex-shrink-0" /> : <CheckCircle2 className="w-5 h-5 flex-shrink-0" />}
          <span>{toast.message}</span>
        </div>
      )}

      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div className="flex items-center justify-between h-16">
          {/* Logo & Branding */}
          <div className="flex items-center space-x-3">
            {onToggleSidebar && (
              <button
                onClick={onToggleSidebar}
                className="lg:hidden p-2 -ml-2 rounded-lg text-slate-200 hover:text-white hover:bg-white/10"
                title="Menú de Navegación"
              >
                <Menu className="w-6 h-6" />
              </button>
            )}

            <div className="flex items-center space-x-3 cursor-pointer" onClick={() => setActiveTab('dashboard')}>
            <div className="w-10 h-10 rounded-lg bg-white p-1 flex items-center justify-center shadow-inner">
              <img 
                src="/images/LogoUnerg.png" 
                alt="Logo UNERG" 
                className="w-full h-full object-contain"
                onError={(e) => {
                  (e.target as HTMLElement).style.display = 'none';
                }}
              />
            </div>
            <div>
              <div className="flex items-center space-x-2">
                <span className="font-bold text-xl tracking-tight text-white font-serif">SIGEDOR</span>
                <span className="text-[10px] uppercase font-bold tracking-widest bg-amber-500 text-slate-900 px-1.5 py-0.5 rounded">UNERG</span>
              </div>
              <p className="text-xs text-slate-200 hidden sm:block">
                Sistema de Gestión Docente y Reportes
              </p>
            </div>
          </div>
        </div>

          {/* Center Title Badge */}
          <div className="hidden lg:flex items-center text-xs text-slate-200 bg-black/20 px-3 py-1.5 rounded-full border border-white/10">
            <span className="text-amber-400 font-semibold mr-1">Vicerrectorado Académico</span> • San Juan de los Morros
          </div>

          {/* Right Area: Notifications & User Role Switcher */}
          <div className="flex items-center space-x-3">
            {/* Notification Bell */}
            <div className="relative">
              <button 
                onClick={() => setShowNotifications(!showNotifications)}
                className="p-2 rounded-lg hover:bg-white/10 text-slate-200 hover:text-white transition-colors relative"
                title="Notificaciones y Solicitudes"
              >
                <Bell className="w-5 h-5" />
                {pendingPermissions.length > 0 && (
                  <span className="absolute top-1 right-1 w-4 h-4 bg-amber-500 text-slate-950 text-[10px] font-bold rounded-full flex items-center justify-center animate-pulse">
                    {pendingPermissions.length}
                  </span>
                )}
              </button>

              {showNotifications && (
                <div className="absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-slate-200 text-slate-800 p-3 z-50 text-sm">
                  <div className="flex items-center justify-between pb-2 border-b border-slate-100">
                    <span className="font-semibold text-slate-900">Solicitudes Pendientes</span>
                    <span className="text-xs bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full font-medium">
                      {pendingPermissions.length} pendientes
                    </span>
                  </div>
                  <div className="mt-2 max-h-60 overflow-y-auto space-y-2">
                    {pendingPermissions.length === 0 ? (
                      <p className="text-xs text-slate-500 py-3 text-center">No hay permisos pendientes de revisión.</p>
                    ) : (
                      pendingPermissions.map(p => (
                        <div 
                          key={p.id} 
                          onClick={() => {
                            setActiveTab('permissions');
                            setShowNotifications(false);
                          }}
                          className="p-2 rounded bg-slate-50 hover:bg-slate-100 cursor-pointer text-xs border border-slate-100"
                        >
                          <div className="font-semibold text-slate-800">{p.type}</div>
                          <div className="text-slate-500">{p.memo_number} • C.I. {p.teacher_cdi}</div>
                          <div className="text-[11px] text-amber-700 font-medium mt-1">Requiere aprobación</div>
                        </div>
                      ))
                    )}
                  </div>
                </div>
              )}
            </div>

            {/* User Switcher Dropdown */}
            <div className="relative">
              <button
                onClick={() => setShowUserMenu(!showUserMenu)}
                className="flex items-center space-x-2 bg-white/10 hover:bg-white/20 px-3 py-1.5 rounded-lg border border-white/20 text-left transition-all"
              >
                <div className="w-8 h-8 rounded-full bg-white text-[#003366] flex items-center justify-center font-bold text-sm shadow-sm">
                  {currentUser.name.charAt(0)}
                </div>
                <div className="hidden sm:block">
                  <div className="text-xs font-semibold text-white leading-tight truncate max-w-[140px]">{currentUser.name}</div>
                  <div className="text-[11px] text-slate-300 flex items-center space-x-1">
                    <span className={`inline-block px-1.5 py-0.2 rounded text-[10px] font-bold ${roleInfo.bg}`}>
                      {roleInfo.label}
                    </span>
                  </div>
                </div>
                <ChevronDown className="w-4 h-4 text-slate-300" />
              </button>

              {showUserMenu && (
                <div className="absolute right-0 mt-2 w-72 bg-white rounded-xl shadow-2xl border border-slate-200 text-slate-800 p-2 z-50 text-sm">
                  <div className="p-2.5 border-b border-slate-100">
                    <p className="text-xs text-slate-500 font-medium">Conectado como:</p>
                    <p className="text-sm font-bold text-slate-900">{currentUser.name}</p>
                    <p className="text-xs text-slate-500">{currentUser.email}</p>
                    <div className="mt-1 text-[11px] text-slate-600 bg-slate-100 p-1.5 rounded">
                      <strong>Sede:</strong> {currentUser.sede_nombre || 'Sede Central'}<br />
                      <strong>Área:</strong> {currentUser.area_nombre || 'General'}
                    </div>
                  </div>

                  <div className="py-2">
                    <p className="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-2.5 mb-1">
                      Cambiar Cuenta de Demostración
                    </p>
                    <div className="max-h-56 overflow-y-auto space-y-1">
                      {users.filter(u => u.is_active).map(user => {
                        const isSelected = user.id === currentUser.id;
                        const roleLabel = user.roles.includes('admin')
                          ? 'Super Admin'
                          : user.roles.includes('area_manager')
                          ? 'Jefe de Área'
                          : 'Docente';

                        return (
                          <button
                            key={user.id}
                            onClick={() => {
                              setCurrentUser(user);
                              setShowUserMenu(false);
                            }}
                            className={`w-full text-left px-2.5 py-2 rounded-lg text-xs flex items-center justify-between transition-colors ${
                              isSelected ? 'bg-amber-50 text-amber-900 font-semibold border border-amber-200' : 'hover:bg-slate-50 text-slate-700'
                            }`}
                          >
                            <div>
                              <div className="font-medium text-slate-900">{user.name}</div>
                              <div className="text-[11px] text-slate-500">{user.email}</div>
                            </div>
                            <span className="text-[10px] bg-slate-200 text-slate-700 px-1.5 py-0.5 rounded font-mono">
                              {roleLabel}
                            </span>
                          </button>
                        );
                      })}
                    </div>
                  </div>

                  <div className="border-t border-slate-100 pt-2 px-1 flex items-center justify-between">
                    <button
                      onClick={() => {
                        resetToInitialData();
                        setShowUserMenu(false);
                      }}
                      className="text-xs text-slate-500 hover:text-red-600 flex items-center space-x-1 p-1 rounded hover:bg-slate-50 transition-colors"
                      title="Reiniciar datos de demostración"
                    >
                      <RotateCcw className="w-3.5 h-3.5" />
                      <span>Restaurar Datos</span>
                    </button>
                    <button
                      onClick={() => {
                        setActiveTab('users');
                        setShowUserMenu(false);
                      }}
                      className="text-xs text-[#003366] hover:underline font-medium"
                    >
                      Administrar Usuarios
                    </button>
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
