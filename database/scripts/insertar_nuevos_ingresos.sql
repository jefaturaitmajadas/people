-- Inserta en empleados los nuevos ingresos de storage/app/NuevosIngresos.xlsx (empresa 6, COLAPERSA). Generado 2026-11-05.
-- Se puede ejecutar más de una vez: un código que ya existe se omite y no se modifica.
--
-- Del Excel: código, nombres, apellidos y salario por hora.
-- salario es mensual: el doble del salario_base del Excel, que viene quincenal.
-- Por defecto: los mismos catálogos del resto de colaboradores activos (banco_id 3, los demás id 1).
-- Quedan vacíos porque el Excel no los trae: identificación, fecha de inicio, fecha de nacimiento,
-- género, puesto, departamento, jefe y número de cuenta.
-- Quedan fuera de planilla: la nómina toma a quien tiene mano_obra_id, y aquí va NULL hasta
-- que RRHH complete la ficha.

START TRANSACTION;

-- 275 LORENZO GARCIA
INSERT INTO empleados (codigo_empleado, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, salario_por_hora, salario, empresa_id, genera_planilla, contratable, identificacion_id, tipo_contrato_id, turno_id, tipo_salario_id, estado_civil_id, religion_id, pais_nacimiento_id, municipio_nacimiento_id, nivel_educativo_id, pueblo_id, comunidad_linguistica_id, tipo_casa_id, tipo_transporte_id, pais_residencia_id, discapacidad_id, ocupacion_id, tipo_pago_id, moneda_id, seguro_id, banco_id, tipo_cuenta_id, igss_tipo_planilla_id, centro_costo_id, profesion_id, created_at, updated_at)
  SELECT '275', 'LORENZO', 'ANTONIO', 'GARCIA', 'GARCIA', 20.17, 4840, 6, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, NOW(), NOW()
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM empleados WHERE codigo_empleado = '275');

-- 279 MIGUEL PEREZ
INSERT INTO empleados (codigo_empleado, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, salario_por_hora, salario, empresa_id, genera_planilla, contratable, identificacion_id, tipo_contrato_id, turno_id, tipo_salario_id, estado_civil_id, religion_id, pais_nacimiento_id, municipio_nacimiento_id, nivel_educativo_id, pueblo_id, comunidad_linguistica_id, tipo_casa_id, tipo_transporte_id, pais_residencia_id, discapacidad_id, ocupacion_id, tipo_pago_id, moneda_id, seguro_id, banco_id, tipo_cuenta_id, igss_tipo_planilla_id, centro_costo_id, profesion_id, created_at, updated_at)
  SELECT '279', 'MIGUEL', 'ALBERTO', 'PEREZ', 'MONROY', 20.83, 5000, 6, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, NOW(), NOW()
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM empleados WHERE codigo_empleado = '279');

-- 280 ZOILA TUL
INSERT INTO empleados (codigo_empleado, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, salario_por_hora, salario, empresa_id, genera_planilla, contratable, identificacion_id, tipo_contrato_id, turno_id, tipo_salario_id, estado_civil_id, religion_id, pais_nacimiento_id, municipio_nacimiento_id, nivel_educativo_id, pueblo_id, comunidad_linguistica_id, tipo_casa_id, tipo_transporte_id, pais_residencia_id, discapacidad_id, ocupacion_id, tipo_pago_id, moneda_id, seguro_id, banco_id, tipo_cuenta_id, igss_tipo_planilla_id, centro_costo_id, profesion_id, created_at, updated_at)
  SELECT '280', 'ZOILA', 'LUCRECIA', 'TUL', 'CHITIC', 17.42, 4180, 6, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, NOW(), NOW()
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM empleados WHERE codigo_empleado = '280');

-- 281 JUEL CHACAR
INSERT INTO empleados (codigo_empleado, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, salario_por_hora, salario, empresa_id, genera_planilla, contratable, identificacion_id, tipo_contrato_id, turno_id, tipo_salario_id, estado_civil_id, religion_id, pais_nacimiento_id, municipio_nacimiento_id, nivel_educativo_id, pueblo_id, comunidad_linguistica_id, tipo_casa_id, tipo_transporte_id, pais_residencia_id, discapacidad_id, ocupacion_id, tipo_pago_id, moneda_id, seguro_id, banco_id, tipo_cuenta_id, igss_tipo_planilla_id, centro_costo_id, profesion_id, created_at, updated_at)
  SELECT '281', 'JUEL', NULL, 'CHACAR', 'AJU', 17.6, 4226, 6, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, NOW(), NOW()
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM empleados WHERE codigo_empleado = '281');

