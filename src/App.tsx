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
import { Teacher, Report } from './types';

const MainApp: React.FC = () => {
  const [currentSection, setCurrentSection] = useState<NavSection>('dashboard');
  const [selectedTeacher, setSelectedTeacher] = useState<Teacher | null>(null);
  const [viewingReport, setViewingReport] = useState<Report | null>(null);
  const [showApiDocs, setShowApiDocs] = useState(false);

  const { generateReport } = useData();

  const handleGenerateAndOpenReport = (teacher: Teacher, type: Report['typeReport']) => {
    const report = generateReport({
      teacher_cdi: teacher.cdi,
      typeReport: type,
    });
    setViewingReport(report);
  };

  return (
    <div className="min-h-screen bg-slate-50 flex flex-col font-sans">
      {/* Top Navbar */}
      <Header onOpenApiDocs={() => setShowApiDocs(true)} />

      {/* Main Body */}
      <div className="flex-1 flex overflow-hidden">
        {/* Sidebar */}
        <Sidebar
          currentSection={currentSection}
          onSelectSection={(sec) => {
            setCurrentSection(sec);
            setViewingReport(null);
          }}
        />

        {/* Content Area */}
        <main className="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8">
          <div className="max-w-7xl mx-auto">
            {/* If viewing a printable official document */}
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
                    onNavigate={(sec) => setCurrentSection(sec)}
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

                {currentSection === 'users' && <UserManagement />}
              </>
            )}
          </div>
        </main>
      </div>

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
