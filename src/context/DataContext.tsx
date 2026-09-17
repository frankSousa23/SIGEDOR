import React, { createContext, useContext, useState, useMemo } from 'react';
import {
  Teacher,
  Category,
  Dedication,
  Site,
  PermissionTeacher,
  Report,
  CategoryLevel,
  User,
} from '../types';
import {
  INITIAL_TEACHERS,
  INITIAL_CATEGORIES,
  INITIAL_DEDICATIONS,
  INITIAL_SITES,
  INITIAL_PERMISSIONS,
  INITIAL_REPORTS,
  INITIAL_USERS,
  INITIAL_SEDES,
  INITIAL_AREAS,
  INITIAL_PROGRAMAS,
} from '../data/initialData';
import { useAuth } from './AuthContext';

interface DataContextType {
  teachers: Teacher[];
  categories: Category[];
  dedications: Dedication[];
  sites: Site[];
  permissions: PermissionTeacher[];
  reports: Report[];
  usersList: User[];
  sedes: typeof INITIAL_SEDES;
  areas: typeof INITIAL_AREAS;
  programas: typeof INITIAL_PROGRAMAS;

  // Filtered views by user scope
  filteredTeachers: Teacher[];
  filteredSites: Site[];
  filteredReports: Report[];
  filteredPermissions: PermissionTeacher[];

  // CRUD & Business Logic
  addTeacher: (teacher: Omit<Teacher, 'id'>) => void;
  updateTeacher: (id: number, teacher: Partial<Teacher>) => void;
  deleteTeacher: (id: number) => void;

  updateCategory: (teacherCdi: string, data: Partial<Category>) => void;
  promoteTeacher: (teacherCdi: string, nextCategory: CategoryLevel, date: string) => void;

  updateDedication: (teacherCdi: string, data: Partial<Dedication>) => void;

  addSite: (site: Omit<Site, 'id'>) => void;
  updateSite: (id: number, data: Partial<Site>) => void;
  deleteSite: (id: number) => void;

  addPermission: (permission: Omit<PermissionTeacher, 'id'>) => void;
  updatePermissionStatus: (id: number, status: 'Aprobado' | 'Rechazado', observations?: string) => void;

  generateReport: (data: {
    teacher_cdi: string;
    typeReport: Report['typeReport'];
    memoNumber?: string;
    report?: string;
  }) => Report;

  toggleUserActive: (userId: number) => void;
  toggleUserApproved: (userId: number) => void;
  updateUserRole: (userId: number, roles: User['roles']) => void;
}

const DataContext = createContext<DataContextType | undefined>(undefined);

