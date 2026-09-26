import { describe, it, expect } from 'vitest';
import { 
  DEDICATION_RULES, 
  isValidDedicationHours, 
  getDedicationHoursWarning,
  getDedicationCode,
  getDedicationDenotation,
  getDedicationRule
} from '../src/utils/dedicationRules';
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
  INITIAL_PROGRAMAS
} from '../src/data/initialData';
import { CategoryLevel, DedicationType } from '../src/types';

describe('1. Normativa de Dedicación Horaria UNERG (2-7 TCV, 18 MT, 30 TC, 35-36 DE)', () => {
  it('debe definir con precisión los rangos reglamentarios para cada modalidad', () => {
    expect(DEDICATION_RULES['Tiempo Convencional'].minHours).toBe(2);
    expect(DEDICATION_RULES['Tiempo Convencional'].maxHours).toBe(7);
    expect(DEDICATION_RULES['Tiempo Convencional'].code).toBe('TCV');
    expect(DEDICATION_RULES['Tiempo Convencional'].denotation).toBe('2-7 TCV');

    expect(DEDICATION_RULES['Medio Tiempo'].minHours).toBe(18);
    expect(DEDICATION_RULES['Medio Tiempo'].maxHours).toBe(18);
    expect(DEDICATION_RULES['Medio Tiempo'].code).toBe('MT');
    expect(DEDICATION_RULES['Medio Tiempo'].denotation).toBe('18 MT');

    expect(DEDICATION_RULES['Tiempo Completo'].minHours).toBe(30);
    expect(DEDICATION_RULES['Tiempo Completo'].maxHours).toBe(30);
    expect(DEDICATION_RULES['Tiempo Completo'].code).toBe('TC');
    expect(DEDICATION_RULES['Tiempo Completo'].denotation).toBe('30 TC');

    expect(DEDICATION_RULES['Exclusiva'].minHours).toBe(35);
    expect(DEDICATION_RULES['Exclusiva'].maxHours).toBe(36);
    expect(DEDICATION_RULES['Exclusiva'].code).toBe('DE');
    expect(DEDICATION_RULES['Exclusiva'].denotation).toBe('35-36 DE');
  });

  it('debe validar asignaciones horarias correctas', () => {
    expect(isValidDedicationHours('Tiempo Convencional', 2)).toBe(true);
    expect(isValidDedicationHours('Tiempo Convencional', 4)).toBe(true);
    expect(isValidDedicationHours('Tiempo Convencional', 7)).toBe(true);
    expect(isValidDedicationHours('Medio Tiempo', 18)).toBe(true);
    expect(isValidDedicationHours('Tiempo Completo', 30)).toBe(true);
    expect(isValidDedicationHours('Exclusiva', 35)).toBe(true);
    expect(isValidDedicationHours('Exclusiva', 36)).toBe(true);
  });

  it('debe rechazar y emitir advertencias en asignaciones horarias fuera de norma', () => {
    expect(isValidDedicationHours('Tiempo Convencional', 1)).toBe(false);
    expect(isValidDedicationHours('Tiempo Convencional', 10)).toBe(false);
    expect(isValidDedicationHours('Medio Tiempo', 20)).toBe(false);
    expect(isValidDedicationHours('Tiempo Completo', 35)).toBe(false);
    expect(isValidDedicationHours('Exclusiva', 40)).toBe(false);

    const warn = getDedicationHoursWarning('Tiempo Convencional', 12);
    expect(warn).not.toBeNull();
    expect(warn).toContain('2 a 7 horas');
  });
});

describe('2. Integridad Relacional de la Base de Datos Masiva (30 Docentes y Entidades)', () => {
  it('debe contener exactamente 30 docentes activos distribuidos en el sistema', () => {
    expect(INITIAL_TEACHERS.length).toBe(30);
  });

  it('debe existir una relación 1:1 estricta entre Docente y Escalafón (Categories)', () => {
    expect(INITIAL_CATEGORIES.length).toBe(30);
    INITIAL_TEACHERS.forEach(t => {
      const cat = INITIAL_CATEGORIES.find(c => c.teacher_cdi === t.cdi);
      expect(cat, `El docente V-${t.cdi} debe tener un registro de escalafón`).toBeDefined();
    });
  });

  it('debe existir una relación 1:1 estricta entre Docente y Dedicación Horaria reglamentaria', () => {
    expect(INITIAL_DEDICATIONS.length).toBe(30);
    INITIAL_TEACHERS.forEach(t => {
      const ded = INITIAL_DEDICATIONS.find(d => d.teacher_cdi === t.cdi);
      expect(ded, `El docente V-${t.cdi} debe tener un registro de dedicación`).toBeDefined();
      const valid = isValidDedicationHours(ded!.name, ded!.hours);
      expect(valid, `Las horas de ${ded!.name} (${ded!.hours}h) deben cumplir la norma UNERG`).toBe(true);
    });
  });

  it('debe cubrir las 7 Sedes y 9 Áreas Académicas de la UNERG', () => {
    const sedesPresentes = new Set(INITIAL_TEACHERS.map(t => t.sede_nombre));
    expect(sedesPresentes.size).toBe(7);

    const areasPresentes = new Set(INITIAL_TEACHERS.map(t => t.area_nombre));
    expect(areasPresentes.size).toBe(9);
  });

  it('debe contar con cátedras asignadas activas en las distintas sedes', () => {
    expect(INITIAL_SITES.length).toBeGreaterThanOrEqual(30);
    INITIAL_SITES.forEach(s => {
      expect(s.uc).toBeGreaterThanOrEqual(2);
      expect(s.weekHours).toBeGreaterThanOrEqual(3);
      expect(s.sections).toBeGreaterThanOrEqual(1);
    });
  });
});

