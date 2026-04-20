-- ============================================================
--  Gobernación del Guayas — Schema de Base de Datos
--  Base de datos: gobernacion_guayas
--  Motor: MySQL 5.7+ / MariaDB 10.3+
-- ============================================================

CREATE DATABASE IF NOT EXISTS `gobernacion_guayas`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `gobernacion_guayas`;

-- ─── Usuarios del sistema ────────────────────────────────────
CREATE TABLE IF NOT EXISTS `users` (
    `id`         INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    `username`   VARCHAR(50)     NOT NULL,
    `password`   VARCHAR(255)    NOT NULL,  -- bcrypt hash (password_hash)
    `created_at` TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Trabajadores / Actas ────────────────────────────────────
-- Registra a los trabajadores de la Gobernación.
-- codtrabajador es UNIQUE para poder ser referenciado como FK desde CNEL.
CREATE TABLE IF NOT EXISTS `acta` (
    `id`               INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `codtrabajador`    INT UNSIGNED   NOT NULL,
    `nombretrabajador` VARCHAR(100)   NOT NULL,
    `cedula`           VARCHAR(20)    NOT NULL,
    `parroquia`        VARCHAR(100)   NOT NULL,
    `created_at`       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_codtrabajador` (`codtrabajador`),
    UNIQUE KEY `uq_cedula_acta`   (`cedula`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Registros CNEL / Luminarias ─────────────────────────────
-- Registra los trabajos de luminarias públicas.
-- Puede vincularse a un trabajador (FK → acta.codtrabajador).
CREATE TABLE IF NOT EXISTS `registrocnel` (
    `idregistrocnel`   INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `nombreinstitucion` VARCHAR(100)  NOT NULL,
    `nuevasluminarias` INT            NOT NULL DEFAULT 0,
    `mantenimiento`    INT            NOT NULL DEFAULT 0,
    `tipo`             VARCHAR(50)    DEFAULT NULL,
    `cantidad`         INT            NOT NULL DEFAULT 0,
    `estado`           VARCHAR(50)    DEFAULT NULL,
    `distrito`         VARCHAR(50)    DEFAULT NULL,
    `codtrabajador`    INT UNSIGNED   DEFAULT NULL,
    `nombretrabajador` VARCHAR(100)   DEFAULT NULL,
    `created_at`       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`idregistrocnel`),
    CONSTRAINT `fk_cnel_trabajador`
        FOREIGN KEY (`codtrabajador`) REFERENCES `acta` (`codtrabajador`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Formulario de Colegios ───────────────────────────────────
-- Registra instituciones educativas.
-- Puede vincularse a un registro CNEL (FK → registrocnel.idregistrocnel).
CREATE TABLE IF NOT EXISTS `formulario` (
    `id_formulario`    INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `nombreinstitucion` VARCHAR(100)  NOT NULL,
    `rector`           VARCHAR(100)   NOT NULL,
    `direccion`        VARCHAR(200)   NOT NULL,
    `telefono`         VARCHAR(20)    NOT NULL,
    `distrito`         VARCHAR(50)    NOT NULL,
    `idregistrocnel`   INT UNSIGNED   DEFAULT NULL,
    `created_at`       TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id_formulario`),
    CONSTRAINT `fk_formulario_cnel`
        FOREIGN KEY (`idregistrocnel`) REFERENCES `registrocnel` (`idregistrocnel`)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ─── Encuentros Ciudadanos ────────────────────────────────────
-- Registra los encuentros realizados con ciudadanos por parroquia.
CREATE TABLE IF NOT EXISTS `encuentros` (
    `id`             INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    `direccion`      VARCHAR(200)   NOT NULL,
    `parroquia`      VARCHAR(100)   NOT NULL,
    `estado`         VARCHAR(50)    NOT NULL,
    `nombrecontacto` VARCHAR(100)   NOT NULL,
    `cedula`         VARCHAR(20)    NOT NULL,
    `telefono`       VARCHAR(20)    NOT NULL,
    `created_at`     TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
--  NOTAS:
--  - El usuario admin se crea ejecutando: php setup.php
--  - Las contraseñas se almacenan como hash bcrypt (PHP password_hash)
--  - NUNCA almacenar contraseñas en texto plano
-- ============================================================
