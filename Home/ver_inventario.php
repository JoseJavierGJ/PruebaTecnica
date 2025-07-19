<?php
require_once '../config/Connection.php';

$connection = new Connection();
$pdo = $connection->connect();

$sql = "SELECT idProducto, nombre, descripcion, cantidad, estatus FROM productos";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<h1>Inventario</h1>

<div class="card">
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
            <td style="padding: 10px;"><?php echo $producto['cantidad']; ?></td>
            <td style="padding: 10px;"><?php echo $producto['estatus'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div id="paginacion" style="margin-top: 15px; text-align: center;"></div>
</div>

<script>
const buscador = document.getElementById('buscador');
const filasPorPagina = document.getElementById('filasPorPagina');
const tabla = document.getElementById('tablaProductos');
const paginacion = document.getElementById('paginacion');

let paginaActual = 1;

// Aplica búsqueda y resetea paginación
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
  
  // Filtrar filas según el texto
  const filasFiltradas = filas.filter(fila => {
    const celdas = fila.querySelectorAll("td");
    return Array.from(celdas).some(td =>
      td.textContent.toLowerCase().includes(texto)
    );
  });

  // Ocultar todas
  filas.forEach(f => f.style.display = "none");

  const filasPorPag = parseInt(filasPorPagina.value);
  const totalPaginas = Math.ceil(filasFiltradas.length / filasPorPag);

  // Mostrar solo las que tocan en esta página
  const inicio = (paginaActual - 1) * filasPorPag;
  const fin = inicio + filasPorPag;

  filasFiltradas.slice(inicio, fin).forEach(fila => fila.style.display = "");

  // Actualizar paginación
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

window.addEventListener("load", aplicarFiltroYPaginacion);
</script>

