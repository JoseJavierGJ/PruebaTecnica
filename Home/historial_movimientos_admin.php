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

<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Historial de Movimientos</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      background: #f2f2f2;
      margin: 0;
      padding: 20px;
    }

    .card {
      background: #fff;
      border-radius: 8px;
      box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
      padding: 25px;
      max-width: 1200px;
      margin: 20px auto;
    }

    h1 {
      color: #4158d0;
      margin-bottom: 20px;
      text-align: center;
    }

    .filtros-container {
      display: flex;
      flex-direction: column;
      gap: 10px;
      margin-bottom: 15px;
    }

    .filtros-row {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
    }

    .filtros-row input,
    .filtros-row select {
      padding: 6px 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .filtros-row button {
      padding: 6px 12px;
      border-radius: 5px;
      border: none;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .btn-filtrar {
      background-color: #4158d0;
      color: white;
    }

    .btn-filtrar:hover {
      background-color: #3148b0;
      transform: translateY(-2px);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn-limpiar {
      background-color: #aaa;
      color: white;
    }

    .btn-limpiar:hover {
      background-color: #888;
      transform: translateY(-2px);
      box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    /* tabla */
    #tablaMovimientos {
      width: 100%;
      border-collapse: collapse;
      margin: 15px 0;
      font-size: 14px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }

    #tablaMovimientos thead tr {
      background-color: #4158d0;
      color: white;
      text-align: left;
    }

    #tablaMovimientos th,
    #tablaMovimientos td {
      padding: 10px 12px;
      border-right: 1px solid #e0e0e0;
      border-bottom: 1px solid #e0e0e0;
    }

    /*  centradas columnas */
    #tablaMovimientos td {
      text-align: center;
    }
    #tablaMovimientos th {
      text-align: center;
    }

    #tablaMovimientos th:last-child,
    #tablaMovimientos td:last-child {
      border-right: none;
    }

    #tablaMovimientos tbody tr {
      background-color: white;
      transition: all 0.2s;
    }

    #tablaMovimientos tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }

    #tablaMovimientos tbody tr:hover {
      background-color: #f0f0f0;
    }

    /* ordenar */
    #tablaMovimientos th[data-sort] {
      cursor: pointer;
      position: relative;
      padding-right: 20px;
    }

    #tablaMovimientos th[data-sort]::after {
      content: "↓↑";
      position: absolute;
      right: 12px;
      font-size: 15px;
      opacity: 0.5;
    }

    #tablaMovimientos th[data-sort].asc::after {
      content: "↑";
      opacity: 1;
    }

    #tablaMovimientos th[data-sort].desc::after {
      content: "↓";
      opacity: 1;
    }

    /* paginación */
    .pagination-top {
      display: flex;
      justify-content: flex-end;
      margin-bottom: 10px;
    }

    .pagination-bottom {
      display: flex;
      justify-content: center;
      margin-top: 15px;
    }

    .filas-selector {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    #filasPorPagina {
      padding: 6px 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }

    #paginacion {
      display: flex;
      gap: 5px;
    }

    #paginacion button {
      padding: 6px 12px;
      border: 1px solid #ddd;
      border-radius: 4px;
      background: #eee;
      color: #333;
      cursor: pointer;
      transition: all 0.2s;
    }

    #paginacion button:hover {
      background: #e0e0e0;
    }

    #paginacion button.active {
      background: #4158d0;
      color: white;
      border-color: #4158d0;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .filtros-row {
        flex-direction: column;
        align-items: stretch;
      }

      .pagination-top,
      .pagination-bottom {
        flex-direction: column;
        align-items: center;
      }

      #tablaMovimientos {
        display: block;
        overflow-x: auto;
      }
    }
  </style>
</head>

