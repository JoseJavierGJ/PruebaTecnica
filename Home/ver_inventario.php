<?php
require_once '../config/Connection.php';

$connection = new Connection();
$pdo = $connection->connect();

$sql = "SELECT idProducto, nombre, descripcion, cantidad, estatus FROM productos";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ver Inventario</title>
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
    
    /* búsqueda y paginación */
    .table-controls {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
      flex-wrap: wrap;
      gap: 15px;
    }
    
    #buscador {
      padding: 8px 15px;
      border: 1px solid #ddd;
      border-radius: 6px;
      width: 250px;
      font-size: 14px;
    }
    
    .filas-selector {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    #filasPorPagina {
      padding: 6px 10px;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 14px;
      margin-left: 5px;
    }
    
    /* tabla */
    #tablaProductos {
      width: 100%;
      border-collapse: collapse;
      margin: 15px 0;
      font-size: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.05);
    }
    
    #tablaProductos thead tr {
      background-color: #4158d0;
      color: white;
      text-align: left;
    }
    
    #tablaProductos th,
    #tablaProductos td {
      padding: 12px 15px;
      border-right: 1px solid #e0e0e0; /* Líneas verticales */
      border-bottom: 1px solid #e0e0e0; /* Líneas horizontales */
    }

    #tablaProductos td {
      text-align: center;
    }
    #tablaProductos th {
      text-align: center;
    }
    
    #tablaProductos th:last-child,
    #tablaProductos td:last-child {
      border-right: none; 
    }
    
    #tablaProductos tbody tr {
      background-color: white;
      transition: all 0.2s;
    }
    
    #tablaProductos tbody tr:nth-child(even) {
      background-color: #f9f9f9;
    }
    
    #tablaProductos tbody tr:hover {
      background-color: #f0f0f0;
    }
    
    /* Paginator */
    #paginacion {
      margin-top: 15px;
      text-align: center;
    }
    
    #paginacion button {
      margin: 0 5px;
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
      .table-controls {
        flex-direction: column;
        align-items: stretch;
      }
      
      #buscador {
        width: 100%;
      }
      
      #tablaProductos {
        display: block;
        overflow-x: auto;
      }
    }
  </style>
</head>
<body>
  <h1>Inventario</h1>

  <div class="card">
    <div class="table-controls">
      <input type="text" id="buscador" placeholder="Buscar producto...">
      <div class="filas-selector">
        <label>Mostrar</label>
        <select id="filasPorPagina">
          <option value="5">5</option>
          <option value="10" selected>10</option>
          <option value="20">20</option>
        </select>
        <label>filas</label>
      </div>
    </div>

    <div style="overflow-x:auto;">
      <table id="tablaProductos">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Cantidad</th>
            <th>Estatus</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($productos as $producto): ?>
            <tr>
              <td><?php echo $producto['idProducto']; ?></td>
              <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
              <td><?php echo htmlspecialchars($producto['descripcion']); ?></td>
              <td><?php echo $producto['cantidad']; ?></td>
              <td><?php echo $producto['estatus'] == 1 ? 'Activo' : 'Inactivo'; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>

    <div id="paginacion"></div>
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
          if (i === paginaActual) {
            btn.classList.add("active");
          }

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
</body>
</html>