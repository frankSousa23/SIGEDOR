import React from 'react';
import { useAuth } from '../context/AuthContext';
import { useData } from '../context/DataContext';
import { 
  LayoutDashboard, 
  Users, 
  Award, 
  Clock, 
  MapPin, 
  FileText, 
  ShieldCheck, 
  CalendarDays,
  Presentation,
  ChevronLeft,
  ChevronRight,
  X
} from 'lucide-react';

export type NavSection = 
  | 'dashboard' 
  | 'teachers' 
  | 'categories' 
  | 'dedications' 
  | 'sites' 
  | 'permissions' 
  | 'reports' 
  | 'users'
  | 'architecture';

interface SidebarProps {
  currentSection: NavSection;
  onSelectSection: (section: NavSection) => void;
  isMobileOpen?: boolean;
  onCloseMobile?: () => void;
  isCollapsed?: boolean;
  onToggleCollapse?: () => void;
}

export const Sidebar: React.FC<SidebarProps> = ({ 
  currentSection, 
  onSelectSection,
  isMobileOpen = false,
  onCloseMobile,
  isCollapsed = false,
  onToggleCollapse
}) => {
  const { isSuperAdmin, isAreaManager, isTeacher } = useAuth();
  const { filteredTeachers, filteredPermissions, filteredReports } = useData();

  const pendingPermissionsCount = filteredPermissions.filter(p => p.status === 'Pendiente').length;

  const navItems: { id: NavSection; label: string; icon: any; show: boolean; countBadge?: string | number }[] = [
    {
      id: 'dashboard',
      label: 'Escritorio Principal',
      icon: LayoutDashboard,
      show: true,
    },
    {
      id: 'teachers',
      label: isTeacher ? 'Mi Expediente 360°' : 'Expedientes Docentes',
      icon: Users,
      show: true,
      countBadge: filteredTeachers.length,
    },
    {
      id: 'categories',
      label: isTeacher ? 'Mi Escalafón' : 'Escalafón Universitario',
      icon: Award,
      show: true,
    },
    {
      id: 'dedications',
      label: isTeacher ? 'Mi Dedicación y Horas' : 'Dedicación y Horas',
      icon: Clock,
      show: true,
    },
    {
      id: 'sites',
      label: isTeacher ? 'Mis Cátedras' : 'Sedes y Cátedras',
      icon: MapPin,
      show: true,
    },
    {
      id: 'permissions',
      label: isTeacher ? 'Mis Solicitudes' : 'Permisos y Licencias',
      icon: CalendarDays,
      show: true,
      countBadge: pendingPermissionsCount > 0 ? `${pendingPermissionsCount} pend.` : undefined,
    },
    {
      id: 'reports',
      label: isTeacher ? 'Mis Documentos Oficiales' : 'Reportes y PDF Oficial',
      icon: FileText,
      show: true,
      countBadge: filteredReports.length,
    },
    {
      id: 'architecture',
      label: 'Visión y Láminas (PDF)',
      icon: Presentation,
      show: true,
    },
    {
      id: 'users',
      label: 'Control de Usuarios',
      icon: ShieldCheck,
      show: isSuperAdmin,
    },
  ];

  const handleSelect = (id: NavSection) => {
    onSelectSection(id);
    if (onCloseMobile) {
      onCloseMobile();
    }
  };

  const navContent = (isMobile: boolean = false) => (
    <div className="flex flex-col h-full">
      {/* Header */}
      <div className="p-4 border-b border-slate-800/80 flex items-center justify-between">
        {(!isCollapsed || isMobile) ? (
          <div>
            <div className="text-[11px] uppercase tracking-wider text-slate-400 font-bold mb-0.5">
              Panel Filament v3
            </div>
            <div className="text-xs text-slate-400 font-medium truncate max-w-[170px]">
              {isSuperAdmin ? 'Administración Global' : isAreaManager ? 'Gestión de Sede / Área' : 'Portal Docente'}
            </div>
          </div>
        ) : (
          <div className="mx-auto text-[10px] font-bold text-amber-400">UNERG</div>
        )}

        {isMobile && onCloseMobile && (
          <button
            onClick={onCloseMobile}
            className="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
            aria-label="Cerrar menú"
          >
            <X className="w-5 h-5" />
          </button>
        )}

        {!isMobile && onToggleCollapse && (
          <button
            onClick={onToggleCollapse}
            className="hidden lg:flex p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 transition-colors"
            title={isCollapsed ? "Expandir barra lateral" : "Contraer barra lateral"}
          >
            {isCollapsed ? <ChevronRight className="w-4 h-4" /> : <ChevronLeft className="w-4 h-4" />}
          </button>
        )}
      </div>

      {/* Navigation items */}
      <nav className="flex-1 px-3 py-3 space-y-1.5 overflow-y-auto">
        {navItems.filter(item => item.show).map(item => {
          const Icon = item.icon;
          const isActive = currentSection === item.id;
          return (
            <button
              key={item.id}
              id={`nav-${item.id}`}
              onClick={() => handleSelect(item.id)}
              title={isCollapsed && !isMobile ? item.label : undefined}
              className={`w-full flex items-center gap-3 px-3 py-2.5 rounded-lg text-xs font-semibold transition-all ${
                isActive
                  ? 'bg-unerg-blue text-white shadow-md'
                  : 'text-slate-300 hover:bg-slate-800 hover:text-white'
              } ${isCollapsed && !isMobile ? 'justify-center px-2' : ''}`}
            >
              <Icon className={`w-4 h-4 flex-shrink-0 ${isActive ? 'text-amber-300' : 'text-slate-400'}`} />
              {(!isCollapsed || isMobile) && (
                <>
                  <span className="flex-1 text-left truncate">{item.label}</span>
                  {item.countBadge !== undefined && (
                    <span className={`text-[10px] px-1.5 py-0.5 rounded-full font-bold ${
                      isActive 
                        ? 'bg-white/20 text-white' 
                        : 'bg-slate-800 text-slate-400'
                    }`}>
                      {item.countBadge}
                    </span>
                  )}
                </>
              )}
            </button>
          );
        })}
      </nav>

      {/* Footer */}
      {(!isCollapsed || isMobile) && (
        <div className="p-3.5 border-t border-slate-800 text-[11px] text-slate-500">
          <div className="font-semibold text-slate-400">UNERG - Guárico</div>
          <div>San Juan de los Morros</div>
          <div className="text-[10px] text-slate-500 mt-0.5">Licencia MIT • Frank Sousa</div>
        </div>
      )}
    </div>
  );

  return (
    <>
      {/* Mobile Drawer (Backdrop + Slide-in Sidebar) */}
      {isMobileOpen && (
        <div className="fixed inset-0 z-50 md:hidden flex">
          {/* Backdrop overlay */}
          <div 
            className="fixed inset-0 bg-slate-950/60 backdrop-blur-xs transition-opacity animate-in fade-in duration-200"
            onClick={onCloseMobile}
          />
          {/* Drawer content */}
          <aside className="relative w-72 max-w-[80vw] bg-slate-900 text-slate-300 flex flex-col shadow-2xl border-r border-slate-800 z-10 animate-in slide-in-from-left duration-200">
            {navContent(true)}
          </aside>
        </div>
      )}

      {/* Desktop Persistent Sidebar */}
      <aside 
        className={`no-print hidden md:flex flex-col bg-slate-900 text-slate-300 flex-shrink-0 border-r border-slate-800 transition-all duration-200 ease-in-out ${
          isCollapsed ? 'w-16' : 'w-64'
        }`}
      >
        {navContent(false)}
      </aside>
    </>
  );
};