export const DataProvider: React.FC<{ children: React.ReactNode }> = ({ children }) => {
  const { currentUser, isSuperAdmin, isAreaManager, isTeacher } = useAuth();

  const [teachers, setTeachers] = useState<Teacher[]>(INITIAL_TEACHERS);
  const [categories, setCategories] = useState<Category[]>(INITIAL_CATEGORIES);
  const [dedications, setDedications] = useState<Dedication[]>(INITIAL_DEDICATIONS);
  const [sites, setSites] = useState<Site[]>(INITIAL_SITES);
  const [permissions, setPermissions] = useState<PermissionTeacher[]>(INITIAL_PERMISSIONS);
  const [reports, setReports] = useState<Report[]>(INITIAL_REPORTS);
  const [usersList, setUsersList] = useState<User[]>(INITIAL_USERS);

  // Multi-tenant scope filtering
  const filteredTeachers = useMemo(() => {
    if (isSuperAdmin) return teachers;
    if (isAreaManager) {
      return teachers.filter(
        t => t.sede_nombre === currentUser.sede_nombre || t.area_nombre === currentUser.area_nombre
      );
    }
    if (isTeacher) {
      return teachers.filter(t => t.email.toLowerCase() === currentUser.email.toLowerCase());
    }
    return teachers;
  }, [teachers, isSuperAdmin, isAreaManager, isTeacher, currentUser]);

  const filteredSites = useMemo(() => {
    if (isSuperAdmin) return sites;
    if (isAreaManager) {
      return sites.filter(
        s => s.sede_nombre === currentUser.sede_nombre || s.area_nombre === currentUser.area_nombre
      );
    }
    if (isTeacher) {
      const myTeacher = teachers.find(t => t.email.toLowerCase() === currentUser.email.toLowerCase());
      return myTeacher ? sites.filter(s => s.teacher_cdi === myTeacher.cdi) : [];
    }
    return sites;
  }, [sites, isSuperAdmin, isAreaManager, isTeacher, currentUser, teachers]);

  const filteredReports = useMemo(() => {
    if (isSuperAdmin) return reports;
    if (isAreaManager) {
      return reports.filter(
        r => r.sede_nombre === currentUser.sede_nombre || r.area_nombre === currentUser.area_nombre
      );
    }
    if (isTeacher) {
      const myTeacher = teachers.find(t => t.email.toLowerCase() === currentUser.email.toLowerCase());
      return myTeacher ? reports.filter(r => r.teacher_cdi === myTeacher.cdi) : [];
    }
    return reports;
  }, [reports, isSuperAdmin, isAreaManager, isTeacher, currentUser, teachers]);

  const filteredPermissions = useMemo(() => {
    if (isSuperAdmin) return permissions;
    if (isAreaManager) {
      // Find teachers in this area
      const cdis = new Set(filteredTeachers.map(t => t.cdi));
      return permissions.filter(p => cdis.has(p.teacher_cdi));
    }
    if (isTeacher) {
      const myTeacher = teachers.find(t => t.email.toLowerCase() === currentUser.email.toLowerCase());
      return myTeacher ? permissions.filter(p => p.teacher_cdi === myTeacher.cdi) : [];
    }
    return permissions;
  }, [permissions, filteredTeachers, isSuperAdmin, isAreaManager, isTeacher, currentUser, teachers]);

  // Actions
  const addTeacher = (newTeacher: Omit<Teacher, 'id'>) => {
    const nextId = Math.max(...teachers.map(t => t.id), 0) + 1;
    const teacher: Teacher = { ...newTeacher, id: nextId };
    setTeachers(prev => [teacher, ...prev]);

    // Create companion Category and Dedication
    const catId = Math.max(...categories.map(c => c.id), 0) + 1;
    const newCat: Category = {
      id: catId,
      teacher_cdi: teacher.cdi,
      preTitle: 'Licenciado / Ingeniero',
      lastTitle: '',
      current_category: 'Instructor',
      instructor: teacher.datePromotion || new Date().toISOString().split('T')[0],
      disable_assistant_rule: false,
      info: 'Nuevo ingreso docente',
    };
    setCategories(prev => [newCat, ...prev]);

    const dedId = Math.max(...dedications.map(d => d.id), 0) + 1;
    const newDed: Dedication = {
      id: dedId,
      teacher_cdi: teacher.cdi,
      name: 'Tiempo Convencional',
      hours: 12,
      studentNumber: 25,
      studentHours: 2,
      info: 'Carga horaria inicial',
    };
    setDedications(prev => [newDed, ...prev]);
  };

  const updateTeacher = (id: number, data: Partial<Teacher>) => {
    setTeachers(prev => prev.map(t => (t.id === id ? { ...t, ...data } : t)));
  };

  const deleteTeacher = (id: number) => {
    const teacher = teachers.find(t => t.id === id);
    if (!teacher) return;
    setTeachers(prev => prev.filter(t => t.id !== id));
    setCategories(prev => prev.filter(c => c.teacher_cdi !== teacher.cdi));
    setDedications(prev => prev.filter(d => d.teacher_cdi !== teacher.cdi));
    setSites(prev => prev.filter(s => s.teacher_cdi !== teacher.cdi));
  };

  const updateCategory = (teacherCdi: string, data: Partial<Category>) => {
    setCategories(prev =>
      prev.map(c => (c.teacher_cdi === teacherCdi ? { ...c, ...data } : c))
    );
  };

  const promoteTeacher = (teacherCdi: string, nextCategory: CategoryLevel, date: string) => {
    setCategories(prev =>
      prev.map(c => {
        if (c.teacher_cdi === teacherCdi) {
          const updated: Category = {
            ...c,
            current_category: nextCategory,
          };
          if (nextCategory === 'Instructor') updated.instructor = date;
          if (nextCategory === 'Asistente') updated.asistente = date;
          if (nextCategory === 'Agregado') updated.agregado = date;
          if (nextCategory === 'Asociado') updated.asociado = date;
          if (nextCategory === 'Titular') updated.titular = date;
          return updated;
        }
        return c;
      })
    );
  };

  const updateDedication = (teacherCdi: string, data: Partial<Dedication>) => {
    setDedications(prev =>
      prev.map(d => (d.teacher_cdi === teacherCdi ? { ...d, ...data } : d))
    );
  };

  const addSite = (newSite: Omit<Site, 'id'>) => {
    const nextId = Math.max(...sites.map(s => s.id), 0) + 1;
    setSites(prev => [{ ...newSite, id: nextId }, ...prev]);
  };

  const updateSite = (id: number, data: Partial<Site>) => {
    setSites(prev => prev.map(s => (s.id === id ? { ...s, ...data } : s)));
  };

  const deleteSite = (id: number) => {
    setSites(prev => prev.filter(s => s.id !== id));
  };

  const addPermission = (newPermission: Omit<PermissionTeacher, 'id'>) => {
    const nextId = Math.max(...permissions.map(p => p.id), 0) + 1;
    const teacher = teachers.find(t => t.cdi === newPermission.teacher_cdi);
    setPermissions(prev => [
      {
        ...newPermission,
        id: nextId,
        teacher_name: teacher ? `${teacher.name} ${teacher.surName}` : undefined,
      },
      ...prev,
    ]);
  };

  const updatePermissionStatus = (
    id: number,
    status: 'Aprobado' | 'Rechazado',
    observations?: string
  ) => {
    setPermissions(prev =>
      prev.map(p =>
        p.id === id ? { ...p, status, observations: observations || p.observations } : p
      )
    );
  };

  const generateReport = ({
    teacher_cdi,
    typeReport,
    memoNumber,
    report,
  }: {
    teacher_cdi: string;
    typeReport: Report['typeReport'];
    memoNumber?: string;
    report?: string;
  }): Report => {
    const teacher = teachers.find(t => t.cdi === teacher_cdi);
    const nextId = Math.max(...reports.map(r => r.id), 0) + 1;
    const randomCode = Math.floor(1000 + Math.random() * 9000);
    const year = new Date().getFullYear();
    const verification_code = `UNERG-REP-${year}-${randomCode}`;

    const newReport: Report = {
      id: nextId,
      verification_code,
      teacher_cdi,
      teacher_name: teacher ? `${teacher.name} ${teacher.surName}` : 'Docente',
      memoNumber: memoNumber || `MEMO-DOR-${year}-${nextId.toString().padStart(3, '0')}`,
      typeReport,
      status: 'Verificado',
      report: report || `Emisión oficial de ${typeReport} emitida con validez legal e institucional.`,
      email: teacher?.email,
      sede_nombre: teacher?.sede_nombre,
      area_nombre: teacher?.area_nombre,
      created_at: new Date().toISOString().replace('T', ' ').substring(0, 19),
      created_by: currentUser.name,
    };

    setReports(prev => [newReport, ...prev]);
    return newReport;
  };

  const toggleUserActive = (userId: number) => {
    setUsersList(prev =>
      prev.map(u => (u.id === userId ? { ...u, is_active: !u.is_active } : u))
    );
  };

  const toggleUserApproved = (userId: number) => {
    setUsersList(prev =>
      prev.map(u => (u.id === userId ? { ...u, is_approved: !u.is_approved } : u))
    );
  };

  const updateUserRole = (userId: number, roles: User['roles']) => {
    setUsersList(prev => prev.map(u => (u.id === userId ? { ...u, roles } : u)));
  };

  return (
    <DataContext.Provider
      value={{
        teachers,
        categories,
        dedications,
        sites,
        permissions,
        reports,
        usersList,
        sedes: INITIAL_SEDES,
        areas: INITIAL_AREAS,
        programas: INITIAL_PROGRAMAS,
        filteredTeachers,
        filteredSites,
        filteredReports,
        filteredPermissions,
        addTeacher,
        updateTeacher,
        deleteTeacher,
        updateCategory,
        promoteTeacher,
        updateDedication,
        addSite,
        updateSite,
        deleteSite,
        addPermission,
        updatePermissionStatus,
        generateReport,
        toggleUserActive,
        toggleUserApproved,
        updateUserRole,
      }}
    >
      {children}
    </DataContext.Provider>
  );
};

export const useData = () => {
  const context = useContext(DataContext);
  if (!context) {
    throw new Error('useData must be used within a DataProvider');
  }
  return context;
};
