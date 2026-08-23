import React, { createContext, useContext, useState, useEffect } from 'react';
import {
  User,
  Teacher,
  Category,
  Dedication,
  SiteAssignment,
  TeacherPermission,
  ReportItem,
  Sede,
  Area,
  Programa,
  ActivityLog,
  UserRole,
  CategoryLevel,
  PermissionStatus
} from '../types';
import {
  INITIAL_USERS,
  INITIAL_TEACHERS,
  INITIAL_CATEGORIES,
  INITIAL_DEDICATIONS,
  INITIAL_SITES,
  INITIAL_PERMISSIONS,
  INITIAL_REPORTS,
  INITIAL_SEDES,
  INITIAL_AREAS,
  INITIAL_PROGRAMAS,
  INITIAL_ACTIVITY_LOGS
} from '../data/initialData';

interface AppContextType {
  currentUser: User;
  setCurrentUser: (user: User) => void;
  users: User[];
  teachers: Teacher[];
  categories: Category[];
  dedications: Dedication[];
  sites: SiteAssignment[];
  permissions: TeacherPermission[];
  reports: ReportItem[];
  sedes: Sede[];
  areas: Area[];
  programas: Programa[];
  activityLogs: ActivityLog[];
  
  // Navigation
  activeTab: string;
  setActiveTab: (tab: string) => void;
  
  // Toast notifications
  toast: { message: string; type: 'success' | 'error' | 'info' } | null;
  showToast: (message: string, type?: 'success' | 'error' | 'info') => void;
  
  // Teacher CRUD
  addTeacher: (teacher: Omit<Teacher, 'id'>) => void;
  updateTeacher: (id: number, teacher: Partial<Teacher>) => void;
  deleteTeacher: (id: number) => void;
  
  // Category / Escalafón CRUD
  saveCategory: (category: Omit<Category, 'id'> & { id?: number }) => void;
  
  // Dedication CRUD
  saveDedication: (dedication: Omit<Dedication, 'id'> & { id?: number }) => void;
  
  // Site Assignment CRUD
  saveSiteAssignment: (site: Omit<SiteAssignment, 'id'> & { id?: number }) => void;
  toggleSiteActive: (id: number) => void;
  deleteSiteAssignment: (id: number) => void;
  
  // Permissions CRUD
  addPermission: (permission: Omit<TeacherPermission, 'id' | 'created_at'>) => void;
  updatePermissionStatus: (id: number, status: PermissionStatus) => void;
  deletePermission: (id: number) => void;
  
  // Reports CRUD
  addReport: (report: Omit<ReportItem, 'id' | 'created_at'>) => void;
  deleteReport: (id: number) => void;
  
  // User Management
  toggleUserApproved: (id: number) => void;
  toggleUserActive: (id: number) => void;
  updateUserRoles: (id: number, roles: UserRole[]) => void;
  addUser: (user: Omit<User, 'id' | 'created_at'>) => void;
  updateUser: (id: number, user: Partial<User>) => void;
  deleteUser: (id: number) => void;
  
  // Audit Log
  logActivity: (event: ActivityLog['event'], description: string, target?: string) => void;
  
  // CSV Import / Export
  importCsv: (type: 'teachers' | 'categories' | 'dedications' | 'sites' | 'users', csvText: string) => { success: boolean; count: number; error?: string };
  exportCsv: (type: 'teachers' | 'categories' | 'dedications' | 'sites' | 'users') => void;
  
  // Reset demo data
  resetToInitialData: () => void;
}

const AppContext = createContext<AppContextType | undefined>(undefined);

