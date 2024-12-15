# Estructura de Base de Datos

## Tablas Principales

### 1. teachers
Almacena la información principal de los docentes.
```sql
CREATE TABLE teachers (
    id bigint unsigned PRIMARY KEY,
    cdi varchar(255) UNIQUE,
    email varchar(255) UNIQUE,
    first_name varchar(255),
    last_name varchar(255),
    phone varchar(255),
    address text,
    site_id bigint unsigned,
    category_id bigint unsigned,
    dedication_id bigint unsigned,
    created_at timestamp,
    updated_at timestamp,
    deleted_at timestamp NULL
);
```

### 2. sites
Gestiona las sedes institucionales.
```sql
CREATE TABLE sites (
    id bigint unsigned PRIMARY KEY,
    name varchar(255),
    description text,
    address text,
    created_at timestamp,
    updated_at timestamp
);
```

### 3. categories
Maneja las categorías docentes.
```sql
CREATE TABLE categories (
    id bigint unsigned PRIMARY KEY,
    name varchar(255),
    description text,
    level int,
    requirements text,
    created_at timestamp,
    updated_at timestamp
);
```

### 4. dedications
Control de dedicaciones docentes.
```sql
CREATE TABLE dedications (
    id bigint unsigned PRIMARY KEY,
    type varchar(255),
    hours int,
    description text,
    created_at timestamp,
    updated_at timestamp
);
```

### 5. permissionsteachers
Gestión de permisos y licencias docentes.
```sql
CREATE TABLE permissionsteachers (
    id bigint unsigned PRIMARY KEY,
    teacher_id bigint unsigned,
    start_date date,
    end_date date,
    reason text,
    status enum('pending','approved','rejected'),
    created_at timestamp,
    updated_at timestamp
);
```

### 6. reports
Sistema de reportes.
```sql
CREATE TABLE reports (
    id bigint unsigned PRIMARY KEY,
    teacher_id bigint unsigned,
    type varchar(255),
    content text,
    generated_at timestamp,
    created_at timestamp,
    updated_at timestamp
);
```

## Tablas de Sistema

### 1. users
Gestión de usuarios del sistema.
```sql
CREATE TABLE users (
    id bigint unsigned PRIMARY KEY,
    name varchar(255),
    email varchar(255) UNIQUE,
    email_verified_at timestamp NULL,
    password varchar(255),
    remember_token varchar(100),
    is_admin boolean,
    created_at timestamp,
    updated_at timestamp,
    deleted_at timestamp NULL
);
```

### 2. permissions
Sistema de permisos (Spatie).
```sql
CREATE TABLE permissions (
    id bigint unsigned PRIMARY KEY,
    name varchar(255),
    guard_name varchar(255),
    created_at timestamp,
    updated_at timestamp
);
```

### 3. roles
Sistema de roles (Spatie).
```sql
CREATE TABLE roles (
    id bigint unsigned PRIMARY KEY,
    name varchar(255),
    guard_name varchar(255),
    created_at timestamp,
    updated_at timestamp
);
```

## Relaciones

### Teachers
- belongsTo Site
- belongsTo Category
- belongsTo Dedication
- hasMany PermissionTeacher
- hasMany Report

### Sites
- hasMany Teacher

### Categories
- hasMany Teacher

### Dedications
- hasMany Teacher

### PermissionTeacher
- belongsTo Teacher

## Índices y Optimización

### Índices Principales
- teachers: cdi, email
- users: email
- permissions: name, guard_name
- roles: name, guard_name

### Claves Foráneas
- teachers -> sites
- teachers -> categories
- teachers -> dedications
- permissionsteachers -> teachers
- reports -> teachers

## Integridad Referencial

Todas las relaciones están protegidas por claves foráneas con:
- ON DELETE CASCADE para registros dependientes
- ON UPDATE CASCADE para mantener la integridad

## Soft Deletes

Implementado en:
- teachers
- users
- categories
- sites

## Notas de Mantenimiento

1. Backups
   - Programados diariamente
   - Retención de 30 días
   - Incluye estructura y datos

2. Optimización
   - Análisis mensual de índices
   - Vacuum de tablas
   - Monitoreo de rendimiento

3. Migraciones
   - Control de versiones
   - Rollback seguro
   - Seeders para datos de prueba
