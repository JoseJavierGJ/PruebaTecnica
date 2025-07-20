<?php
require_once '../config/Connection.php';

$mensaje = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $nombre = $_POST['nombre'];
  $descripcion = $_POST['descripcion'];
  $cantidad = isset($_POST['cantidad']) ? (int)$_POST['cantidad'] : 0;

  try {
    $connection = new Connection();
    $pdo = $connection->connect();

    $sql = "INSERT INTO productos (nombre, descripcion, cantidad) VALUES (:nombre, :descripcion, :cantidad)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      'nombre' => $nombre,
      'descripcion' => $descripcion,
      'cantidad' => $cantidad
    ]);

    $mensaje = 'Producto agregado correctamente ✅';
  } catch (Throwable $th) {
    $mensaje = 'Error al agregar el producto ❌: ' . $th->getMessage();
  }
}
?>
<div class="seccion-titulo">
  <h1>Agregar Producto</h1>
</div>


<?php if (!empty($mensaje)) : ?>
  <div class="notificacion"><?php echo htmlspecialchars($mensaje); ?></div>
  <script>
    setTimeout(() => {
      const noti = document.querySelector('.notificacion');
      if (noti) noti.style.display = 'none';
    }, 4000);
  </script>
<?php endif; ?>

<div class="card">
  <form method="POST" action="">

    <div class="field">
      <label for="nombre">Nombre del producto</label><br>
      <input type="text" name="nombre" id="nombre" required>
    </div>

    <div class="field">
      <label for="descripcion">Descripción</label><br>
      <textarea name="descripcion" id="descripcion" rows="4" style="width: 100%; border-radius: 10px; padding: 10px;"></textarea>
    </div>


    <div class="field">
      <label for="cantidad">Cantidad inicial</label><br>
      <input type="number" name="cantidad" id="cantidad" min="0" value="0">
    </div>


    <div class="field submit-container">
      <input type="submit" value="Agregar Producto">
    </div>
  </form>
</div>
