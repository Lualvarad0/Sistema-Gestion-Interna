-- ============================================================
--  Migración: Roles de usuario y sistema de notificaciones
--  Ejecutar en: gobernacion_guayas
-- ============================================================

USE `gobernacion_guayas`;

-- Agregar campos de rol y perfil a la tabla users
ALTER TABLE `users`
    ADD COLUMN `rol`             ENUM('administrador','supervisor','operador') NOT NULL DEFAULT 'operador' AFTER `username`,
    ADD COLUMN `nombre_completo` VARCHAR(100) DEFAULT NULL AFTER `rol`,
    ADD COLUMN `email`           VARCHAR(100) DEFAULT NULL AFTER `nombre_completo`,
    ADD COLUMN `activo`          TINYINT(1)   NOT NULL DEFAULT 1 AFTER `email`;

-- El usuario admin existente pasa a ser Administrador
UPDATE `users` SET `rol` = 'administrador' WHERE `username` = 'admin';

-- Tabla de notificaciones del sistema
CREATE TABLE IF NOT EXISTS `notificaciones` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `tipo`        ENUM('info','success','warning','danger') NOT NULL DEFAULT 'info',
    `titulo`      VARCHAR(150) NOT NULL,
    `mensaje`     TEXT NOT NULL,
    `modulo`      VARCHAR(50)  DEFAULT NULL,
    `leida`       TINYINT(1)   NOT NULL DEFAULT 0,
    `user_id`     INT UNSIGNED DEFAULT NULL,
    `created_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_leida`   (`leida`),
    KEY `idx_user_id` (`user_id`),
    CONSTRAINT `fk_notif_user`
        FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
