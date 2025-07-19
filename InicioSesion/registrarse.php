<?php
require_once '../config/Connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
  $idRol = $_POST['idRol'];

  try {
    $connection = new Connection();
    $pdo = $connection->connect();

    $sql = "INSERT INTO usuarios (nombre, correo, contrasena, idRol)
            VALUES (:nombre, :correo, :contrasena, :idRol)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      'nombre' => $nombre,
      'correo' => $correo,
      'contrasena' => $contrasena,
      'idRol' => $idRol,
    ]);

    echo "<script>
            alert('Usuario registrado correctamente.');
            window.location.href = '../index.php';
          </script>";

  } catch (\Throwable $th) {
    echo "<script>
            alert('Error al registrar el usuario: " . addslashes($th->getMessage()) . "');
            window.location.href = '../registrarse.php';
          </script>";
  }
}
