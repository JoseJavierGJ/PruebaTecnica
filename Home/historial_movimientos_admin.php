<?php
require_once '../config/Connection.php';
$pdo = (new Connection())->Connect();

$sql = "SELECT m.idMovimiento, p.nombre AS producto,
        u.nombre AS usuario, m.cantidad, m.cantidadAnterior,
        m.cantidadNueva, m.fechaMovimiento, tm.nombre AS tipo
        FROM movimientos m
        JOIN productos p ON m.idProducto = p.idProducto
        JOIN usuarios u ON m.idUsuario = u.idUsuario
        JOIN tipos_movimiento tm ON m.idTipoMovimiento = tm.idTipoMovimiento
        ORDER BY m.fechaMovimiento DESC";

$movs = $pdo->query($sql)->fetchAll();
?>

<h1>Historial de Movimientos</h1>

<div class="card">
  <div style="margin-bottom: 15px; display: flex; flex-wrap: wrap; gap: 10px; align-items: center;">
    <input type="text" id="filtroProducto" placeholder="Producto..." style="padding: 6px; border-radius: 5px; border: 1px solid #ccc;">
    <input type="text" id="filtroUsuario" placeholder="Usuario..." style="padding: 6px; border-radius: 5px; border: 1px solid #ccc;">
    <select id="filtroTipo" style="padding: 6px; border-radius: 5px;">
      <option value="">Todos</option>
      <option value="Entrada">Entrada</option>
      <option value="Salida">Salida</option>
    </select>
    <input type="date" id="fechaDesde" style="padding: 6px; border-radius: 5px;">
    <input type="date" id="fechaHasta" style="padding: 6px; border-radius: 5px;">
    <button onclick="aplicarFiltros()" style="padding: 6px 12px; border-radius: 5px; background-color: #4158d0; color: white; border: none;">Filtrar</button>
    <button onclick="limpiarFiltros()" style="padding: 6px 12px; border-radius: 5px; background-color: #aaa; color: white; border: none;">Limpiar</button>
  </div>

  <div style="overflow-x:auto;">
    <table id="tablaMovimientos" style="width:100%;border-collapse:collapse;">
      <thead><tr style="background:#4158d0;color:#fff;">
        <th>ID</th><th>Producto</th><th>Usuario</th><th>Tipo</th>
        <th>Cantidad</th><th>Antes</th><th>Después</th><th>Fecha</th>
      </tr></thead>
      <tbody>
        <?php foreach ($movs as $m): ?>
          <tr>
            <td><?= htmlspecialchars($m['idMovimiento']) ?></td>
            <td><?= htmlspecialchars($m['producto']) ?></td>
            <td><?= htmlspecialchars($m['usuario']) ?></td>
            <td><?= htmlspecialchars($m['tipo']) ?></td>
            <td><?= htmlspecialchars($m['cantidad']) ?></td>
            <td><?= htmlspecialchars($m['cantidadAnterior']) ?></td>
            <td><?= htmlspecialchars($m['cantidadNueva']) ?></td>
            <td><?= htmlspecialchars($m['fechaMovimiento']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
function aplicarFiltros() {
  const producto = document.getElementById("filtroProducto").value.toLowerCase();
  const usuario = document.getElementById("filtroUsuario").value.toLowerCase();
  const tipo = document.getElementById("filtroTipo").value;
  const desde = document.getElementById("fechaDesde").value;
  const hasta = document.getElementById("fechaHasta").value;

  const filas = document.querySelectorAll("#tablaMovimientos tbody tr");

  filas.forEach(fila => {
    const celdas = fila.querySelectorAll("td");
    const productoTexto = celdas[1].textContent.toLowerCase();
    const usuarioTexto = celdas[2].textContent.toLowerCase();
    const tipoTexto = celdas[3].textContent;
    const fechaTexto = celdas[7].textContent.substring(0,10); // yyyy-mm-dd

    const cumpleProducto = producto === "" || productoTexto.includes(producto);
    const cumpleUsuario = usuario === "" || usuarioTexto.includes(usuario);
    const cumpleTipo = tipo === "" || tipoTexto === tipo;
    const cumpleDesde = desde === "" || fechaTexto >= desde;
    const cumpleHasta = hasta === "" || fechaTexto <= hasta;

    if (cumpleProducto && cumpleUsuario && cumpleTipo && cumpleDesde && cumpleHasta) {
      fila.style.display = "";
    } else {
      fila.style.display = "none";
    }
  });
}

function limpiarFiltros() {
  document.getElementById("filtroProducto").value = "";
  document.getElementById("filtroUsuario").value = "";
  document.getElementById("filtroTipo").value = "";
  document.getElementById("fechaDesde").value = "";
  document.getElementById("fechaHasta").value = "";
  aplicarFiltros();
}
</script>
