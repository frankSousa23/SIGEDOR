# Estructura y Esquema de Base de Datos de SIGEDOR

El esquema de base de datos de SIGEDOR está optimizado para **MySQL 8.0+ / MariaDB 10.4+** (y compatible con SQLite para testing automatizado). Cuenta con **24 migraciones estructuradas**, claves foráneas con integridad referencial, índices en identificadores únicos y trazabilidad con borrado lógico (*SoftDeletes*).

---

## 1. Tablas Jerárquicas Institucionales UNERG

### 1.1 `sedes`
Almacena los recintos y núcleos territoriales de la universidad.
```sql
CREATE TABLE sedes (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(255) NOT NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL
);
```

### 1.2 `areas`
Áreas de conocimiento académico institucional.
```sql
CREATE TABLE areas (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(255) NOT NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL
);
```

### 1.3 `programas`
Carreras de pregrado y programas de posgrado impartidos en cada área.
```sql
CREATE TABLE programas (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(255) NOT NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL
);
```

---

## 2. Tablas Principales de Gestión Docente

### 2.1 `teachers`
Expediente curricular y demográfico del personal docente universitario.
```sql
CREATE TABLE teachers (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    cdi varchar(20) NOT NULL UNIQUE COMMENT 'Cédula de Identidad',
    name varchar(255) NOT NULL,
    surName varchar(255) NOT NULL,
    genre enum('F','M') NOT NULL,
    phone varchar(255) NULL,
    email varchar(255) NOT NULL UNIQUE,
    birthDate date NULL,
    datePromotion date NULL,
    asignaturePromotion varchar(255) NULL,
    user_id bigint unsigned NOT NULL UNIQUE,
    sede_id bigint unsigned NOT NULL,
    area_id bigint unsigned NOT NULL,
    programa_id bigint unsigned NULL,
    site_id bigint unsigned NULL,
    category_id bigint unsigned NULL,
    dedication_id bigint unsigned NULL,
    permissionteacher_id bigint unsigned NULL,
    report_id bigint unsigned NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    deleted_at timestamp NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (sede_id) REFERENCES sedes(id) ON DELETE CASCADE,
    FOREIGN KEY (area_id) REFERENCES areas(id) ON DELETE CASCADE,
    FOREIGN KEY (programa_id) REFERENCES programas(id) ON DELETE SET NULL
);
```

### 2.2 `categories`
Control histórico del escalafón universitario docente y fechas de ascenso.
```sql
CREATE TABLE categories (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    teacher_cdi varchar(20) NOT NULL,
    preTitle varchar(255) NULL,
    lastTitle varchar(255) NULL,
    current_category varchar(255) NULL,
    instructor date NULL,
    asistente date NULL,
    agregado date NULL,
    asociado date NULL,
    titular date NULL,
    info text NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    INDEX (teacher_cdi)
);
```

### 2.3 `dedications`
Modalidades contractuales de carga horaria semanal según la normativa UNERG.
```sql
CREATE TABLE dedications (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    teacher_cdi varchar(20) NOT NULL,
    name varchar(255) NOT NULL,
    hours int NOT NULL,
    type varchar(10) NOT NULL,
    director varchar(255) NULL,
    studentNumber int NULL,
    studentHours int NULL,
    info text NULL,
    is_active boolean DEFAULT true,
    is_available boolean DEFAULT true,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    INDEX (teacher_cdi)
);
```

### 2.4 `sites`
Distribución de cátedra, unidades de crédito (UC) y asignación horaria del docente.
```sql
CREATE TABLE sites (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    teacher_cdi varchar(20) NOT NULL,
    sede_id bigint unsigned NULL,
    area_id bigint unsigned NULL,
    programa_id bigint unsigned NULL,
    uc int NULL,
    weekHours int NULL,
    sections int NULL,
    info text NULL,
    is_active boolean DEFAULT true,
    is_available boolean DEFAULT true,
    teachers_count int DEFAULT 0,
    last_assignment datetime NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    deleted_at timestamp NULL,
    INDEX (teacher_cdi)
);
```

### 2.5 `permissionsteachers`
Solicitudes de permisos, años sabáticos, licencias e incapacidades docentes.
```sql
CREATE TABLE permissionsteachers (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NULL,
    teacher_cdi varchar(20) NOT NULL,
    memo_number varchar(255) NULL,
    type varchar(255) NULL,
    is_paid boolean DEFAULT true,
    status varchar(50) DEFAULT 'pending',
    duration_type varchar(50) NULL,
    start_date date NOT NULL,
    end_date date NOT NULL,
    description text NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    INDEX (teacher_cdi)
);
```

