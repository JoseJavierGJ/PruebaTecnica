<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="css/style.css">
  <title>Inicio de Sesión</title>
</head>

<body>
  <header class="login-header">
    Sistema de Inventario
  </header>
  <div class="wrapper">
    <div class="title">Inicia sesión</div>
    <form action="InicioSesion/inicioSesion.php" method="POST">
      <div class="field">
        <input type="text" required name="username">
        <label>Correo</label>
      </div>
      <div class="field">
        <input type="password" required name="password">
        <label>Contraseña</label>
      </div>
      <div class="field">
        <input type="submit" value="Ingresar">
      </div>
      <div class="signup-link"><a href="registrarse.php">Registrarse Ahora</a></div>
    </form>
  </div>

  <footer class="login-footer">
    © 2025 Sistema de Inventario. Desarrollado por José Javier.
  </footer>
</body>

</html>