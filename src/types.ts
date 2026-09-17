export type UserRole = 'admin' | 'area_manager' | 'teacher';

export interface User {
  id: number;
  name: string;
  email: string;
  sede_nombre: string;
  area_nombre: string;
  roles: UserRole[];
  is_active: boolean;
  is_approved: boolean;
  avatar?: string;
}

export interface Teacher {
  id: number;
  name: string;
  surName: string;
  cdi: string;
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
  category_id?: number;
  dedication_id?: number;
  site_id?: number;
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

export interface Dedication {
  id: number;
  teacher_cdi: string;
  name: DedicationType;
  hours: number;
  director?: string;
  studentNumber: number;
  studentHours: number;
  info?: string;
}

export interface Site {
  id: number;
  teacher_cdi: string;
  sede_nombre: string;
  area_nombre: string;
  programa_nombre: string;
  uc: number;
  weekHours: number;
  sections: number;
  info: string;
  is_active: boolean;
}

export type PermissionType = 
  | 'Año Sabático'
  | 'Comisión de Servicio'
  | 'Incapacidad Médica'
  | 'Licencia Médica'
  | 'Prórroga de Estudios'
  | 'Permiso por Cuido'
  | 'Permiso Especial'
  | 'Permiso Académico'
  | 'Permiso No Remunerado';

export type PermissionStatus = 'Aprobado' | 'Pendiente' | 'Rechazado';

export interface PermissionTeacher {
  id: number;
  teacher_cdi: string;
  teacher_name?: string;
  type: PermissionType;
  reason: string;
  start_date: string;
  end_date: string;
  semester: string;
  status: PermissionStatus;
  observations?: string;
}

export type ReportType = 
  | 'Constancia de Trabajo'
  | 'Expediente Curricular Individual'
  | 'Memorando de Carga y Dedicación'
  | 'Reporte Institucional de Escalafón';

export interface Report {
  id: number;
  verification_code: string;
  teacher_cdi: string;
  teacher_name?: string;
  memoNumber: string;
  typeReport: ReportType;
  status: 'Vigente' | 'Verificado' | 'Archivado';
  report?: string;
  email?: string;
  info?: string;
  sede_nombre?: string;
  area_nombre?: string;
  created_at: string;
  created_by?: string;
}

export interface Sede {
  id: number;
  nombre: string;
  estado: string;
}

export interface Area {
  id: number;
  nombre: string;
  sede_id?: number;
}

export interface Programa {
  id: number;
  nombre: string;
  area_id?: number;
}