<body>
  <h1>Historial de Movimientos</h1>

  <div class="card">
    <div class="filtros-container">
      <div class="filtros-row">
        <input type="text" id="filtroProducto" placeholder="Producto...">
        <input type="text" id="filtroUsuario" placeholder="Usuario...">
        <select id="filtroTipo">
          <option value="">Todos</option>
          <option value="Entrada">Entrada</option>
          <option value="Salida">Salida</option>
        </select>
      </div>
      <div class="filtros-row">
        <input type="date" id="fechaDesde">
        <input type="date" id="fechaHasta">
        <button onclick="filtrarYActualizar()" class="btn-filtrar">Filtrar</button>
        <button onclick="limpiarFiltros()" class="btn-limpiar">Limpiar</button>
      </div>
    </div>

    <div class="pagination-top">
      <div class="filas-selector">
        <label>Mostrar</label>
        <select id="filasPorPagina">
          <option value="5">5</option>
          <option value="10" selected>10</option>
          <option value="20">20</option>
          <option value="30">30</option>
          <option value="40">40</option>
          <option value="50">50</option>
        </select>
        <label>filas</label>
      </div>
    </div>

    <div style="overflow-x:auto;">
      <table id="tablaMovimientos">
        <thead>
          <tr>
            <th>ID</th>
            <th>Producto</th>
            <th>Usuario</th>
            <th>Tipo</th>
            <th>Cantidad</th>
            <th>Antes</th>
            <th>Después</th>
            <th data-sort="fecha">Fecha</th>
          </tr>
        </thead>
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
              <td data-fecha="<?= htmlspecialchars($m['fechaMovimiento']) ?>">
                <?= htmlspecialchars($m['fechaMovimiento']) ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div class="pagination-bottom">
      <div id="paginacion"></div>
    </div>
  </div>

  <script>
    // eliminar acentos
    function normalizarTexto(texto) {
      return texto.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toLowerCase();
    }

    // Variables globales
    let paginaActual = 1;
    let filasPorPagina = 10;
    let movimientosOriginales = Array.from(document.querySelectorAll("#tablaMovimientos tbody tr"));
    let movimientosFiltrados = [...movimientosOriginales];
    let ordenFecha = 'desc'; // Orden inicial 

    // Elementos del DOM
    const filasPorPaginaSelect = document.getElementById('filasPorPagina');
    const tabla = document.getElementById('tablaMovimientos');
    const paginacion = document.getElementById('paginacion');

    document.addEventListener('DOMContentLoaded', function() {
      filasPorPaginaSelect.addEventListener('change', cambiarFilasPorPagina);

      // Configurar ordenamiento por fecha
      const encabezadoFecha = document.querySelector("#tablaMovimientos th[data-sort='fecha']");
      encabezadoFecha.addEventListener('click', ordenarPorFecha);
      encabezadoFecha.classList.add('desc');

      aplicarPaginacion();
    });

    function cambiarFilasPorPagina(e) {
      filasPorPagina = parseInt(e.target.value);
      paginaActual = 1;
      aplicarPaginacion();
    }

    // Función para aplicar paginación
    function aplicarPaginacion() {
      movimientosOriginales.forEach(f => f.style.display = "none");

      const inicio = (paginaActual - 1) * filasPorPagina;
      const fin = inicio + filasPorPagina;

      movimientosFiltrados.slice(inicio, fin).forEach(fila => fila.style.display = "");

      actualizarControlesPaginacion();
    }

    // actualizar paginator
    function actualizarControlesPaginacion() {
      const totalPaginas = Math.ceil(movimientosFiltrados.length / filasPorPagina);

      paginacion.innerHTML = "";

      if (totalPaginas > 1) {
        for (let i = 1; i <= totalPaginas; i++) {
          const btn = crearBotonPaginacion(i);
          paginacion.appendChild(btn);
        }
      }
    }

    //  botones de paginacion
    function crearBotonPaginacion(numPagina) {
      const btn = document.createElement("button");
      btn.textContent = numPagina;

      if (numPagina === paginaActual) {
        btn.classList.add("active");
      }

      btn.addEventListener("click", () => {
        paginaActual = numPagina;
        aplicarPaginacion();
      });

      return btn;
    }

    // Funcion para ordenar por fecha
    function ordenarPorFecha() {
      const encabezadoFecha = document.querySelector("#tablaMovimientos th[data-sort='fecha']");

      // Cambiar el orden
      ordenFecha = ordenFecha === 'desc' ? 'asc' : 'desc';

      encabezadoFecha.classList.remove('asc', 'desc');
      encabezadoFecha.classList.add(ordenFecha);

      movimientosFiltrados.sort((a, b) => {
        const fechaA = new Date(a.querySelector("td[data-fecha]").dataset.fecha);
        const fechaB = new Date(b.querySelector("td[data-fecha]").dataset.fecha);

        return ordenFecha === 'asc' ? fechaA - fechaB : fechaB - fechaA;
      });

      paginaActual = 1;
      aplicarPaginacion();
    }

    // función para filtrar y actualizar
    function filtrarYActualizar() {
      const producto = normalizarTexto(document.getElementById("filtroProducto").value);
      const usuario = normalizarTexto(document.getElementById("filtroUsuario").value);
      const tipo = document.getElementById("filtroTipo").value;
      const desde = document.getElementById("fechaDesde").value;
      const hasta = document.getElementById("fechaHasta").value;

      movimientosFiltrados = movimientosOriginales.filter(fila => {
        const celdas = fila.querySelectorAll("td");
        const productoTexto = normalizarTexto(celdas[1].textContent);
        const usuarioTexto = normalizarTexto(celdas[2].textContent);
        const tipoTexto = celdas[3].textContent;
        const fechaTexto = celdas[7].textContent.substring(0, 10); // yyyy-mm-dd

        const cumpleProducto = producto === "" || productoTexto.includes(producto);
        const cumpleUsuario = usuario === "" || usuarioTexto.includes(usuario);
        const cumpleTipo = tipo === "" || tipoTexto === tipo;
        const cumpleDesde = desde === "" || fechaTexto >= desde;
        const cumpleHasta = hasta === "" || fechaTexto <= hasta;

        return cumpleProducto && cumpleUsuario && cumpleTipo && cumpleDesde && cumpleHasta;
      });

      // Reiniciar despus de filtrar
      paginaActual = 1;
      aplicarPaginacion();
    }

    function limpiarFiltros() {
      document.getElementById("filtroProducto").value = "";
      document.getElementById("filtroUsuario").value = "";
      document.getElementById("filtroTipo").value = "";
      document.getElementById("fechaDesde").value = "";
      document.getElementById("fechaHasta").value = "";

      // restaurar todos los movimientos
      movimientosFiltrados = [...movimientosOriginales];
      paginaActual = 1;
      aplicarPaginacion();
    }
  </script>
</body>

</html>