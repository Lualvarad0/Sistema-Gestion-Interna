-- ─────────────────────────────────────────────────────────────────
-- Migración: perfil extendido + tabla de configuración del sistema
-- Ejecutar UNA sola vez. Si las columnas ya existen, omitir ALTER.
-- ─────────────────────────────────────────────────────────────────

-- Campos adicionales en users
ALTER TABLE `users`
    ADD COLUMN `telefono`    VARCHAR(20)  NULL                AFTER `email`,
    ADD COLUMN `cargo`       VARCHAR(100) NULL                AFTER `telefono`,
    ADD COLUMN `avatar_color` VARCHAR(20) NOT NULL DEFAULT 'blue' AFTER `cargo`;

-- Tabla de configuración del sistema (clave-valor)
CREATE TABLE IF NOT EXISTS `settings` (
    `id`          INT UNSIGNED    AUTO_INCREMENT PRIMARY KEY,
    `clave`       VARCHAR(100)    NOT NULL,
    `valor`       TEXT,
    `tipo`        ENUM('string','boolean','integer') NOT NULL DEFAULT 'string',
    `descripcion` VARCHAR(200),
    `updated_at`  TIMESTAMP       DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY `uk_clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Valores predeterminados
INSERT INTO `settings` (`clave`, `valor`, `tipo`, `descripcion`) VALUES
    ('app_name',               'Gobernación del Guayas', 'string',  'Nombre de la aplicación'),
    ('session_lifetime',       '7200',                   'integer', 'Duración de sesión en segundos'),
    ('maintenance_mode',       '0',                      'boolean', 'Modo de mantenimiento activo'),
    ('registros_por_pagina',   '15',                     'integer', 'Filas por defecto en tablas'),
    ('notificaciones_activas', '1',                      'boolean', 'Sistema de notificaciones habilitado')
ON DUPLICATE KEY UPDATE clave = clave;
