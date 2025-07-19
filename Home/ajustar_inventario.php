<?php
require_once '../config/Connection.php';

$connection = new Connection();
$pdo = $connection->connect();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($_POST['productos'] as $idProducto => $datos) {
    $cantidad = intval($datos['cantidad']);
    $estatus = intval($datos['estatus']);

    $stmt = $pdo->prepare("UPDATE productos SET cantidad = ?, estatus = ? WHERE idProducto = ?");
    $stmt->execute([$cantidad, $estatus, $idProducto]);
  }

  header("Location: dashboardAdministrador.php?vista=ajustar_inventario&success=1");
  exit;
}

$sql = "SELECT idProducto, nombre, descripcion, cantidad, estatus FROM productos";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Ajustar Inventario</h1>

<div class="card">

  <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
    <div id="mensajeExito" style="
      position: fixed;
      top: 20px;
      right: 20px;
      background-color: #4CAF50;
      color: white;
      padding: 12px 20px;
      border-radius: 8px;
      box-shadow: 0 4px 8px rgba(0,0,0,0.2);
      z-index: 9999;
      font-size: 16px;
    ">
      ✅ Cambios guardados correctamente
    </div>
  <?php endif; ?>

  <form method="POST">
    <div style="margin-bottom: 10px; display: flex; justify-content: space-between; align-items: center;">
      <input type="text" id="buscador" placeholder="Buscar producto..." style="padding: 8px; border-radius: 6px; border: 1px solid #ccc; width: 250px;">
      <label>
        Mostrar
        <select id="filasPorPagina" style="padding: 5px 10px; border-radius: 6px;">
          <option value="5">5</option>
          <option value="10" selected>10</option>
          <option value="20">20</option>
        </select>
        filas
      </label>
    </div>

    <div style="overflow-x:auto;">
      <table id="tablaProductos" style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background-color: #4158d0; color: white;">
            <th style="padding: 10px;">ID</th>
            <th style="padding: 10px;">Nombre</th>
            <th style="padding: 10px;">Descripción</th>
            <th style="padding: 10px;">Cantidad</th>
            <th style="padding: 10px;">Estatus</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($productos as $producto): ?>
            <tr>
              <td style="padding: 10px;"><?php echo $producto['idProducto']; ?></td>
              <td style="padding: 10px;"><?php echo htmlspecialchars($producto['nombre']); ?></td>
              <td style="padding: 10px;"><?php echo htmlspecialchars($producto['descripcion']); ?></td>
              <td style="padding: 10px;">
                <input type="number" name="productos[<?php echo $producto['idProducto']; ?>][cantidad]" value="<?php echo $producto['cantidad']; ?>" style="width: 80px; padding: 6px;">
              </td>
              <td style="padding: 10px;">
                <select name="productos[<?php echo $producto['idProducto']; ?>][estatus]" style="padding: 6px;">
                  <option value="1" <?php if ($producto['estatus'] == 1) echo 'selected'; ?>>Activo</option>
                  <option value="0" <?php if ($producto['estatus'] == 0) echo 'selected'; ?>>Inactivo</option>
                </select>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div style="margin-top: 20px; text-align: center;">
      <button type="submit" style="padding: 10px 20px; background-color: #4158d0; color: white; border: none; border-radius: 5px; cursor: pointer;">
        Guardar Cambios
      </button>
    </div>
  </form>

  <div id="paginacion" style="margin-top: 15px; text-align: center;"></div>
</div>

<script>
const buscador = document.getElementById('buscador');
const filasPorPagina = document.getElementById('filasPorPagina');
const tabla = document.getElementById('tablaProductos');
const paginacion = document.getElementById('paginacion');

let paginaActual = 1;

buscador.addEventListener("input", () => {
  paginaActual = 1;
  aplicarFiltroYPaginacion();
});

filasPorPagina.addEventListener("change", () => {
  paginaActual = 1;
  aplicarFiltroYPaginacion();
});

function aplicarFiltroYPaginacion() {
  const texto = buscador.value.toLowerCase();
  const filas = Array.from(tabla.querySelectorAll("tbody tr"));

  const filasFiltradas = filas.filter(fila => {
    const celdas = fila.querySelectorAll("td");
    return Array.from(celdas).some(td =>
      td.textContent.toLowerCase().includes(texto)
    );
  });

  filas.forEach(f => f.style.display = "none");

  const filasPorPag = parseInt(filasPorPagina.value);
  const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPag);

  const inicio = (paginaActual - 1) * filasPorPag;
  const fin = inicio + filasPorPag;

  filasFiltradas.slice(inicio, fin).forEach(fila => fila.style.display = "");

  paginacion.innerHTML = "";

  if (totalPaginas > 1) {
    for (let i = 1; i <= totalPaginas; i++) {
      const btn = document.createElement("button");
      btn.textContent = i;
      btn.style.margin = "0 5px";
      btn.style.padding = "6px 12px";
      btn.style.border = "none";
      btn.style.borderRadius = "4px";
      btn.style.background = i === paginaActual ? "#4158d0" : "#eee";
      btn.style.color = i === paginaActual ? "#fff" : "#333";
      btn.style.cursor = "pointer";

      btn.addEventListener("click", () => {
        paginaActual = i;
        aplicarFiltroYPaginacion();
      });

      paginacion.appendChild(btn);
    }
  }
}

// Ocultar mensaje de éxito automáticamente
const mensaje = document.getElementById("mensajeExito");
if (mensaje) {
  setTimeout(() => {
    mensaje.style.display = "none";
  }, 3000);
}

window.addEventListener("load", aplicarFiltroYPaginacion);
</script>
