<?php
session_start();

// Verifica si el usuario ha iniciado sesión y si es almacenista
if (!isset($_SESSION['nombre']) || $_SESSION['idRol'] != 2) {
  header('Location: ../index.php');
  exit();
}

$vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Dashboard de Almacén</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
</head>

<body>
  <div class="sidebar">
    <h2>Almacén</h2>
    <a href="dashboardAlmacenista.php?vista=inicio" class="active">Inicio</a>

    <!-- Módulo de Inventario -->
    <div class="module-card">
      <h3>Gestión de Inventario</h3>
      <div class="module-actions">
        <a href="dashboardAlmacenista.php?vista=ver_inventario">Ver inventario</a>
      </div>
    </div>

    <!-- Módulo de Salidas -->
    <div class="module-card">
      <h3>Salidas de Productos</h3>
      <div class="module-actions">
        <a href="dashboardAlmacenista.php?vista=registrar_salida">Registrar salida</a>
        <a href="dashboardAlmacenista.php?vista=historial_salidas">Historial de salidas</a>
      </div>
    </div>

    <a href="../InicioSesion/CerrarSesion.php">Cerrar sesión</a>
  </div>

  <div class="main-content">
    <?php
    if ($vista === 'inicio') {
      echo '<h1>Bienvenido, ' . htmlspecialchars($_SESSION['nombre']) . '</h1>
        <div class="card">
          <h3>Resumen de Almacén</h3>
          <p>Panel principal para la gestión de inventario y salidas de productos.</p>
        </div>

        <div class="card">
          <h3>Acciones Rápidas</h3>
          <div class="quick-actions">
            <a href="dashboardAlmacenista.php?vista=ver_inventario">Consultar inventario</a>
            <a href="dashboardAlmacenista.php?vista=registrar_salida">Registrar salida</a>
            <a href="dashboardAlmacenista.php?vista=historial_salidas">Historial de salidas</a>
          </div>
        </div>';
    } else {
      $archivo = __DIR__ . '/' . $vista . '.php';
      if (file_exists($archivo)) {
        include $archivo;
      } else {
        echo "<div class='card'><h3>Error</h3><p>La vista solicitada no existe.</p></div>";
      }
    }
    ?>
  </div>
</body>

</html>