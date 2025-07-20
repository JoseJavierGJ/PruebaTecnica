<?php
require_once '../config/Connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
  $idRol = $_POST['idRol'];

    // Validacion  correo 
  if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
      <script>
        Swal.fire({
          icon: "error",
          title: "Correo inválido",
          text: "Por favor ingresa un correo electrónico válido",
          confirmButtonColor: "#4158d0"
        }).then(() => {
          window.location.href = "../registrarse.php";
        });
      </script>
    </body>
    </html>';
    exit;
  }

  // Validación ontraseña
  if (strlen($contrasena) < 6) {
    echo '
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
      <script>
        Swal.fire({
          icon: "error",
          title: "Contraseña muy corta",
          text: "La contraseña debe tener al menos 6 caracteres",
          confirmButtonColor: "#4158d0"
        }).then(() => {
          window.location.href = "../registrarse.php";
        });
      </script>
    </body>
    </html>';
    exit;
  }

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

    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
      <script>
        Swal.fire({
          icon: 'success',
          title: 'Registro exitoso',
          text: 'Ahora puedes iniciar sesión',
          confirmButtonColor: '#4158d0'
        }).then(() => {
          window.location.href = '../index.php';
        });
      </script>
    </body>
    </html>
    <?php

  } catch (\Throwable $th) {
    $mensaje = addslashes($th->getMessage());
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
      <meta charset="UTF-8">
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
      <script>
        Swal.fire({
          icon: 'error',
          title: 'Error al registrar',
          text: '<?php echo $mensaje; ?>',
          confirmButtonColor: '#4158d0'
        }).then(() => {
          window.location.href = '../registrarse.php';
        });
      </script>
    </body>
    </html>
    <?php
  }
}
