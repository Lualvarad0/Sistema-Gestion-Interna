-- Migración: Agregar columnas de geolocalización a todos los módulos
ALTER TABLE `formulario`
    ADD COLUMN `latitud`  DECIMAL(10,7) NULL DEFAULT NULL,
    ADD COLUMN `longitud` DECIMAL(10,7) NULL DEFAULT NULL;

ALTER TABLE `registrocnel`
    ADD COLUMN `latitud`  DECIMAL(10,7) NULL DEFAULT NULL,
    ADD COLUMN `longitud` DECIMAL(10,7) NULL DEFAULT NULL;

ALTER TABLE `encuentros`
    ADD COLUMN `latitud`  DECIMAL(10,7) NULL DEFAULT NULL,
    ADD COLUMN `longitud` DECIMAL(10,7) NULL DEFAULT NULL;

ALTER TABLE `acta`
    ADD COLUMN `latitud`  DECIMAL(10,7) NULL DEFAULT NULL,
    ADD COLUMN `longitud` DECIMAL(10,7) NULL DEFAULT NULL;
