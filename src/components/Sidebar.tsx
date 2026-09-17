import React from 'react';
import { useAuth } from '../context/AuthContext';
import { 
  LayoutDashboard, 
  Users, 
  Award, 
  Clock, 
  MapPin, 
  FileText, 
  ShieldCheck, 
  CalendarDays,
  FileSpreadsheet
} from 'lucide-react';

export type NavSection = 
  | 'dashboard' 
  | 'teachers' 
  | 'categories' 
  | 'dedications' 
  | 'sites' 
  | 'permissions' 
  | 'reports' 
  | 'users';

interface SidebarProps {
  currentSection: NavSection;
  onSelectSection: (section: NavSection) => void;
}

export const Sidebar: React.FC<SidebarProps> = ({ currentSection, onSelectSection }) => {
  const { isSuperAdmin, isAreaManager, isTeacher } = useAuth();

  const navItems: { id: NavSection; label: string; icon: any; show: boolean; countBadge?: number }[] = [
    {
      id: 'dashboard',
      label: 'Escritorio Principal',
      icon: LayoutDashboard,
      show: true,
    },
    {
      id: 'teachers',
      label: 'Expedientes Docentes',
      icon: Users,
      show: true,
    },
    {
      id: 'categories',
      label: 'Escalafón Universitario',
      icon: Award,
      show: true,
    },
    {
      id: 'dedications',
      label: 'Dedicación y Horas',
      icon: Clock,
      show: true,
    },
    {
      id: 'sites',
      label: 'Sedes y Cátedras',
      icon: MapPin,
      show: true,
    },
    {
      id: 'permissions',
      label: 'Permisos y Licencias',
      icon: CalendarDays,
      show: true,
    },
    {
      id: 'reports',
      label: 'Reportes y PDF Oficial',
      icon: FileText,
      show: true,
    },
    {
      id: 'users',
      label: 'Control de Usuarios',
      icon: ShieldCheck,
      show: isSuperAdmin,
    },
  ];

  return (
    <aside className="no-print w-64 bg-slate-900 text-slate-300 flex-shrink-0 flex flex-col border-r border-slate-800">
      <div className="p-4 border-b border-slate-800/80">
        <div className="text-[11px] uppercase tracking-wider text-slate-400 font-bold mb-1">
          Panel Filament v3
        </div>
        <div className="text-xs text-slate-400 font-medium">
          {isSuperAdmin ? 'Administración Global' : isAreaManager ? 'Gestión de Sede / Área' : 'Portal Docente'}
        </div>
      </div>

      <nav className="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto">
        {navItems.filter(item => item.show).map(item => {
          const Icon = item.icon;
          const isActive = currentSection === item.id;
          return (
            <button
              key={item.id}
              id={`nav-${item.id}`}
              onClick={() => onSelectSection(item.id)}
              className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-all ${
                isActive
                  ? 'bg-unerg-blue text-white shadow-md'
                  : 'text-slate-300 hover:bg-slate-800 hover:text-white'
              }`}
            >
              <Icon className={`w-4 h-4 ${isActive ? 'text-amber-300' : 'text-slate-400'}`} />
              <span className="flex-1 text-left">{item.label}</span>
            </button>
          );
        })}
      </nav>

      {/* Footer institutional note */}
      <div className="p-4 border-t border-slate-800 text-[11px] text-slate-500">
        <div className="font-semibold text-slate-400">UNERG - Guárico</div>
        <div>San Juan de los Morros</div>
        <div className="text-[10px] text-slate-500 mt-1">Licencia MIT • Frank Sousa</div>
      </div>
    </aside>
  );
};
