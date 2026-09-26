import React, { useState } from 'react';
import { AuthProvider, useAuth } from './context/AuthContext';
import { DataProvider, useData } from './context/DataContext';
import { Header } from './components/Header';
import { Sidebar, NavSection } from './components/Sidebar';
import { Dashboard } from './components/Dashboard';
import { TeacherList } from './components/TeacherList';
import { TeacherDetailModal } from './components/TeacherDetailModal';
import { CategoryManagement } from './components/CategoryManagement';
import { DedicationManagement } from './components/DedicationManagement';
import { SiteManagement } from './components/SiteManagement';
import { PermissionManagement } from './components/PermissionManagement';
import { ReportManagement } from './components/ReportManagement';
import { UserManagement } from './components/UserManagement';
import { OfficialDocumentView } from './components/OfficialDocumentView';
import { ApiDocsModal } from './components/ApiDocsModal';
import { DataManagementModal } from './components/DataManagementModal';
import { SystemPresentationView } from './components/SystemPresentationView';
import { RoleAuditorBar } from './components/RoleAuditorBar';
import { Teacher, Report } from './types';
import { 
  LayoutDashboard, 
  Users, 
  FileText, 
  Presentation, 
  Menu,
  Award,
  ChevronRight,
  ShieldCheck
} from 'lucide-react';

const sectionLabels: Record<NavSection, string> = {
  dashboard: 'Escritorio Principal',
  teachers: 'Expedientes Docentes',
  categories: 'Escalafón Universitario',
  dedications: 'Dedicación y Horas',
  sites: 'Sedes y Cátedras',
  permissions: 'Permisos y Licencias',
  reports: 'Reportes y PDF Oficial',
  architecture: 'Visión y Láminas (PDF)',
  users: 'Control de Usuarios y Roles',
};

