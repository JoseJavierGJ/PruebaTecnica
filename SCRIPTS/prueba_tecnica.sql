# Crear base de datos
CREATE DATABASE IF NOT EXISTS prueba_tecnica;
USE prueba_tecnica;

# eliminar tablas si existen
DROP TABLE IF EXISTS movimientos;
DROP TABLE IF EXISTS tipos_movimiento;
DROP TABLE IF EXISTS productos;
DROP TABLE IF EXISTS usuarios;
DROP TABLE IF EXISTS roles;

# Tabla de roles
CREATE TABLE roles (
    idRol INT(2) PRIMARY KEY AUTO_INCREMENT,
    nombreRol VARCHAR(50) NOT NULL
);

# Tabla de usuarios
CREATE TABLE usuarios (
    idUsuario INT(6) PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(100) NOT NULL,
    correo VARCHAR(50) NOT NULL UNIQUE,
    contrasena VARCHAR(255) NOT NULL, 
    idRol INT(2) NOT NULL,
    #estatus INT(1) DEFAULT 1 COMMENT '1=Activo, 0=Inactivo',
    FOREIGN KEY (idRol) REFERENCES roles(idRol)
);

# tabla de productos
CREATE TABLE productos (
    idProducto INT(6) PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(40) NOT NULL,
    descripcion TEXT,
    cantidad INT(6) DEFAULT 0,
    estatus INT(1) DEFAULT 1 COMMENT '1=Activo, 0=Inactivo'
);

# Tabla de tipos de movimento
CREATE TABLE tipos_movimiento (
    idTipoMovimiento INT(2) PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(50) NOT NULL,
    descripcion VARCHAR(255)
);


# Tabla de movimientos (historico)
CREATE TABLE movimientos (
    idMovimiento INT(6) PRIMARY KEY AUTO_INCREMENT,
    idProducto INT(6) NOT NULL,
    idUsuario INT(6) NOT NULL,
    idTipoMovimiento INT(2) NOT NULL,
    cantidad INT(6) NOT NULL,
    cantidadAnterior INT(6) NOT NULL,
    cantidadNueva INT(6) NOT NULL,
    fechaMovimiento DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (idProducto) REFERENCES productos(idProducto),
    FOREIGN KEY (idUsuario) REFERENCES usuarios(idUsuario),
    FOREIGN KEY (idTipoMovimiento) REFERENCES tipos_movimiento(idTipoMovimiento)
);


##### Incio de INSERTs - Datos Iniciales #####

# Insert roles
INSERT INTO roles (idRol, nombreRol) 
	VALUES (1, 'Administrador'),
		(2, 'Almacenista');

# insert usuarios
INSERT INTO usuarios (idUsuario, nombre, correo, contrasena, idRol)                                     			####### NOTA ########
	VALUES	(1, 'Laura Méndez', 'laura@example.com', '$2y$10$IOlamfCS7QHj9Q/Uu8wCi.f7AeuEhJ3v38AMzmwz9OtNMVKUn3VdK', 1), ### 1234
		(2, 'Ricardo Mendoza', 'ricardo@example.com', '$2y$10$J155LpzVi095oNkzpyOrFuPIibLHrvFn9AKpqiKydSZRdVcL0YCU2', 2), ### 1234 
		(3, 'Andrea Granados', 'andrea@example.com', '$2y$10$9E5vlwMk0g9ywGJH0BL0Q.O1N/OSMMV1sOVrWCO.UZfLfJqkC3ndC', 2), ### 123456
		(4, 'Daniel Ugarte', 'daniel@example.com', '$2y$10$C62ai.2M6z4rVaWDTX49KOfeJCUelEfA54iN58jlXmK0jnra7GRO6', 1), ### 123456
		(5, 'Yoselin Escudero', 'yoselin@example.com', '$2y$10$xs/dgnhO9gRuNiBIUhmR5ehYs02TpnRUtIBlBbabNzmOVGk/vHVvy', 1), ### 123456
		(6, 'Carlos Guillen', 'carlos@example.com', '$2y$10$RJJGc5c2xnEjtAZqF0VKouSNJ7Hytxy01ceTOAuKY9ynsugH239gG', 2); ### 123456


