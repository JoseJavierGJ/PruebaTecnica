############ Respuestas - Conocimientos SQL  ############

##### 1.1) Funcionamiento general de la sentencia JOIN  #####
# Un JOIN es juntar dos tablas de la base de datos para ver la información relacionada.

# Un ejemplo puede ser con una tabla de alumnos y otra de materias y quieres ver 
# que alumno está en que materia. Con un JOIN, puedes enlazarlas  usando algo en 
# común como un ID para que en el resultado aparezcan los datos de ambas tablas juntas.  

##### 1.2) Tipos de JOIN y su funcionamiento #####
# INNER JOIN: Solo muestra los registros que coinciden en ambas tablas. Si un alumno no está inscrito 
# en ninguna materia, no aparecerá en el resultado.  

# LEFT JOIN: Muestra todos los registros de la  tabla izquierda (la primera que pones) y los que coinciden 
# de la derecha. Si no hay coincidencia, pone NULL en las columnas de la derecha.  

# RIGHT JOIN: Lo mismo que LEFT JOIN, pero prioriza la tabla derecha.  

# FULL JOIN: Combina todo, tanto  coincidencias como registros que no tengan pareja (rellena con 
# NULL donde no hay datos).  

# CROSS JOIN: Hace todas las combinaciones posibles entre las tablas (como un producto cartesiano).  
 
##### 1.3) Funcionamiento y propósito de los TRIGGERS  #####
# Un TRIGGER es como un "asistente automático" que actúa cuando ocurre algo específico en la base de datos, 
# como agregar, modificar o eliminar registros. Por ejemplo: si borras un producto, el trigger puede 
# avisarte o  guardar automáticamente quién lo hizo y cuándo.  

# ¿Para qué sirve?  
# Automatizar tareas repetitivas: En lugar de hacerlo manualmente, el trigger se encarga (como 
#validar que un precio no sea negativo).  

# Guardar un historial: Lleva un registro de los cambios, como quién modificó algo o cuándo pasó.  

# Mantener todo en orden: Por ejemplo, si eliminas una venta, el trigger podría actualizar el 
# inventario al instante para que no haya errores.  


##### 1.4) ¿Qué es y para qué sirve un STORED PROCEDURE?  #####
# Un STORED PROCEDURE es como un script  guardado en la base de datos que puedes ejecutar cuando lo 
#necesites. En lugar de escribir una consulta larga cada vez, guardas la lógica en un procedimiento
# y lo  llamas con un nombre, pasándole parámetros si es necesario.  

# Sirve para:  
# • Reutilizar código  
# • Mejorar el rendimiento   
# • Seguridad y organización  



# Crear la base de datos 
CREATE DATABASE IF NOT EXISTS conocimientos_sql;

# Seleccionar la base de datos
USE conocimientos_sql;

# Creacion de la tabla Producto
CREATE TABLE productos (
    idProducto INT(6) PRIMARY KEY,
    nombre VARCHAR(40) NOT NULL,
    precio DECIMAL(16, 2) NOT NULL
);

# Creación de la tabla Venta
CREATE TABLE ventas (
    idVenta INT(6) PRIMARY KEY,
    idProducto INT(6) NOT NULL,
    cantidad INT(6) NOT NULL,
    FOREIGN KEY (idProducto) REFERENCES productos(idProducto)
);

# Insertar datos en la tabla Productos
INSERT INTO productos (idProducto, nombre, precio) 
	VALUES	(1, 'LAPTOP', 3000.00),
		(2, 'PC', 4000.00),
		(3, 'MOUSE', 100.00),
		(4, 'TECLADO', 150.00),
		(5, 'MONITOR', 2000.00),
		(6, 'MICROFONO', 350.00),
		(7, 'AUDIFONOS', 450.00);

# Insertar datos en la tabla Ventas
INSERT INTO ventas (idVenta, idProducto, cantidad) 
	VALUES	(1, 5, 8),
		(2, 1, 15),
		(3, 6, 13),
		(4, 6, 4),
		(5, 2, 3),
		(6, 5, 1),
		(7, 4, 5),
		(8, 2, 5),
		(9, 6, 2),
		(10, 1, 8);
        
        
############## CONSULTAS ##############

# 1.5) Traer todos los productos que tengan una venta
SELECT DISTINCT p.idProducto, p.nombre, p.precio
FROM productos p
INNER JOIN ventas v ON p.idProducto = v.idProducto;


# 1.6) Traer todos los productos que tengan ventas y la cantidad total de productos vendidos.
SELECT p.idProducto, p.nombre, SUM(v.cantidad) AS cantidad_total_vendida
FROM productos p
INNER JOIN Ventas v ON p.idProducto = v.idProducto
GROUP BY p.idProducto, p.nombre
ORDER BY cantidad_total_vendida DESC;


# 1.7) Traer todos los productos (independientemente de si tienen ventas o no) 
# y la suma total ($) vendida por producto.
SELECT p.idProducto, p.nombre, 
       COALESCE(SUM(v.cantidad * p.precio), 0) AS total_vendido
FROM productos p
LEFT JOIN ventas v ON p.idProducto = v.idProducto
GROUP BY p.idProducto, p.nombre
ORDER BY total_vendido DESC;
