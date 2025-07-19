<?php
require_once '../config/Connection.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $correo = $_POST['username'];
  $contrasena = $_POST['password'];

  try {
    $connection = new Connection();
    $pdo = $connection->connect();

    $sql = "SELECT * FROM usuarios WHERE correo = :correo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['correo' => $correo]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($contrasena, $user['contrasena'])) {
      $_SESSION['idUsuario'] = $user['idUsuario'];
      $_SESSION['nombre'] = $user['nombre'];
      $_SESSION['idRol'] = $user['idRol'];

      if ($user['idRol'] == 1) {
        header('Location: ../Home/dashboardAdministrador.php');
      } elseif ($user['idRol'] == 2) {
        header('Location: ../Home/dashboardAlmacenista.php');
      } else {
        echo 'Acceso Denegado';
      }
      exit();
    } else {
      echo "<script>alert('Credenciales incorrectas.'); window.location.href = '../index.php';</script>";
    }
  } catch (\Throwable $th) {
    echo "Error en la conexión: " . $th->getMessage();
    exit;
  }
}
