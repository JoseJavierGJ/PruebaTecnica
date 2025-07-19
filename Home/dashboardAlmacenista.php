<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['nombre']) || $_SESSION['idRol'] != 2) {
 header("Location: ../index.php");
 exit();
}

// Conectar a la base de datos
require_once '../Config/Connection.php';
$connection = new Connection();
$pdo = $connection->connect();

// Obtener la lista de usuarios
$sql = "SELECT idUsuario, nombre FROM usuarios";
$stmt = $pdo->query($sql);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
  <a href="#" class="active">Inicio</a>

  <!-- Módulo de Inventario -->
  <div class="module-card">
   <h3>Gestión de Inventario</h3>
   <div class="module-actions">
    <a href="#">Ver inventario</a>
   </div>
  </div>

  <!-- Módulo de Salidas -->
  <div class="module-card">
   <h3>Salidas de Productos</h3>
   <div class="module-actions">
    <a href="#">Registrar salida</a>
    <a href="#">Historial de salidas</a>
   </div>
  </div>

  <a href="../InicioSesion/CerrarSesion.php">Cerrar sesión</a>
 </div>

 <div class="main-content">
  <div class="header">
   <h1>Bienvenido, <?php echo htmlspecialchars($_SESSION['nombre']); ?></h1>
  </div>

  <div class="card">
   <h3>Resumen de Almacén</h3>
   <p>Panel principal para la gestión de inventario y salidas de productos.</p>
  </div>

  <div class="card">
   <h3>Acciones Rápidas</h3>
   <div class="quick-actions">
    <a href="#">Consultar inventario</a>
    <a href="#">Registrar salida</a>
   </div>
  </div>
 </div>
</body>

</html>