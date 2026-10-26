-- SQL exacto que ejecuta 'php artisan migrate' en MySQL para las 6 tablas del instituto.
-- Generado desde las migraciones de Laravel 13 (database/migrations/2026_10_26_*).
-- Úsalo para comparar: migración (PHP) ↔ CREATE TABLE (SQL). No hace falta ejecutarlo.

create table `sedes` (`id` bigint unsigned not null auto_increment primary key, `nombre` varchar(100) not null, `ciudad` varchar(80) not null, `direccion` varchar(150) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

create table `docentes` (`id` bigint unsigned not null auto_increment primary key, `sede_id` bigint unsigned not null, `nombres` varchar(80) not null, `apellidos` varchar(80) not null, `documento` varchar(20) not null, `email` varchar(150) not null, `especialidad` varchar(100) not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

alter table `docentes` add constraint `docentes_sede_id_foreign` foreign key (`sede_id`) references `sedes` (`id`) on delete restrict;

alter table `docentes` add unique `docentes_documento_unique`(`documento`);

alter table `docentes` add unique `docentes_email_unique`(`email`);

create table `cursos` (`id` bigint unsigned not null auto_increment primary key, `docente_id` bigint unsigned not null, `codigo` varchar(20) not null, `nombre` varchar(120) not null, `cupos` smallint unsigned not null default '30', `activo` tinyint(1) not null default '1', `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

alter table `cursos` add constraint `cursos_docente_id_foreign` foreign key (`docente_id`) references `docentes` (`id`) on delete restrict;

alter table `cursos` add unique `cursos_codigo_unique`(`codigo`);

create table `estudiantes` (`id` bigint unsigned not null auto_increment primary key, `nombres` varchar(80) not null, `apellidos` varchar(80) not null, `documento` varchar(20) not null, `email` varchar(150) not null, `fecha_nacimiento` date null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

alter table `estudiantes` add unique `estudiantes_documento_unique`(`documento`);

alter table `estudiantes` add unique `estudiantes_email_unique`(`email`);

create table `carnets` (`id` bigint unsigned not null auto_increment primary key, `estudiante_id` bigint unsigned not null, `codigo` varchar(20) not null, `fecha_expedicion` date not null, `fecha_vencimiento` date not null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

alter table `carnets` add constraint `carnets_estudiante_id_foreign` foreign key (`estudiante_id`) references `estudiantes` (`id`) on delete cascade;

alter table `carnets` add unique `carnets_estudiante_id_unique`(`estudiante_id`);

alter table `carnets` add unique `carnets_codigo_unique`(`codigo`);

create table `curso_estudiante` (`id` bigint unsigned not null auto_increment primary key, `curso_id` bigint unsigned not null, `estudiante_id` bigint unsigned not null, `nota` decimal(3, 1) null, `created_at` timestamp null, `updated_at` timestamp null) default character set utf8mb4 collate 'utf8mb4_unicode_ci';

alter table `curso_estudiante` add constraint `curso_estudiante_curso_id_foreign` foreign key (`curso_id`) references `cursos` (`id`) on delete cascade;

alter table `curso_estudiante` add constraint `curso_estudiante_estudiante_id_foreign` foreign key (`estudiante_id`) references `estudiantes` (`id`) on delete cascade;

alter table `curso_estudiante` add unique `curso_estudiante_curso_id_estudiante_id_unique`(`curso_id`, `estudiante_id`);