const MainApp: React.FC = () => {
  const [currentSection, setCurrentSection] = useState<NavSection>('dashboard');
  const [selectedTeacher, setSelectedTeacher] = useState<Teacher | null>(null);
  const [viewingReport, setViewingReport] = useState<Report | null>(null);
  const [showApiDocs, setShowApiDocs] = useState(false);
  const [showDataManagement, setShowDataManagement] = useState(false);
  const [isMobileMenuOpen, setIsMobileMenuOpen] = useState(false);
  const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(false);

  const { isSuperAdmin, isTeacher } = useAuth();
  const { generateReport } = useData();

  const handleGenerateAndOpenReport = (teacher: Teacher, type: Report['typeReport']) => {
    const report = generateReport({
      teacher_cdi: teacher.cdi,
      typeReport: type,
    });
    setViewingReport(report);
  };

  const handleNavigate = (sec: NavSection) => {
    setCurrentSection(sec);
    setViewingReport(null);
    setIsMobileMenuOpen(false);
  };

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col font-sans">
      {/* Top Navbar */}
      <Header 
        onOpenApiDocs={() => setShowApiDocs(true)}
        onOpenDataManagement={() => setShowDataManagement(true)}
        onToggleMobileMenu={() => setIsMobileMenuOpen(!isMobileMenuOpen)}
        isMobileMenuOpen={isMobileMenuOpen}
      />

      {/* Role Simulation and Audit Bar */}
      <RoleAuditorBar />

      {/* Main Body */}
      <div className="flex-1 flex overflow-hidden">
        {/* Sidebar */}
        <Sidebar
          currentSection={currentSection}
          onSelectSection={handleNavigate}
          isMobileOpen={isMobileMenuOpen}
          onCloseMobile={() => setIsMobileMenuOpen(false)}
          isCollapsed={isSidebarCollapsed}
          onToggleCollapse={() => setIsSidebarCollapsed(!isSidebarCollapsed)}
        />

        {/* Content Area */}
        <main className="flex-1 overflow-y-auto p-3 sm:p-5 lg:p-7 pb-20 md:pb-7">
          <div className="max-w-7xl mx-auto space-y-4">
            {/* Breadcrumb / Top Context Indicator */}
            <div className="no-print flex items-center justify-between text-xs text-slate-500 pb-1">
              <div className="flex items-center gap-1.5 truncate">
                <span 
                  onClick={() => handleNavigate('dashboard')}
                  className="hover:text-slate-900 cursor-pointer transition-colors font-medium"
                >
                  SIGEDOR
                </span>
                <ChevronRight className="w-3.5 h-3.5 text-slate-400" />
                <span className="font-semibold text-slate-800">
                  {viewingReport 
                    ? `Documento: ${viewingReport.typeReport}` 
                    : isTeacher && currentSection === 'teachers'
                    ? 'Mi Expediente Docente 360°'
                    : isTeacher && currentSection === 'sites'
                    ? 'Mis Cátedras Asignadas'
                    : isTeacher && currentSection === 'permissions'
                    ? 'Mis Permisos y Licencias'
                    : isTeacher && currentSection === 'reports'
                    ? 'Mis Documentos Oficiales'
                    : sectionLabels[currentSection]}
                </span>
              </div>
              <span className="hidden sm:inline text-[11px] text-slate-400 bg-slate-200/60 px-2 py-0.5 rounded-md font-mono">
                UNERG v2.0
              </span>
            </div>

            {/* If viewing a printable official document */}
            <div key={viewingReport ? `report-${viewingReport.id}` : currentSection} className="animate-view-transition">
              {viewingReport ? (
                <OfficialDocumentView
                  report={viewingReport}
                  onBack={() => setViewingReport(null)}
                />
              ) : (
                <>
                  {currentSection === 'dashboard' && (
                    <Dashboard
                      onSelectTeacher={(cdi) => {}}
                      onViewReport={(rep) => setViewingReport(rep)}
                      onNavigate={handleNavigate}
                    />
                  )}

                  {currentSection === 'teachers' && (
                    <TeacherList
                      onOpenTeacher={(t) => setSelectedTeacher(t)}
                      onGenerateReport={handleGenerateAndOpenReport}
                    />
                  )}

                  {currentSection === 'categories' && <CategoryManagement />}

                  {currentSection === 'dedications' && <DedicationManagement />}

                  {currentSection === 'sites' && <SiteManagement />}

                  {currentSection === 'permissions' && <PermissionManagement />}

                  {currentSection === 'reports' && (
                    <ReportManagement onViewReport={(rep) => setViewingReport(rep)} />
                  )}

                  {currentSection === 'architecture' && <SystemPresentationView />}

                  {currentSection === 'users' && (
                    isSuperAdmin ? (
                      <UserManagement />
                    ) : (
                      <div className="bg-white rounded-2xl p-8 border border-slate-200 text-center max-w-lg mx-auto shadow-sm space-y-3">
                        <div className="w-12 h-12 rounded-full bg-red-50 text-red-600 flex items-center justify-center mx-auto">
                          <ShieldCheck className="w-6 h-6" />
                        </div>
                        <h3 className="font-bold text-slate-800 text-sm">Módulo Reservado a Administradores Globales</h3>
                        <p className="text-xs text-slate-500 leading-relaxed">
                          La administración de cuentas de usuario, aprobación de accesos y asignación de roles RBAC está restringida exclusivamente a usuarios con credenciales de Super Administrador institucional.
                        </p>
                      </div>
                    )
                  )}
                </>
              )}
            </div>
          </div>
        </main>
      </div>

      {/* Mobile Bottom Navigation Bar (App-like UX) */}
      <nav className="no-print md:hidden fixed bottom-0 left-0 right-0 z-30 bg-white border-t border-slate-200 px-2 py-1.5 flex items-center justify-around shadow-lg">
        <button
          onClick={() => handleNavigate('dashboard')}
          className={`flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-[10px] font-semibold transition-colors ${
            currentSection === 'dashboard' && !viewingReport ? 'text-unerg-blue' : 'text-slate-500 hover:text-slate-800'
          }`}
        >
          <LayoutDashboard className="w-4 h-4" />
          <span>Inicio</span>
        </button>

        <button
          onClick={() => handleNavigate('teachers')}
          className={`flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-[10px] font-semibold transition-colors ${
            currentSection === 'teachers' && !viewingReport ? 'text-unerg-blue' : 'text-slate-500 hover:text-slate-800'
          }`}
        >
          <Users className="w-4 h-4" />
          <span>Docentes</span>
        </button>

        <button
          onClick={() => handleNavigate('reports')}
          className={`flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-[10px] font-semibold transition-colors ${
            currentSection === 'reports' || viewingReport ? 'text-unerg-blue' : 'text-slate-500 hover:text-slate-800'
          }`}
        >
          <FileText className="w-4 h-4" />
          <span>Reportes</span>
        </button>

        <button
          onClick={() => handleNavigate('architecture')}
          className={`flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-[10px] font-semibold transition-colors ${
            currentSection === 'architecture' && !viewingReport ? 'text-unerg-blue' : 'text-slate-500 hover:text-slate-800'
          }`}
        >
          <Presentation className="w-4 h-4" />
          <span>Láminas</span>
        </button>

        <button
          onClick={() => setIsMobileMenuOpen(true)}
          className="flex flex-col items-center gap-0.5 py-1 px-2 rounded-lg text-[10px] font-semibold text-slate-500 hover:text-slate-800 transition-colors"
        >
          <Menu className="w-4 h-4" />
          <span>Más</span>
        </button>
      </nav>

      {/* Teacher 360° Dossier Modal */}
      {selectedTeacher && (
        <TeacherDetailModal
          teacher={selectedTeacher}
          onClose={() => setSelectedTeacher(null)}
          onGenerateReport={(teacher, type) => {
            setSelectedTeacher(null);
            handleGenerateAndOpenReport(teacher, type);
          }}
        />
      )}

      {/* Swagger / OpenAPI Interactive Explorer Modal */}
      {showApiDocs && <ApiDocsModal onClose={() => setShowApiDocs(false)} />}

      {/* Data Management & Integration Modal */}
      {showDataManagement && (
        <DataManagementModal
          isOpen={showDataManagement}
          onClose={() => setShowDataManagement(false)}
        />
      )}
    </div>
  );
};

export function App() {
  return (
    <AuthProvider>
      <DataProvider>
        <MainApp />
      </DataProvider>
    </AuthProvider>
  );
}

export default App;
