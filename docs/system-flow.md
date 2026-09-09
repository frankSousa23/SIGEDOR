# Flujo de Datos y Ciclo de Vida del Sistema SIGEDOR

En este documento se describe el ciclo de vida de los datos en SIGEDOR: desde el registro inicial de un usuario hasta la emisión y verificación de documentos oficiales en PDF.

---

## 1. Flujo de Usuario y Aprobación de Cuenta

```
┌─────────────────┐       ┌──────────────────────┐       ┌────────────────────────┐
│ Registro / Demo │ ----> │  Aprobación Admin    │ ----> │ Asignación Territorial │
│  @sigedor.com   │       │ (is_approved = true) │       │     (Sede y Área)      │
└─────────────────┘       └──────────────────────┘       └───────────┬────────────┘
                                                                     │
                                                                     ▼
                                                        ┌────────────────────────┐
                                                        │ Creación de Expediente │
                                                        │      en Teachers       │
                                                        └────────────────────────┘
```

1. **Autenticación**: El usuario ingresa con credenciales bajo el dominio corporativo `@sigedor.com`.
2. **Validación de Estado**: El sistema comprueba que `is_active` e `is_approved` sean verdaderos. Si la cuenta no está aprobada, el acceso al panel es rechazado.
3. **Asignación de Rol**: Un Administrador otorga el rol (`admin`, `area_manager` o `teacher`) y define la filiación territorial (`sede_id`, `area_id`).
4. **Vinculación con Expediente**: Al crear el registro en `TeacherResource`, la selección del usuario autocompleta los datos personales y vincula `user_id`.

---

## 2. Flujo de Escalafón, Dedicación y Asignación de Cátedra

SIGEDOR sincroniza de forma atómica y bidireccional los componentes académicos del docente mediante **Hooks Eloquent (`booted`)**:

```
        ┌────────────────────────────────────────────────────────┐
        │               Expediente Docente (Teacher)             │
        │      (category_id | dedication_id | site_id)           │
        └───────▲───────────────────▲────────────────────▲───────┘
                │                   │                    │
          [booted sync]       [booted sync]        [booted sync]
                │                   │                    │
        ┌───────┴──────┐    ┌───────┴──────┐     ┌───────┴──────┐
        │   Category   │    │  Dedication  │     │     Site     │
        │ (Escalafón)  │    │(Carga Horas) │     │ (Asignación) │
        └──────────────┘    └──────────────┘     └──────────────┘
```

1. **Gestión de Escalafón (`Category`)**:
   - Se registra el historial de ascensos o títulos académicos.
   - El sistema calcula la `current_category` aplicando reglas de ascenso directo (Especialidad/Maestría -> Asistente; Doctorado -> Agregado).
   - El hook `saved` actualiza automáticamente `teachers.category_id`.
2. **Gestión de Dedicación Horaria (`Dedication`)**:
   - Se selecciona la modalidad (TCV, MT, TC, EX) y se validan las horas reglamentarias UNERG.
   - El hook `saved` actualiza automáticamente `teachers.dedication_id`.
3. **Asignación de Cátedra (`Site`)**:
   - Se asignan las unidades de crédito, horas semanales y secciones.
   - El hook `saved` sincroniza `teachers.site_id`, `sede_id`, `area_id` y `programa_id`.

---

## 3. Flujo de Permisos y Licencias Académicas

1. **Solicitud Inicial**:
   - El docente ingresa a `PermissionTeacherResource`. Su cédula aparece preseleccionada y bloqueada para edición.
   - Selecciona el tipo de permiso (`name`), la fecha de inicio (`start_date`) y la duración (`duration_type`).
   - El sistema calcula reactivamente la fecha final (`end_date`).
   - La solicitud se guarda obligatoriamente con estado `pending`.
2. **Revisión y Aprobación**:
   - El Jefe de Área o Administrador revisa la justificación y documentación anexa.
   - Conmuta el estado a `approved` o `rejected`.
3. **Emisión de Memorando**:
   - Ante un permiso aprobado, el Jefe de Área o Administrador activa la acción "Emitir Memo" para generar el reporte administrativo oficial.

---

## 4. Emisión y Verificación de Documentos PDF

```
┌──────────────────┐       ┌──────────────────────┐       ┌────────────────────────┐
│ Acción de Emisión│ ----> │ Generación de Código │ ----> │ Renderizado con DomPDF │
│(Constancia/Memo) │       │ (UNERG-REP-YYYY-XXXX)│       │ (Membrete Oficial)     │
└──────────────────┘       └──────────────────────┘       └───────────┬────────────┘
                                                                      │
                                                                      ▼
                                                         ┌────────────────────────┐
                                                         │ Descarga / Verificación│
                                                         │   Física o Digital     │
                                                         └────────────────────────┘
```

1. **Disparo de la Acción**:
   - Directamente desde la tabla de docentes ("Emitir Constancia"), desde un permiso aprobado ("Emitir Memo") o desde el formulario de reportes.
2. **Generación Criptográfica de Código**:
   - El modelo `Report` asigna un `verification_code` único e indexado (`UNERG-REP-YYYY-XXXX`) y marca el estado como `issued`.
3. **Renderizado Oficial UNERG**:
   - Se inyecta la plantilla Blade institucional con membrete nacional, logo oficial de alta resolución, código de validación, fechado formal y bloques para firmas y sellos reglamentarios.
4. **Disponibilidad en el Expediente 360°**:
   - El documento queda registrado en el historial del docente (`ReportsRelationManager`) y en el widget de últimos reportes del Dashboard.
