export type UserRole = 'admin' | 'area_manager' | 'teacher';

export interface User {
  id: number;
  name: string;
  email: string;
  password?: string;
  sede_id: number | string;
  sede_nombre?: string;
  area_id: number | string;
  area_nombre?: string;
  roles: UserRole[];
  is_active: boolean;
  is_approved: boolean;
  created_at?: string;
}

export interface Teacher {
  id: number;
  cdi: string;
  name: string;
  surName: string;
  genre: 'M' | 'F';
  phone: string;
  email: string;
  birthDate: string;
  datePromotion: string;
  asignaturePromotion: string;
  user_email: string;
  sede_nombre: string;
  area_nombre: string;
  programa_nombre: string;
  sede_id?: number;
  area_id?: number;
  programa_id?: number;
  user_id?: number;
}

export type CategoryLevel = 'Instructor' | 'Asistente' | 'Agregado' | 'Asociado' | 'Titular';

export interface Category {
  id: number;
  teacher_cdi: string;
  preTitle: string;
  lastTitle: string;
  current_category: CategoryLevel;
  instructor?: string;
  asistente?: string;
  agregado?: string;
  asociado?: string;
  titular?: string;
  disable_assistant_rule: boolean;
  info?: string;
}

export type DedicationType = 'Tiempo Convencional' | 'Medio Tiempo' | 'Tiempo Completo' | 'Exclusiva';

export type DirectorPosition = 'Coordinador' | 'Jefe de Departamento' | 'Decano' | 'Director' | 'Sub-Director' | '';

export interface Dedication {
  id: number;
  teacher_cdi: string;
  name: DedicationType;
  hours: number;
  director: DirectorPosition;
  studentNumber: number;
  studentHours: number;
  info?: string;
}

export interface SiteAssignment {
  id: number;
  teacher_cdi: string;
  sede_nombre: string;
  area_nombre: string;
  programa_nombre: string;
  uc: number;
  weekHours: number;
  sections: number;
  info?: string;
  is_active: boolean;
}

export type PermissionType = 
  | 'Año Sabático'
  | 'Comisión de Servicio'
  | 'Prórroga de Comisión'
  | 'Incapacidad Médica'
  | 'Permiso por Cuido'
  | 'Permiso Especial No Remunerado'
  | 'Beca o Formación Doctoral';

export type DurationType = 'semestral' | 'anual' | 'temporal';

export type PermissionStatus = 'pending' | 'approved' | 'rejected';

export interface TeacherPermission {
  id: number;
  teacher_cdi: string;
  memo_number: string;
  type: PermissionType;
  duration_type: DurationType;
  start_date: string;
  end_date: string;
  status: PermissionStatus;
  is_paid: boolean;
  description: string;
  created_at: string;
}

export type ReportType = 
  | 'Constancia de Trabajo'
  | 'Informe de Dedicación'
  | 'Informe de Escalafón'
  | 'Memorando Administrativo';

export interface ReportItem {
  id: number;
  memoNumber: string;
  teacher_cdi: string;
  typeReport: ReportType;
  email?: string;
  sede_nombre: string;
  area_nombre: string;
  report: string;
  info?: string;
  created_at: string;
}

export interface Sede {
  id: number;
  nombre: string;
}

export interface Area {
  id: number;
  nombre: string;
}

export interface Programa {
  id: number;
  nombre: string;
}

export interface ActivityLog {
  id: string;
  user_name: string;
  user_email: string;
  event: 'created' | 'updated' | 'deleted' | 'approved' | 'rejected' | 'exported' | 'imported' | 'auth';
  description: string;
  target_subject?: string;
  timestamp: string;
}