describe('3. Escalafón Docente Universitario y Línea de Ascenso', () => {
  const validCategories: CategoryLevel[] = ['Instructor', 'Asistente', 'Agregado', 'Asociado', 'Titular'];

  it('todos los registros de escalafón deben tener categorías válidas', () => {
    INITIAL_CATEGORIES.forEach(c => {
      expect(validCategories).toContain(c.current_category);
      expect(c.preTitle).toBeTruthy();
    });
  });

  it('debe incluir todos los niveles del escalafón representados', () => {
    const categoriesSet = new Set(INITIAL_CATEGORIES.map(c => c.current_category));
    expect(categoriesSet.has('Instructor')).toBe(true);
    expect(categoriesSet.has('Asistente')).toBe(true);
    expect(categoriesSet.has('Agregado')).toBe(true);
    expect(categoriesSet.has('Asociado')).toBe(true);
    expect(categoriesSet.has('Titular')).toBe(true);
  });
});

describe('4. Permisos, Licencias y Años Sabáticos', () => {
  it('debe incluir permisos en estados Aprobado y Pendiente', () => {
    const aprobados = INITIAL_PERMISSIONS.filter(p => p.status === 'Aprobado');
    const pendientes = INITIAL_PERMISSIONS.filter(p => p.status === 'Pendiente');

    expect(aprobados.length).toBeGreaterThan(0);
    expect(pendientes.length).toBeGreaterThan(0);
  });

  it('cada solicitud debe vincular a un docente existente', () => {
    INITIAL_PERMISSIONS.forEach(p => {
      const teacher = INITIAL_TEACHERS.find(t => t.cdi === p.teacher_cdi);
      expect(teacher, `El docente V-${p.teacher_cdi} del permiso ${p.id} debe existir`).toBeDefined();
    });
  });
});

describe('5. Reportes Oficiales y Códigos de Verificación', () => {
  it('los reportes deben tener códigos formales UNERG-REP-YYYY-XXXX', () => {
    expect(INITIAL_REPORTS.length).toBeGreaterThanOrEqual(12);
    INITIAL_REPORTS.forEach(r => {
      expect(r.verification_code).toMatch(/^UNERG-REP-\d{4}-\d{4}$/);
      expect(r.memoNumber).toMatch(/^MEMO-/);
    });
  });
});

describe('6. Seguridad y Control de Acceso Basado en Roles (RBAC)', () => {
  it('debe incluir usuarios con roles admin, area_manager y teacher', () => {
    const hasAdmin = INITIAL_USERS.some(u => u.roles.includes('admin'));
    const hasAreaManager = INITIAL_USERS.some(u => u.roles.includes('area_manager'));
    const hasTeacher = INITIAL_USERS.some(u => u.roles.includes('teacher'));

    expect(hasAdmin).toBe(true);
    expect(hasAreaManager).toBe(true);
    expect(hasTeacher).toBe(true);
  });

  it('debe simular correctamente el alcance de un Jefe de Área', () => {
    const saludManager = INITIAL_USERS.find(u => u.area_nombre === 'Ciencias de la salud');
    expect(saludManager).toBeDefined();

    // In a real area manager filter, only Salud teachers are visible
    const scopedTeachers = INITIAL_TEACHERS.filter(
      t => t.area_nombre === saludManager!.area_nombre || t.sede_nombre === saludManager!.sede_nombre
    );
    expect(scopedTeachers.length).toBeGreaterThan(0);
    expect(scopedTeachers.length).toBeLessThan(INITIAL_TEACHERS.length);
  });

  it('debe simular correctamente el alcance de un Docente Individual', () => {
    const doc = INITIAL_TEACHERS[0];
    const scoped = INITIAL_TEACHERS.filter(t => t.email.toLowerCase() === doc.email.toLowerCase());
    expect(scoped.length).toBe(1);
    expect(scoped[0].cdi).toBe(doc.cdi);
  });
});