-- 282 VICTOR XALIN
INSERT INTO empleados (codigo_empleado, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, salario_por_hora, salario, empresa_id, genera_planilla, contratable, identificacion_id, tipo_contrato_id, turno_id, tipo_salario_id, estado_civil_id, religion_id, pais_nacimiento_id, municipio_nacimiento_id, nivel_educativo_id, pueblo_id, comunidad_linguistica_id, tipo_casa_id, tipo_transporte_id, pais_residencia_id, discapacidad_id, ocupacion_id, tipo_pago_id, moneda_id, seguro_id, banco_id, tipo_cuenta_id, igss_tipo_planilla_id, centro_costo_id, profesion_id, created_at, updated_at)
  SELECT '282', 'VICTOR', 'DANIEL', 'XALIN', 'TUN', 17.5, 4200, 6, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, NOW(), NOW()
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM empleados WHERE codigo_empleado = '282');

-- 283 PEDRO DE LA CRUZ
INSERT INTO empleados (codigo_empleado, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, salario_por_hora, salario, empresa_id, genera_planilla, contratable, identificacion_id, tipo_contrato_id, turno_id, tipo_salario_id, estado_civil_id, religion_id, pais_nacimiento_id, municipio_nacimiento_id, nivel_educativo_id, pueblo_id, comunidad_linguistica_id, tipo_casa_id, tipo_transporte_id, pais_residencia_id, discapacidad_id, ocupacion_id, tipo_pago_id, moneda_id, seguro_id, banco_id, tipo_cuenta_id, igss_tipo_planilla_id, centro_costo_id, profesion_id, created_at, updated_at)
  SELECT '283', 'PEDRO', 'DANIEL', 'DE LA CRUZ', 'FUENTES', 21.46, 5150, 6, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, NOW(), NOW()
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM empleados WHERE codigo_empleado = '283');

-- 284 BAYRON ALVARADO
INSERT INTO empleados (codigo_empleado, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, salario_por_hora, salario, empresa_id, genera_planilla, contratable, identificacion_id, tipo_contrato_id, turno_id, tipo_salario_id, estado_civil_id, religion_id, pais_nacimiento_id, municipio_nacimiento_id, nivel_educativo_id, pueblo_id, comunidad_linguistica_id, tipo_casa_id, tipo_transporte_id, pais_residencia_id, discapacidad_id, ocupacion_id, tipo_pago_id, moneda_id, seguro_id, banco_id, tipo_cuenta_id, igss_tipo_planilla_id, centro_costo_id, profesion_id, created_at, updated_at)
  SELECT '284', 'BAYRON', 'UBALDO', 'ALVARADO', 'HERNANDEZ', 21.46, 5666, 6, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, NOW(), NOW()
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM empleados WHERE codigo_empleado = '284');

-- 285 JUANA PEREZ
INSERT INTO empleados (codigo_empleado, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, salario_por_hora, salario, empresa_id, genera_planilla, contratable, identificacion_id, tipo_contrato_id, turno_id, tipo_salario_id, estado_civil_id, religion_id, pais_nacimiento_id, municipio_nacimiento_id, nivel_educativo_id, pueblo_id, comunidad_linguistica_id, tipo_casa_id, tipo_transporte_id, pais_residencia_id, discapacidad_id, ocupacion_id, tipo_pago_id, moneda_id, seguro_id, banco_id, tipo_cuenta_id, igss_tipo_planilla_id, centro_costo_id, profesion_id, created_at, updated_at)
  SELECT '285', 'JUANA', 'LILIANA', 'PEREZ', 'RIVERA', 21.46, 2508, 6, 0, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 3, 1, 1, 1, 1, NOW(), NOW()
  FROM DUAL WHERE NOT EXISTS (SELECT 1 FROM empleados WHERE codigo_empleado = '285');

COMMIT;

-- Verificación: deben salir 8 filas con los nombres del Excel
SELECT id, codigo_empleado, primer_nombre, segundo_nombre, primer_apellido, segundo_apellido, salario_por_hora, salario, created_at
FROM empleados WHERE codigo_empleado IN ('275', '279', '280', '281', '282', '283', '284', '285') ORDER BY CAST(codigo_empleado AS UNSIGNED);