# insert productos
INSERT INTO productos (idProducto, nombre, descripcion, cantidad, estatus) 
	VALUES	(1, 'Tornillos de acero', 'M6x20 mm', 550, 0),
		(2, 'Planchas de acero inoxidable', '2m x 1m', 100, 1),
		(3, 'Rodamientos industriales', 'SKF 6205', 300, 1),
		(4, 'Pintura epóxica', 'Color gris industrial', 100, 1),
		(5, 'Cables eléctricos', 'Calibre 12, 100m por rollo', 30, 1),
		(6, 'Válvulas de compuerta', '2 pulgadas', 75, 0),
		(7, 'Bandas transportadoras', '3 metros, caucho reforzado', 300, 1),
		(8, 'Bombas centrífugas', '1.5 HP, acero inoxidable', 15, 1),
		(9, 'PLC', 'Siemens S7-1200, 24V DC', 13, 1),
		(10, 'Perfiles de aluminio', '30x30 mm, 6 metros', 30, 1),
		(11, 'Adhesivo industrial epóxico', '1 kg', 63, 1),
		(12, 'Planchas de acero A36', '1/4\" x 1.2m x 2.4m', 50, 1),
		(13, 'Alambre de soldadura', 'Rollo de 15 kg', 100, 1),
		(14, 'Extintores ABC', '10 lbs', 22, 1),
		(15, 'Lámparas LED', '100W', 225, 1),
		(16, 'Tornillos hexagonales', 'M8 x 30 mm', 1400, 1),
		(17, 'Pernos de anclaje', ' M12 x 150 mm ', 428, 1),
		(18, 'Cilindros neumáticos', '32mm x 100mm', 734, 1),
		(19, 'Lentes de seguridad', 'UV400', 225, 1);

# Insert tipos_movimiento
INSERT INTO tipos_movimiento (idTipoMovimiento, nombre, descripcion) 
	VALUES (1, 'Entrada', 'Entrada de productos al almacén'),
		(2, 'Salida', 'Salida de productos del almacén');

# Insert movimientos
INSERT INTO movimientos (idMovimiento, idProducto, idUsuario, idTipoMovimiento, cantidad, cantidadAnterior, cantidadNueva, fechaMovimiento) 
	VALUES	(1, 2, 2, 2, 10, 140, 130, '2025-07-03 09:15:31'),
		(2, 2, 2, 2, 10, 130, 120, '2025-07-03 09:30:46'),
		(3, 4, 3, 2, 100, 200, 100, '2025-07-04 10:45:16'),
		(4, 5, 2, 2, 15, 50, 35, '2025-07-05 11:22:19'),
		(5, 1, 1, 1, 10, 530, 540, '2025-07-06 14:08:02'),
		(6, 1, 1, 1, 5, 540, 545, '2025-07-07 15:17:15'),
		(7, 2, 2, 2, 4, 120, 116, '2025-07-08 16:34:43'),
		(8, 2, 2, 2, 16, 116, 100, '2025-07-09 17:01:20'),
		(9, 5, 3, 2, 5, 35, 30, '2025-07-10 18:25:35'),
		(10, 1, 1, 1, 5, 545, 550, '2025-07-11 10:44:54'),
		(11, 2, 4, 1, 5, 100, 105, '2025-07-12 11:24:54'),
		(12, 2, 2, 2, 5, 105, 100, '2025-07-13 12:39:15'),
		(13, 9, 2, 2, 3, 16, 13, '2025-07-14 13:41:38'),
		(14, 16, 2, 2, 100, 1500, 1400, '2025-07-15 14:56:10'),
		(15, 17, 3, 2, 12, 440, 428, '2025-07-16 15:26:10'),
		(16, 19, 6, 2, 15, 240, 225, '2025-07-17 16:56:10'),
		(17, 13, 4, 1, 12, 88, 100, '2025-07-18 17:36:36'),
		(18, 10, 4, 1, 11, 19, 30, '2025-07-19 18:57:31');
        
        




