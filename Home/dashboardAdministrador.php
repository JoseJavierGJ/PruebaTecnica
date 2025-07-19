<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['nombre'])) {
  header('Location: ../index.php');
  exit;
}

// Verifica el rol del usuario
if ($_SESSION['idRol'] !== 1) {
  echo "Acceso denegado. Solo los administradores pueden acceder a esta página.";
  exit;
}

// Vista dinámica (inicio por defecto)
$vista = isset($_GET['vista']) ? $_GET['vista'] : 'inicio';
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard Administrador</title>
  <link rel="stylesheet" href="../css/style.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap">
</head>

<body>
  <div class="sidebar">
    <h2>Dashboard Admin</h2>
    <a href="dashboardAdministrador.php?vista=inicio" class="active">Inicio</a>

    <!-- Módulo de Inventario -->
    <div class="module-card">
      <h3>Inventario</h3>
      <div class="module-actions">
        <a href="dashboardAdministrador.php?vista=ver_inventario">Ver inventario</a>
        <a href="dashboardAdministrador.php?vista=agregar_producto">Agregar producto</a>
        <a href="dashboardAdministrador.php?vista=ajustar_inventario">Ajustar inventario</a>
        <a href="dashboardAdministrador.php?vista=gestionar_productos">Gestionar productos</a>
      </div>
    </div>

    <!-- Módulo Histórico -->
    <div class="module-card">
      <h3>Histórico</h3>
      <div class="module-actions">
        <a href="dashboardAdministrador.php?vista=historico">Ver registros históricos</a>
      </div>
    </div>

    <a href="../InicioSesion/CerrarSesion.php">Cerrar sesión</a>
  </div>

  <div class="main">
    <?php
      if ($vista === 'inicio') {
        echo '<h1>Bienvenido, ' . htmlspecialchars($_SESSION['nombre']) . '</h1>
        <div class="card">
          <h3>Resumen del Sistema</h3>
          <p>Bienvenido al panel de administración. Desde aquí puedes gestionar todos los aspectos del sistema.</p>
        </div>
        <div class="card">
          <h3>Acciones Rápidas</h3>
          <div class="quick-actions">
            <a href="dashboardAdministrador.php?vista=agregar_producto">Agregar Producto</a>
            <a href="dashboardAdministrador.php?vista=ajustar_inventario">Ajustar Inventario</a>
            <a href="dashboardAdministrador.php?vista=historico">Ver Histórico</a>
          </div>
        </div>';
      } else {
        $archivo = __DIR__ . '/' . $vista . '.php';
        if (file_exists($archivo)) {
          include $archivo;
        } else {
          echo "<h2>La vista solicitada no existe.</h2>";
        }
      }
    ?>
  </div>
</body>

</html>
