<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <title>Registro de Usuario</title>
  <link rel="stylesheet" href="css/style.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <header class="login-header">
    Sistema de Inventario
  </header>
  <div class="wrapper">
    <div class="title">Registro</div>
    <form action="InicioSesion/registrarse.php" method="POST" id="registroForm">
      <div class="field">
        <input type="text" id="nombre" name="nombre" required>
        <label for="nombre">Nombre</label>
      </div>
      <div class="field">
        <input type="email" id="correo" name="correo" required>
        <label for="correo">Correo</label>
      </div>
      <div class="field">
        <input type="password" id="contrasena" name="contrasena" required minlength="6">
        <label for="contrasena">Contraseña</label>
      </div>
      <div class="field">
        <select id="idRol" name="idRol" required>
          <option value="" selected disabled>-- Seleccione un rol --</option>
          <option value="1">Administrador</option>
          <option value="2">Almacenista</option>
        </select>
        <label for="idRol">Rol</label>
      </div>
      <div class="field">
        <input type="submit" value="Registrar">
      </div>
      <div class="signup-link">
        ¿Ya tienes cuenta? <a href="index.php">Iniciar sesión</a>
      </div>
    </form>
  </div>

  <footer class="login-footer">
    © 2025 Sistema de Inventario. Desarrollado por José Javier.
  </footer>

  <script>
    document.getElementById('registroForm').addEventListener('submit', function(e) {
      const correo = document.getElementById('correo').value;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailRegex.test(correo)) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Correo inválido',
          text: 'Por favor ingresa un correo electrónico válido',
          confirmButtonColor: '#4158d0'
        });
        return false;
      }

      // Validación adicional de contraseña
      const contrasena = document.getElementById('contrasena').value;
      if (contrasena.length < 6) {
        e.preventDefault();
        Swal.fire({
          icon: 'error',
          title: 'Contraseña muy corta',
          text: 'La contraseña debe tener al menos 6 caracteres',
          confirmButtonColor: '#4158d0'
        });
        return false;
      }

      return true;
    });
  </script>
</body>

</html>