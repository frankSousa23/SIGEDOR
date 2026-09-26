import { DedicationType } from '../types';

export type DedicationCode = 'TCV' | 'MT' | 'TC' | 'DE';

export interface DedicationRule {
  name: DedicationType;
  code: DedicationCode;
  hoursRangeText: string;
  denotation: string;
  allowedHours: number[];
  minHours: number;
  maxHours: number;
  defaultHours: number;
  badgeBg: string;
  badgeText: string;
  badgeBorder: string;
  description: string;
  legalBase: string;
}

export const DEDICATION_RULES: Record<DedicationType, DedicationRule> = {
  'Tiempo Convencional': {
    name: 'Tiempo Convencional',
    code: 'TCV',
    hoursRangeText: '2 a 7 horas',
    denotation: '2-7 TCV',
    allowedHours: [2, 3, 4, 5, 6, 7],
    minHours: 2,
    maxHours: 7,
    defaultHours: 6,
    badgeBg: 'bg-teal-50',
    badgeText: 'text-teal-700',
    badgeBorder: 'border-teal-200',
    description: 'Contratación por horas de clase para cátedras específicas (asignación regular de 2 a 7 horas semanales).',
    legalBase: 'Art. 85 Ley de Universidades / Normativa UNERG de Régimen de Dedicación Docente'
  },
  'Medio Tiempo': {
    name: 'Medio Tiempo',
    code: 'MT',
    hoursRangeText: '18 horas',
    denotation: '18 MT',
    allowedHours: [18],
    minHours: 18,
    maxHours: 18,
    defaultHours: 18,
    badgeBg: 'bg-amber-50',
    badgeText: 'text-amber-700',
    badgeBorder: 'border-amber-200',
    description: 'Media jornada docente orientada a actividades lectivas y de preparación (18 horas semanales).',
    legalBase: 'Art. 86 Ley de Universidades / Estatuto Docente Universitario'
  },
  'Tiempo Completo': {
    name: 'Tiempo Completo',
    code: 'TC',
    hoursRangeText: '30 horas',
    denotation: '30 TC',
    allowedHours: [30],
    minHours: 30,
    maxHours: 30,
    defaultHours: 30,
    badgeBg: 'bg-blue-50',
    badgeText: 'text-blue-700',
    badgeBorder: 'border-blue-200',
    description: 'Jornada académica completa que combina docencia, extensión y gestión académica (30 horas semanales).',
    legalBase: 'Art. 87 Ley de Universidades / Reglamento de Dedicación Docente'
  },
  'Exclusiva': {
    name: 'Exclusiva',
    code: 'DE',
    hoursRangeText: '35 a 36 horas',
    denotation: '35-36 DE',
    allowedHours: [35, 36],
    minHours: 35,
    maxHours: 36,
    defaultHours: 36,
    badgeBg: 'bg-purple-50',
    badgeText: 'text-purple-700',
    badgeBorder: 'border-purple-200',
    description: 'Dedicación universitaria exclusiva integral de docencia, investigación y directivas (35 a 36 horas semanales).',
    legalBase: 'Art. 88 Ley de Universidades / Régimen de Exclusividad Docente'
  }
};

export const getDedicationRule = (name: DedicationType): DedicationRule => {
  return DEDICATION_RULES[name] || DEDICATION_RULES['Tiempo Completo'];
};

export const getDedicationCode = (name: DedicationType): DedicationCode => {
  return getDedicationRule(name).code;
};

export const getDedicationDenotation = (name: DedicationType): string => {
  return getDedicationRule(name).denotation;
};

export const getAllowedHours = (name: DedicationType): number[] => {
  return getDedicationRule(name).allowedHours;
};

export const getDefaultHours = (name: DedicationType): number => {
  return getDedicationRule(name).defaultHours;
};

export const isValidDedicationHours = (name: DedicationType, hours: number): boolean => {
  const rule = getDedicationRule(name);
  return hours >= rule.minHours && hours <= rule.maxHours;
};

export const getDedicationHoursWarning = (name: DedicationType, hours: number): string | null => {
  const rule = getDedicationRule(name);
  if (hours < rule.minHours || hours > rule.maxHours) {
    if (name === 'Tiempo Convencional') {
      return `Asignación fuera de norma: Tiempo Convencional (TCV) debe estar en el rango de 2 a 7 horas semanales (actual: ${hours}h).`;
    }
    if (name === 'Medio Tiempo') {
      return `Asignación fuera de norma: Medio Tiempo (MT) se estipula exactamente en 18 horas semanales (actual: ${hours}h).`;
    }
    if (name === 'Tiempo Completo') {
      return `Asignación fuera de norma: Tiempo Completo (TC) se estipula exactamente en 30 horas semanales (actual: ${hours}h).`;
    }
    if (name === 'Exclusiva') {
      return `Asignación fuera de norma: Dedicación Exclusiva (DE) se estipula entre 35 y 36 horas semanales (actual: ${hours}h).`;
    }
  }
  return null;
};