### 2.6 `reports`
Documentos oficiales emitidos (Constancias de Trabajo, Memorandos e Informes).
```sql
CREATE TABLE reports (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    verification_code varchar(255) NULL UNIQUE,
    teacher_cdi varchar(20) NOT NULL,
    memoNumber varchar(255) NULL,
    typeReport varchar(255) NULL,
    status varchar(50) DEFAULT 'issued',
    report text NULL,
    email varchar(255) NULL,
    info text NULL,
    sede_id bigint unsigned NULL,
    area_id bigint unsigned NULL,
    category_id bigint unsigned NULL,
    dedication_id bigint unsigned NULL,
    created_by bigint unsigned NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    INDEX (verification_code),
    INDEX (teacher_cdi),
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);
```

---

## 3. Tablas de Seguridad y Autorización

### 3.1 `users`
Cuentas de acceso autenticadas bajo dominio `@sigedor.com`.
```sql
CREATE TABLE users (
    id bigint unsigned AUTO_INCREMENT PRIMARY KEY,
    name varchar(255) NOT NULL,
    email varchar(255) NOT NULL UNIQUE,
    email_verified_at timestamp NULL,
    password varchar(255) NOT NULL,
    is_active boolean DEFAULT true,
    is_approved boolean DEFAULT true,
    sede_id bigint unsigned NULL,
    area_id bigint unsigned NULL,
    remember_token varchar(100) NULL,
    created_at timestamp NULL,
    updated_at timestamp NULL,
    deleted_at timestamp NULL
);
```

### 3.2 Tablas Spatie Laravel-Permission
- `roles`: Catálogo de roles (`admin`, `area_manager`, `teacher`).
- `permissions`: Permisos atómicos del sistema.
- `model_has_roles`: Asignación N:M de roles a los modelos `User`.
- `model_has_permissions`: Asignación directa de permisos a usuarios.
- `role_has_permissions`: Asociación de permisos a cada rol.

---

## 4. Registro de Migraciones del Repositorio (24 Migraciones)

1. `0001_01_01_000000_create_sessions_table.php` (Sesiones de usuario)
2. `0001_01_01_000001_create_cache_table.php` (Almacén de caché)
3. `0001_01_01_000002_create_jobs_table.php` (Colas de trabajo)
4. `0001_01_01_103235_create_permission_tables.php` (Spatie RBAC)
5. `0001_02_000000_create_sedes_table.php` (Sedes UNERG)
6. `0001_02_000010_create_areas_table.php` (Áreas académicas)
7. `0001_02_000020_create_programas_table.php` (Programas de estudio)
8. `0001_05_01_000000_create_users_table.php` (Usuarios del sistema)
9. `2021_11_26_015812_create_teachers_table.php` (Expedientes docentes)
10. `2022_04_02_000000_create_sites_table.php` (Asignación de cátedra)
11. `2024_02_09_213006_create_site_teacher_table.php` (Pivote de asignación)
12. `2024_03_13_000000_add_soft_deletes_to_users_table.php` (Borrado lógico en usuarios)
13. `2024_11_26_015813_create_categories_table.php` (Escalafón docente)
14. `2024_11_26_015813_create_dedications_table.php` (Dedicación horaria)
15. `2024_11_26_015814_add_missing_columns_to_dedications.php` (Columnas de dedicación)
16. `2024_11_26_015937_create_permissionsteachers_table.php` (Permisos docentes)
17. `2024_11_26_020008_create_reports_table.php` (Reportes oficiales)
18. `2025_01_24_000000_create_activity_log_table.php` (Auditoría Spatie)
19. `2025_01_24_165521_add_event_column_to_activity_log_table.php` (Eventos de auditoría)
20. `2025_01_25_135200_create_telescope_entries_table.php` (Telemetría Telescope)
21. `2026_08_31_131500_remove_disable_assistant_rule_from_categories_table.php` (Ajuste de reglas de ascenso)
22. `2026_09_03_000001_alter_reports_report_to_text.php` (Ampliación de campo reporte a TEXT)
23. `2026_09_09_000001_add_verification_and_status_to_reports_table.php` (Código de autenticidad documental y estado)
24. `2026_09_09_000002_sync_teacher_relations_data.php` (Sincronización retroactiva de claves foráneas)
