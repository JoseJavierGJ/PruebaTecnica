
# Sistema de Inventario - Prueba Técnica Grupo Castores

Este proyecto es una solución web desarrollada para administrar el inventario de una empresa, como parte de la evaluación técnica para el puesto de Ingeniero de Software.  
Incluye registro e inicio de sesión, gestión de productos, control de entradas y salidas, historial de movimientos y gestión de roles (Administrador y Almacenista).

---

## 🧰 IDE utilizado

- Visual Studio Code  
  - Versión: 1.102.1  
  - Sistema operativo: Windows 10 x64  
  - Node.js (integrado en VSCode): 22.15.1

---

## 💻 Lenguajes y tecnologías utilizadas

- PHP 8.2.12  
- HTML5  
- CSS3  
- JavaScript

---

## 🗄️ Base de Datos (DBMS)

- **MySQL**  
  - Script creado en **MySQL Workbench 8.0.36**  
  - Base de datos gestionada con **phpMyAdmin 5.2.1**, ejecutada desde **XAMPP Control Panel v3.3.0**

---

## ⚙️ Pasos para correr el proyecto

### 1. Instalar y configurar XAMPP

- Descargar e instalar [XAMPP](https://www.apachefriends.org/es/index.html).
- XAMPP crea por defecto una carpeta en `C:\xampp\htdocs` donde debe colocarse el proyecto.

### 2. Cambiar el puerto de MySQL (solo si ya tienes el 3306 ocupado)

> ⚠️ *Este paso es necesario si tienes instalado MySQL Workbench u otro software que ya esté usando el puerto 3306.*

- Abrir XAMPP.
- Ir a la esquina superior derecha y hacer clic en **Config** → luego en **Service and Port Settings**.
- Ir a la pestaña **MySQL** y cambiar el **Main Port** de `3306` a `3396`. Guardar.
- A continuación, en el módulo de **MySQL**, hacer clic en **Config → my.ini** y reemplazar los valores `port=3306` por `port=3396` (aparecen dos veces). Guardar y cerrar.
- En el módulo de **Apache**, hacer clic en **Config → phpMyAdmin (config.inc.php)** y asegurarse de que la línea:
  ```php
  $cfg['Servers'][$i]['host'] = 'localhost:3306';
  ```
  esté ajustada a:
  ```php
  $cfg['Servers'][$i]['host'] = 'localhost:3396';
  ```

### 3. Clonar el repositorio

- Ubicar la carpeta `C:\xampp\htdocs`.
- Clonar o copiar el repositorio del proyecto dentro de esa carpeta. Por ejemplo:
  ```
  C:\xampp\htdocs\PruebaTecnica
  ```
  
### 4. Iniciar Apache y MySQL en XAMPP

- En el panel principal de XAMPP, dar clic en **Start** en los módulos de Apache y MySQL.
- Ambos botones deben cambiar a "Stop", lo que indica que los servicios están activos.

### 5. Importar la base de datos

- Abrir phpMyAdmin con el siguiente enlace (http://localhost/phpmyadmin).
- Crear una base de datos con el nombre **prueba_tecnica**.
- Ir a la pestaña **Importar**, seleccionar el archivo `.sql` correspondiente desde la carpeta `SCRIPTS` y ejecutarlo.

### 6. Ejecutar el proyecto

- Abrir un navegador y entrar a:
  ```
  http://localhost/PruebaTecnica
  ```
- Se cargará la pantalla de inicio de sesión. Desde ahí puedes iniciar sesión o registrarte.

---

## 📁 Estructura del Repositorio

```
PruebaTecnica/
├── index.php
├── registrarse.php
├── config/
│   └── Connection.php
├── css/
│   └── style.css
├── Home/
│   ├── agregar_producto.php
│   ├── ajustar_inventario.php
│   └── ...
├── InicioSesion/
│   ├── CerrarSesion.php
│   ├── InicioSesion.php
│   └── registrarse.php
├── scripts/
│   ├── consultas_sql.sql
│   ├── script_bd.sql
│   └── diagrama_bd.png
└── README.md
```

---

## 🎥 Video de demostración

El video de muestra la funcionalidad por rol, validaciones, historial y restricciones está disponible en el siguiente enlace:  
**https://drive.google.com/file/d/1c7BxIemTZGnPX8o916F34STr-glwKVFv/view?usp=drive_link**

---

**💻 Desarrollado por:**  
José Javier