export const AppProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const [users, setUsers] = useState<User[]>(() => {
    const saved = localStorage.getItem('sigedor_users');
    return saved ? JSON.parse(saved) : INITIAL_USERS;
  });

  const [currentUser, setCurrentUser] = useState<User>(() => {
    const saved = localStorage.getItem('sigedor_current_user');
    return saved ? JSON.parse(saved) : INITIAL_USERS[0];
  });

  const [teachers, setTeachers] = useState<Teacher[]>(() => {
    const saved = localStorage.getItem('sigedor_teachers');
    return saved ? JSON.parse(saved) : INITIAL_TEACHERS;
  });

  const [categories, setCategories] = useState<Category[]>(() => {
    const saved = localStorage.getItem('sigedor_categories');
    return saved ? JSON.parse(saved) : INITIAL_CATEGORIES;
  });

  const [dedications, setDedications] = useState<Dedication[]>(() => {
    const saved = localStorage.getItem('sigedor_dedications');
    return saved ? JSON.parse(saved) : INITIAL_DEDICATIONS;
  });

  const [sites, setSites] = useState<SiteAssignment[]>(() => {
    const saved = localStorage.getItem('sigedor_sites');
    return saved ? JSON.parse(saved) : INITIAL_SITES;
  });

  const [permissions, setPermissions] = useState<TeacherPermission[]>(() => {
    const saved = localStorage.getItem('sigedor_permissions');
    return saved ? JSON.parse(saved) : INITIAL_PERMISSIONS;
  });

  const [reports, setReports] = useState<ReportItem[]>(() => {
    const saved = localStorage.getItem('sigedor_reports');
    return saved ? JSON.parse(saved) : INITIAL_REPORTS;
  });

  const [activityLogs, setActivityLogs] = useState<ActivityLog[]>(() => {
    const saved = localStorage.getItem('sigedor_activity');
    return saved ? JSON.parse(saved) : INITIAL_ACTIVITY_LOGS;
  });

  const [activeTab, setActiveTab] = useState<string>('dashboard');
  const [toast, setToast] = useState<{ message: string; type: 'success' | 'error' | 'info' } | null>(null);

  const sedes = INITIAL_SEDES;
  const areas = INITIAL_AREAS;
  const programas = INITIAL_PROGRAMAS;

  // Persist state changes
  useEffect(() => {
    localStorage.setItem('sigedor_users', JSON.stringify(users));
  }, [users]);

  useEffect(() => {
    localStorage.setItem('sigedor_current_user', JSON.stringify(currentUser));
  }, [currentUser]);

  useEffect(() => {
    localStorage.setItem('sigedor_teachers', JSON.stringify(teachers));
  }, [teachers]);

  useEffect(() => {
    localStorage.setItem('sigedor_categories', JSON.stringify(categories));
  }, [categories]);

  useEffect(() => {
    localStorage.setItem('sigedor_dedications', JSON.stringify(dedications));
  }, [dedications]);

  useEffect(() => {
    localStorage.setItem('sigedor_sites', JSON.stringify(sites));
  }, [sites]);

  useEffect(() => {
    localStorage.setItem('sigedor_permissions', JSON.stringify(permissions));
  }, [permissions]);

  useEffect(() => {
    localStorage.setItem('sigedor_reports', JSON.stringify(reports));
  }, [reports]);

  useEffect(() => {
    localStorage.setItem('sigedor_activity', JSON.stringify(activityLogs));
  }, [activityLogs]);

  const showToast = (message: string, type: 'success' | 'error' | 'info' = 'success') => {
    setToast({ message, type });
    setTimeout(() => {
      setToast(null);
    }, 4000);
  };

  const logActivity = (event: ActivityLog['event'], description: string, target?: string) => {
    const newLog: ActivityLog = {
      id: `log-${Date.now()}-${Math.random().toString(36).substring(2, 6)}`,
      user_name: currentUser.name,
      user_email: currentUser.email,
      event,
      description,
      target_subject: target,
      timestamp: new Date().toISOString().replace('T', ' ').substring(0, 19),
    };
    setActivityLogs(prev => [newLog, ...prev]);
  };

  // Teacher actions
  const addTeacher = (teacherData: Omit<Teacher, 'id'>) => {
    const newId = teachers.length > 0 ? Math.max(...teachers.map(t => t.id)) + 1 : 1;
    const newTeacher: Teacher = { ...teacherData, id: newId };
    setTeachers(prev => [newTeacher, ...prev]);

    // Create default category if not exists
    if (!categories.some(c => c.teacher_cdi === newTeacher.cdi)) {
      const newCat: Category = {
        id: categories.length > 0 ? Math.max(...categories.map(c => c.id)) + 1 : 1,
        teacher_cdi: newTeacher.cdi,
        preTitle: 'Licenciado(a) / Ingeniero(a)',
        lastTitle: '',
        current_category: 'Instructor',
        instructor: newTeacher.datePromotion || new Date().toISOString().split('T')[0],
        disable_assistant_rule: false,
        info: 'Registro inicial de escalafón'
      };
      setCategories(prev => [newCat, ...prev]);
    }

    // Create default dedication if not exists
    if (!dedications.some(d => d.teacher_cdi === newTeacher.cdi)) {
      const newDed: Dedication = {
        id: dedications.length > 0 ? Math.max(...dedications.map(d => d.id)) + 1 : 1,
        teacher_cdi: newTeacher.cdi,
        name: 'Tiempo Completo',
        hours: 30,
        director: '',
        studentNumber: 30,
        studentHours: 4,
        info: 'Carga académica inicial asignada'
      };
      setDedications(prev => [newDed, ...prev]);
    }

    // Create initial site assignment
    if (!sites.some(s => s.teacher_cdi === newTeacher.cdi)) {
      const newSite: SiteAssignment = {
        id: sites.length > 0 ? Math.max(...sites.map(s => s.id)) + 1 : 1,
        teacher_cdi: newTeacher.cdi,
        sede_nombre: newTeacher.sede_nombre,
        area_nombre: newTeacher.area_nombre,
        programa_nombre: newTeacher.programa_nombre,
        uc: 3,
        weekHours: 6,
        sections: 2,
        info: `Cátedra de ${newTeacher.asignaturePromotion || 'Docencia Ordinaria'}`,
        is_active: true
      };
      setSites(prev => [newSite, ...prev]);
    }

    logActivity('created', `Docente registrado: ${newTeacher.name} ${newTeacher.surName} (C.I. ${newTeacher.cdi})`, newTeacher.cdi);
    showToast(`Docente ${newTeacher.name} ${newTeacher.surName} registrado con éxito.`);
  };

  const updateTeacher = (id: number, teacherData: Partial<Teacher>) => {
    setTeachers(prev => prev.map(t => (t.id === id ? { ...t, ...teacherData } : t)));
    logActivity('updated', `Expediente docente actualizado para ID: ${id}`, teacherData.cdi);
    showToast('Expediente docente actualizado correctamente.');
  };

  const deleteTeacher = (id: number) => {
    const teacher = teachers.find(t => t.id === id);
    if (!teacher) return;
    setTeachers(prev => prev.filter(t => t.id !== id));
    logActivity('deleted', `Docente eliminado del sistema: ${teacher.name} ${teacher.surName} (C.I. ${teacher.cdi})`, teacher.cdi);
    showToast(`Docente ${teacher.name} ${teacher.surName} eliminado.`);
  };

  // Category / Escalafón
  const saveCategory = (categoryData: Omit<Category, 'id'> & { id?: number }) => {
    if (categoryData.id) {
      setCategories(prev => prev.map(c => c.id === categoryData.id ? (categoryData as Category) : c));
      logActivity('updated', `Escalafón actualizado para C.I. ${categoryData.teacher_cdi}: ${categoryData.current_category}`, categoryData.teacher_cdi);
    } else {
      const newId = categories.length > 0 ? Math.max(...categories.map(c => c.id)) + 1 : 1;
      const newCat: Category = { ...categoryData, id: newId };
      setCategories(prev => [newCat, ...prev]);
      logActivity('created', `Escalafón creado para C.I. ${categoryData.teacher_cdi}: ${categoryData.current_category}`, categoryData.teacher_cdi);
    }
    showToast('Datos de escalafón guardados.');
  };

  // Dedication
  const saveDedication = (dedicationData: Omit<Dedication, 'id'> & { id?: number }) => {
    if (dedicationData.id) {
      setDedications(prev => prev.map(d => d.id === dedicationData.id ? (dedicationData as Dedication) : d));
      logActivity('updated', `Dedicación horaria actualizada para C.I. ${dedicationData.teacher_cdi}: ${dedicationData.name} (${dedicationData.hours}h)`, dedicationData.teacher_cdi);
    } else {
      const newId = dedications.length > 0 ? Math.max(...dedications.map(d => d.id)) + 1 : 1;
      const newDed: Dedication = { ...dedicationData, id: newId };
      setDedications(prev => [newDed, ...prev]);
      logActivity('created', `Dedicación creada para C.I. ${dedicationData.teacher_cdi}: ${dedicationData.name}`, dedicationData.teacher_cdi);
    }
    showToast('Dedicación y carga horaria actualizada.');
  };

  // Site assignment
  const saveSiteAssignment = (siteData: Omit<SiteAssignment, 'id'> & { id?: number }) => {
    if (siteData.id) {
      setSites(prev => prev.map(s => s.id === siteData.id ? (siteData as SiteAssignment) : s));
      logActivity('updated', `Asignación de cátedra actualizada para C.I. ${siteData.teacher_cdi}`, siteData.teacher_cdi);
    } else {
      const newId = sites.length > 0 ? Math.max(...sites.map(s => s.id)) + 1 : 1;
      const newSite: SiteAssignment = { ...siteData, id: newId };
      setSites(prev => [newSite, ...prev]);
      logActivity('created', `Nueva asignación de cátedra para C.I. ${siteData.teacher_cdi} en ${siteData.sede_nombre}`, siteData.teacher_cdi);
    }
    showToast('Asignación de sede y cátedra guardada.');
  };

  const toggleSiteActive = (id: number) => {
    setSites(prev => prev.map(s => {
      if (s.id === id) {
        const nextState = !s.is_active;
        logActivity('updated', `Estado de asignación ID ${id} cambiado a ${nextState ? 'Activo' : 'Inactivo'}`, s.teacher_cdi);
        return { ...s, is_active: nextState };
      }
      return s;
    }));
  };

  const deleteSiteAssignment = (id: number) => {
    setSites(prev => prev.filter(s => s.id !== id));
    logActivity('deleted', `Asignación de cátedra eliminada ID: ${id}`);
    showToast('Asignación eliminada.');
  };

  // Permissions
  const addPermission = (permissionData: Omit<TeacherPermission, 'id' | 'created_at'>) => {
    const newId = permissions.length > 0 ? Math.max(...permissions.map(p => p.id)) + 1 : 1;
    const newPerm: TeacherPermission = {
      ...permissionData,
      id: newId,
      created_at: new Date().toISOString().split('T')[0]
    };
    setPermissions(prev => [newPerm, ...prev]);
    logActivity('created', `Permiso solicitado: ${newPerm.type} (Memo: ${newPerm.memo_number}) para C.I. ${newPerm.teacher_cdi}`, newPerm.teacher_cdi);
    showToast(`Solicitud de permiso ${newPerm.type} creada.`);
  };

  const updatePermissionStatus = (id: number, status: PermissionStatus) => {
    setPermissions(prev => prev.map(p => {
      if (p.id === id) {
        logActivity(status === 'approved' ? 'approved' : 'rejected', `Permiso ${p.memo_number} (${p.type}) marcado como ${status.toUpperCase()}`, p.teacher_cdi);
        return { ...p, status };
      }
      return p;
    }));
    showToast(`Permiso actualizado a estado: ${status === 'approved' ? 'Aprobado' : status === 'rejected' ? 'Rechazado' : 'Pendiente'}`);
  };

  const deletePermission = (id: number) => {
    const perm = permissions.find(p => p.id === id);
    setPermissions(prev => prev.filter(p => p.id !== id));
    if (perm) {
      logActivity('deleted', `Permiso ${perm.memo_number} eliminado`, perm.teacher_cdi);
    }
    showToast('Permiso eliminado.');
  };

  // Reports
  const addReport = (reportData: Omit<ReportItem, 'id' | 'created_at'>) => {
    const newId = reports.length > 0 ? Math.max(...reports.map(r => r.id)) + 1 : 1;
    const newRep: ReportItem = {
      ...reportData,
      id: newId,
      created_at: new Date().toISOString().replace('T', ' ').substring(0, 16)
    };
    setReports(prev => [newRep, ...prev]);
    logActivity('created', `Emisión de reporte: ${newRep.typeReport} (Memo: ${newRep.memoNumber})`, newRep.memoNumber);
    showToast(`Reporte ${newRep.memoNumber} generado exitosamente.`);
  };

  const deleteReport = (id: number) => {
    const rep = reports.find(r => r.id === id);
    setReports(prev => prev.filter(r => r.id !== id));
    if (rep) {
      logActivity('deleted', `Reporte ${rep.memoNumber} eliminado`, rep.memoNumber);
    }
    showToast('Reporte eliminado.');
  };

  // User management
  const toggleUserApproved = (id: number) => {
    setUsers(prev => prev.map(u => {
      if (u.id === id) {
        const next = !u.is_approved;
        logActivity('approved', `Cuenta de usuario ${u.email} ${next ? 'Aprobada' : 'Pendiente de Aprobación'}`);
        return { ...u, is_approved: next };
      }
      return u;
    }));
  };

  const toggleUserActive = (id: number) => {
    setUsers(prev => prev.map(u => {
      if (u.id === id) {
        const next = !u.is_active;
        logActivity('updated', `Cuenta de usuario ${u.email} ${next ? 'Activada' : 'Desactivada'}`);
        return { ...u, is_active: next };
      }
      return u;
    }));
  };

  const updateUserRoles = (id: number, newRoles: UserRole[]) => {
    setUsers(prev => prev.map(u => {
      if (u.id === id) {
        logActivity('updated', `Roles de ${u.email} modificados a: ${newRoles.join(', ')}`);
        return { ...u, roles: newRoles };
      }
      return u;
    }));
    showToast('Roles de usuario actualizados.');
  };

  const addUser = (userData: Omit<User, 'id' | 'created_at'>) => {
    const newId = users.length > 0 ? Math.max(...users.map(u => u.id)) + 1 : 1;
    const newUser: User = {
      ...userData,
      id: newId,
      created_at: new Date().toISOString().replace('T', ' ').substring(0, 16)
    };
    setUsers(prev => [newUser, ...prev]);
    logActivity('created', `Nuevo usuario creado: ${newUser.name} (${newUser.email})`);
    showToast(`Usuario ${newUser.name} registrado con éxito.`);
  };

  const updateUser = (id: number, updatedFields: Partial<User>) => {
    setUsers(prev => prev.map(u => {
      if (u.id === id) {
        const updated = { ...u, ...updatedFields };
        logActivity('updated', `Usuario ${u.email} actualizado`);
        return updated;
      }
      return u;
    }));
    showToast('Usuario actualizado correctamente.');
  };

  const deleteUser = (id: number) => {
    const u = users.find(user => user.id === id);
    setUsers(prev => prev.filter(user => user.id !== id));
    if (u) {
      logActivity('deleted', `Usuario ${u.name} (${u.email}) eliminado`);
    }
    showToast('Usuario eliminado.');
  };

  // CSV Import / Export
  const importCsv = (type: 'teachers' | 'categories' | 'dedications' | 'sites' | 'users', csvText: string) => {
    try {
      const lines = csvText.trim().split('\n').map(l => l.trim()).filter(l => l.length > 0);
      if (lines.length <= 1) {
        return { success: false, count: 0, error: 'El archivo CSV está vacío o solo contiene la cabecera.' };
      }

      // Check delimiter (semicolon or comma)
      const delimiter = lines[0].includes(';') ? ';' : ',';
      const headers = lines[0].split(delimiter).map(h => h.trim().replace(/^["']|["']$/g, ''));

      let importedCount = 0;

      if (type === 'teachers') {
        const newTeachers: Teacher[] = [];
        for (let i = 1; i < lines.length; i++) {
          const values = lines[i].split(delimiter).map(v => v.trim().replace(/^["']|["']$/g, ''));
          if (values.length < 5) continue;
          
          // Map headers to fields
          const row: any = {};
          headers.forEach((h, idx) => {
            row[h] = values[idx] || '';
          });

          newTeachers.push({
            id: parseInt(row.id) || (Date.now() + i),
            name: row.name || 'Docente',
            surName: row.surName || 'Sin Apellido',
            cdi: row.cdi || `V-${Math.floor(10000000 + Math.random() * 90000000)}`,
            genre: (row.genre === 'F' || row.genre === 'M') ? row.genre : 'M',
            phone: row.phone || '',
            email: row.email || `docente${i}@unerg.edu.ve`,
            birthDate: row.birthDate || '1985-01-01',
            datePromotion: row.datePromotion || '2015-01-01',
            asignaturePromotion: row.asignaturePromotion || 'Cátedra Regular',
            user_email: row.user_email || row.email || '',
            sede_nombre: row.sede_nombre || 'Sede Central/San Juan de los Morros',
            area_nombre: row.area_nombre || 'Ingeniería de sistemas',
            programa_nombre: row.programa_nombre || 'Ingeniería en Informática'
          });
          importedCount++;
        }

        if (newTeachers.length > 0) {
          // Merge by cdi
          setTeachers(prev => {
            const existingCdis = new Set(newTeachers.map(t => t.cdi));
            return [...newTeachers, ...prev.filter(t => !existingCdis.has(t.cdi))];
          });
        }
      } else if (type === 'categories') {
        const newCats: Category[] = [];
        for (let i = 1; i < lines.length; i++) {
          const values = lines[i].split(delimiter).map(v => v.trim().replace(/^["']|["']$/g, ''));
          if (values.length < 3) continue;
          const row: any = {};
          headers.forEach((h, idx) => { row[h] = values[idx] || ''; });

          newCats.push({
            id: parseInt(row.id) || (Date.now() + i),
            teacher_cdi: row.teacher_cdi || '',
            preTitle: row.preTitle || 'Licenciado(a)',
            lastTitle: row.lastTitle || '',
            current_category: (row.current_category as CategoryLevel) || 'Instructor',
            instructor: row.instructor || undefined,
            asistente: row.asistente || undefined,
            agregado: row.agregado || undefined,
            asociado: row.asociado || undefined,
            titular: row.titular || undefined,
            disable_assistant_rule: row.disable_assistant_rule === '1' || row.disable_assistant_rule === 'true',
            info: row.info || ''
          });
          importedCount++;
        }
        if (newCats.length > 0) {
          setCategories(prev => {
            const cdis = new Set(newCats.map(c => c.teacher_cdi));
            return [...newCats, ...prev.filter(c => !cdis.has(c.teacher_cdi))];
          });
        }
      } else if (type === 'dedications') {
        const newDeds: Dedication[] = [];
        for (let i = 1; i < lines.length; i++) {
          const values = lines[i].split(delimiter).map(v => v.trim().replace(/^["']|["']$/g, ''));
          if (values.length < 3) continue;
          const row: any = {};
          headers.forEach((h, idx) => { row[h] = values[idx] || ''; });

          newDeds.push({
            id: parseInt(row.id) || (Date.now() + i),
            teacher_cdi: row.teacher_cdi || '',
            name: (row.name as any) || 'Tiempo Completo',
            hours: parseInt(row.hours) || 30,
            director: row.director || '',
            studentNumber: parseInt(row.studentNumber) || 30,
            studentHours: parseInt(row.studentHours) || 4,
            info: row.info || ''
          });
          importedCount++;
        }
        if (newDeds.length > 0) {
          setDedications(prev => {
            const cdis = new Set(newDeds.map(d => d.teacher_cdi));
            return [...newDeds, ...prev.filter(d => !cdis.has(d.teacher_cdi))];
          });
        }
      } else if (type === 'sites') {
        const newSites: SiteAssignment[] = [];
        for (let i = 1; i < lines.length; i++) {
          const values = lines[i].split(delimiter).map(v => v.trim().replace(/^["']|["']$/g, ''));
          if (values.length < 4) continue;
          const row: any = {};
          headers.forEach((h, idx) => { row[h] = values[idx] || ''; });

          newSites.push({
            id: parseInt(row.id) || (Date.now() + i),
            teacher_cdi: row.teacher_cdi || '',
            sede_nombre: row.sede_nombre || 'Sede Central/San Juan de los Morros',
            area_nombre: row.area_nombre || 'Ingeniería de sistemas',
            programa_nombre: row.programa_nombre || 'Ingeniería en Informática',
            uc: parseInt(row.uc) || 3,
            weekHours: parseInt(row.weekHours) || 6,
            sections: parseInt(row.sections) || 2,
            info: row.info || '',
            is_active: row.is_active === '1' || row.is_active === 'true' || row.is_active === undefined
          });
          importedCount++;
        }
        if (newSites.length > 0) {
          setSites(prev => [...newSites, ...prev]);
        }
      } else if (type === 'users') {
        const newUsers: User[] = [];
        for (let i = 1; i < lines.length; i++) {
          const values = lines[i].split(delimiter).map(v => v.trim().replace(/^["']|["']$/g, ''));
          if (values.length < 3) continue;
          const row: any = {};
          headers.forEach((h, idx) => { row[h] = values[idx] || ''; });

          const rawRoles = (row.rol_name || row.roles || 'teacher').split(',').map((r: string) => r.trim()) as UserRole[];
          newUsers.push({
            id: parseInt(row.id) || (Date.now() + i),
            name: row.name || 'Usuario',
            email: row.email || `user${i}@sigedor.com`,
            sede_id: row.sede_id || 1,
            sede_nombre: row.sede_nombre || 'Sede Central/San Juan de los Morros',
            area_id: row.area_id || 1,
            area_nombre: row.area_nombre || 'Ingeniería de sistemas',
            roles: rawRoles,
            is_active: row.is_active === '1' || row.is_active === 'true',
            is_approved: row.is_approved === '1' || row.is_approved === 'true',
            created_at: new Date().toISOString().substring(0, 16)
          });
          importedCount++;
        }
        if (newUsers.length > 0) {
          setUsers(prev => {
            const emails = new Set(newUsers.map(u => u.email));
            return [...newUsers, ...prev.filter(u => !emails.has(u.email))];
          });
        }
      }

      logActivity('imported', `Importación CSV completada: ${importedCount} registros de ${type}`, type);
      showToast(`Importación CSV exitosa: ${importedCount} registros incorporados.`);
      return { success: true, count: importedCount };
    } catch (err: any) {
      console.error('Error parsing CSV', err);
      return { success: false, count: 0, error: err.message || 'Error de sintaxis en el archivo CSV' };
    }
  };

  const exportCsv = (type: 'teachers' | 'categories' | 'dedications' | 'sites' | 'users') => {
    let header = '';
    let rows: string[] = [];

    if (type === 'teachers') {
      header = 'id;name;surName;cdi;genre;phone;email;birthDate;datePromotion;asignaturePromotion;user_email;sede_nombre;area_nombre;programa_nombre';
      rows = teachers.map(t => `${t.id};${t.name};${t.surName};${t.cdi};${t.genre};${t.phone};${t.email};${t.birthDate};${t.datePromotion};${t.asignaturePromotion};${t.user_email};${t.sede_nombre};${t.area_nombre};${t.programa_nombre}`);
    } else if (type === 'categories') {
      header = 'id;teacher_cdi;preTitle;lastTitle;current_category;instructor;asistente;agregado;asociado;titular;disable_assistant_rule;info';
      rows = categories.map(c => `${c.id};${c.teacher_cdi};${c.preTitle};${c.lastTitle};${c.current_category};${c.instructor || ''};${c.asistente || ''};${c.agregado || ''};${c.asociado || ''};${c.titular || ''};${c.disable_assistant_rule ? 1 : 0};${c.info || ''}`);
    } else if (type === 'dedications') {
      header = 'id;teacher_cdi;name;hours;director;studentNumber;studentHours;info';
      rows = dedications.map(d => `${d.id};${d.teacher_cdi};${d.name};${d.hours};${d.director || ''};${d.studentNumber};${d.studentHours};${d.info || ''}`);
    } else if (type === 'sites') {
      header = 'id;teacher_cdi;sede_nombre;area_nombre;programa_nombre;uc;weekHours;sections;info;is_active';
      rows = sites.map(s => `${s.id};${s.teacher_cdi};${s.sede_nombre};${s.area_nombre};${s.programa_nombre};${s.uc};${s.weekHours};${s.sections};${s.info || ''};${s.is_active ? 1 : 0}`);
    } else if (type === 'users') {
      header = 'id;name;email;sede_nombre;area_nombre;rol_name;is_active;is_approved';
      rows = users.map(u => `${u.id};${u.name};${u.email};${u.sede_nombre || ''};${u.area_nombre || ''};${u.roles.join(',')};${u.is_active ? 1 : 0};${u.is_approved ? 1 : 0}`);
    }

    const csvContent = [header, ...rows].join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', `${type}_sigedor_${new Date().toISOString().split('T')[0]}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

    logActivity('exported', `Exportación CSV realizada para ${type}`);
    showToast(`Dataset ${type}.csv descargado correctamente.`);
  };

  const resetToInitialData = () => {
    localStorage.removeItem('sigedor_users');
    localStorage.removeItem('sigedor_current_user');
    localStorage.removeItem('sigedor_teachers');
    localStorage.removeItem('sigedor_categories');
    localStorage.removeItem('sigedor_dedications');
    localStorage.removeItem('sigedor_sites');
    localStorage.removeItem('sigedor_permissions');
    localStorage.removeItem('sigedor_reports');
    localStorage.removeItem('sigedor_activity');

    setUsers(INITIAL_USERS);
    setCurrentUser(INITIAL_USERS[0]);
    setTeachers(INITIAL_TEACHERS);
    setCategories(INITIAL_CATEGORIES);
    setDedications(INITIAL_DEDICATIONS);
    setSites(INITIAL_SITES);
    setPermissions(INITIAL_PERMISSIONS);
    setReports(INITIAL_REPORTS);
    setActivityLogs(INITIAL_ACTIVITY_LOGS);

    showToast('Datos del sistema restaurados a la configuración inicial.', 'info');
  };

  return (
    <AppContext.Provider
      value={{
        currentUser,
        setCurrentUser,
        users,
        teachers,
        categories,
        dedications,
        sites,
        permissions,
        reports,
        sedes,
        areas,
        programas,
        activityLogs,
        activeTab,
        setActiveTab,
        toast,
        showToast,
        addTeacher,
        updateTeacher,
        deleteTeacher,
        saveCategory,
        saveDedication,
        saveSiteAssignment,
        toggleSiteActive,
        deleteSiteAssignment,
        addPermission,
        updatePermissionStatus,
        deletePermission,
        addReport,
        deleteReport,
        toggleUserApproved,
        toggleUserActive,
        updateUserRoles,
        addUser,
        updateUser,
        deleteUser,
        logActivity,
        importCsv,
        exportCsv,
        resetToInitialData,
      }}
    >
      {children}
    </AppContext.Provider>
  );
};

export const useApp = () => {
  const context = useContext(AppContext);
  if (!context) {
    throw new Error('useApp must be used within an AppProvider');
  }
  return context;
};
