<?php
require_once '../config/Connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $correo = $_POST['correo'];
  $contrasena = password_hash($_POST['contrasena'], PASSWORD_BCRYPT);
  $idRol = $_POST['idRol'];

  // Validacion correo 
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

  // Validación contraseña
  if (strlen($_POST['contrasena']) < 6) {
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

    // Primero verificamos si el correo ya existe
    $sqlCheck = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo";
    $stmtCheck = $pdo->prepare($sqlCheck);
    $stmtCheck->execute(['correo' => $correo]);
    $count = $stmtCheck->fetchColumn();

    if ($count > 0) {
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
            title: "Correo ya registrado",
            text: "El correo electrónico ya está en uso. Por favor utiliza otro correo.",
            confirmButtonColor: "#4158d0"
          }).then(() => {
            window.location.href = "../registrarse.php";
          });
        </script>
      </body>
      </html>';
      exit;
    }

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

  } catch (PDOException $e) {
    if ($e->getCode() == '23000') { // Código para violación de integridad (duplicado)
      $mensaje = "El correo electrónico ya está registrado. Por favor utiliza otro correo.";
    } else {
      $mensaje = addslashes($e->getMessage());
    }
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