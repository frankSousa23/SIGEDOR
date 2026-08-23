import React, { useState } from 'react';
import { useApp } from './context/AppContext';
import { Header } from './components/Header';
import { Sidebar } from './components/Sidebar';
import { DashboardView } from './components/DashboardView';
import { TeachersView } from './components/TeachersView';
import { CategoriesView } from './components/CategoriesView';
import { DedicationsView } from './components/DedicationsView';
import { SitesView } from './components/SitesView';
import { PermissionsView } from './components/PermissionsView';
import { ReportsView } from './components/ReportsView';
import { CsvDataView } from './components/CsvDataView';
import { UsersView } from './components/UsersView';
import { OfficialDocumentModal } from './components/OfficialDocumentModal';

export const AppContent: React.FC = () => {
  const { activeTab, currentUser } = useApp();
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const renderActiveView = () => {
    switch (activeTab) {
      case 'dashboard':
        return <DashboardView />;
      case 'teachers':
        return <TeachersView />;
      case 'categories':
        return <CategoriesView />;
      case 'dedications':
        return <DedicationsView />;
      case 'sites':
        return <SitesView />;
      case 'permissions':
        return <PermissionsView />;
      case 'reports':
        return <ReportsView />;
      case 'csv':
        return <CsvDataView />;
      case 'users':
        return <UsersView />;
      default:
        return <DashboardView />;
    }
  };

  return (
    <div className="flex h-screen overflow-hidden bg-slate-100 font-sans antialiased text-slate-800">
      {/* Sidebar Navigation */}
      <Sidebar isOpen={sidebarOpen} onClose={() => setSidebarOpen(false)} />

      {/* Main Content Area */}
      <div className="flex-1 flex flex-col min-w-0 overflow-hidden">
        <Header onToggleSidebar={() => setSidebarOpen(!sidebarOpen)} />

        <main className="flex-1 overflow-y-auto p-4 md:p-6 lg:p-8 space-y-6">
          {renderActiveView()}
        </main>
      </div>
    </div>
  );
};

export default AppContent;
