import React from 'react';
import { useApp } from '../context/AppContext';
import {
  LayoutDashboard,
  Users,
  Award,
  Clock,
  Building2,
  FileCheck2,
  FileText,
  FileSpreadsheet,
  ShieldAlert,
  History,
  GraduationCap
} from 'lucide-react';

export interface SidebarProps {
  isOpen?: boolean;
  onClose?: () => void;
}

export const Sidebar: React.FC<SidebarProps> = ({ isOpen, onClose }) => {
  const { activeTab, setActiveTab, teachers, permissions, reports, currentUser } = useApp();

  const handleNavClick = (tabId: string) => {
    setActiveTab(tabId);
    if (onClose) onClose();
  };

  const pendingPermissionsCount = permissions.filter(p => p.status === 'pending').length;
  const isAdmin = currentUser.roles.includes('admin');

  const navItems = [
    {
      group: 'PANEL PRINCIPAL',
      items: [
        { id: 'dashboard', label: 'Escritorio', icon: LayoutDashboard },
      ],
    },
    {
      group: 'GESTIÓN DOCENTE',
      items: [
        { id: 'teachers', label: 'Docentes (Expedientes)', icon: GraduationCap, badge: teachers.length },
        { id: 'categories', label: 'Escalafón y Ascensos', icon: Award },
        { id: 'dedications', label: 'Dedicación Horaria', icon: Clock },
      ],
    },
    {
      group: 'ASIGNACIÓN TERRITORIAL',
      items: [
        { id: 'sites', label: 'Sedes y Cátedras', icon: Building2 },
        { id: 'permissions', label: 'Permisos y Licencias', icon: FileCheck2, badge: pendingPermissionsCount > 0 ? pendingPermissionsCount : undefined, badgeColor: 'bg-amber-500 text-slate-950 font-bold' },
      ],
    },
    {
      group: 'REPORTES Y MEMORANDOS',
      items: [
        { id: 'reports', label: 'Memos e Informes', icon: FileText, badge: reports.length },
        { id: 'csv', label: 'Ingesta de Datos CSV', icon: FileSpreadsheet },
      ],
    },
    {
      group: 'ADMINISTRACIÓN Y AUDITORÍA',
      items: [
        ...(isAdmin ? [{ id: 'users', label: 'Control de Usuarios', icon: Users }] : []),
        { id: 'activity', label: 'Registro de Auditoría', icon: History },
      ],
    },
  ];

  return (
    <>
      {/* Mobile Backdrop */}
      {isOpen && (
        <div
          onClick={onClose}
          className="fixed inset-0 bg-slate-950/60 backdrop-blur-xs z-40 lg:hidden"
        />
      )}

      <aside
        className={`fixed inset-y-0 left-0 z-50 w-64 bg-slate-900 text-slate-300 flex flex-col justify-between border-r border-slate-800 transition-transform duration-300 ease-in-out lg:static lg:translate-x-0 ${
          isOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'
        }`}
      >
        <div className="p-4 space-y-6 overflow-y-auto">
          {navItems.map((section, idx) => (
          <div key={idx} className="space-y-1">
            <h3 className="text-[11px] font-bold uppercase tracking-wider text-slate-400 px-3">
              {section.group}
            </h3>
            <nav className="space-y-0.5">
              {section.items.map(item => {
                const Icon = item.icon;
                const isActive = activeTab === item.id;
                return (
                  <button
                    key={item.id}
                    onClick={() => handleNavClick(item.id)}
                    className={`w-full flex items-center justify-between px-3 py-2 rounded-lg text-xs font-medium transition-all ${
                      isActive
                        ? 'bg-[#003366] text-white shadow-sm border-l-4 border-amber-400 font-semibold'
                        : 'text-slate-300 hover:bg-slate-800 hover:text-white'
                    }`}
                  >
                    <div className="flex items-center space-x-2.5">
                      <Icon className={`w-4 h-4 ${isActive ? 'text-amber-400' : 'text-slate-400'}`} />
                      <span>{item.label}</span>
                    </div>
                    {item.badge !== undefined && (
                      <span className={`text-[10px] px-1.5 py-0.5 rounded-full ${item.badgeColor || 'bg-slate-700 text-slate-200'}`}>
                        {item.badge}
                      </span>
                    )}
                  </button>
                );
              })}
            </nav>
          </div>
        ))}
      </div>

      {/* Footer Info */}
      <div className="p-4 border-t border-slate-800 text-[11px] text-slate-400 space-y-1">
        <div className="flex items-center space-x-1.5 text-slate-300">
          <div className="w-2 h-2 rounded-full bg-emerald-500"></div>
          <span className="font-medium text-slate-200">Sistema Operativo</span>
        </div>
        <p className="text-[10px] text-slate-400">UNERG • Versión 3.4.1 (SIGEDOR)</p>
      </div>
    </aside>
    </>
  );
};
