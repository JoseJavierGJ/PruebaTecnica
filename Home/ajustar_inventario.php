<?php
require_once '../config/Connection.php';

$connection = new Connection();
$pdo = $connection->connect();

$hayError = false;
$idUsuario = $_SESSION['idUsuario'] ?? null; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  foreach ($_POST['productos'] as $idProducto => $datos) {
    $nuevaCantidad = intval($datos['cantidad']);
    $estatus = intval($datos['estatus']);

    // Obtener cantidad actual
    $stmtActual = $pdo->prepare("SELECT cantidad FROM productos WHERE idProducto = ?");
    $stmtActual->execute([$idProducto]);
    $cantidadActual = $stmtActual->fetchColumn();

    // Solo registrar si hubo aumento
    if ($nuevaCantidad > $cantidadActual) {
      $stmt = $pdo->prepare("UPDATE productos SET cantidad = ?, estatus = ? WHERE idProducto = ?");
      $stmt->execute([$nuevaCantidad, $estatus, $idProducto]);
      $cantidadMovida = $nuevaCantidad - $cantidadActual;

      $stmtMov = $pdo->prepare("INSERT INTO movimientos 
        (idProducto, idUsuario, idTipoMovimiento, cantidad, cantidadAnterior, cantidadNueva)
        VALUES (?, ?, 1, ?, ?, ?)");
      $stmtMov->execute([$idProducto, $idUsuario, $cantidadMovida, $cantidadActual, $nuevaCantidad]);
    } else if ($nuevaCantidad < $cantidadActual) {
      // No se permite reducir, marca error
      $hayError = true;
    }
  }

  if ($hayError) {
    header("Location: dashboardAdministrador.php?vista=ajustar_inventario&error=1");
  } else {
    header("Location: dashboardAdministrador.php?vista=ajustar_inventario&success=1");
  }
  exit;
}

// Obtener productos
$sql = "SELECT idProducto, nombre, descripcion, cantidad, estatus FROM productos";
$stmt = $pdo->query($sql);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ajustar Inventario</title>
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
    
    /* notificaciones */
    .notificacion {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
      padding: 12px 20px;
      margin-bottom: 20px;
      border-radius: 8px;
      font-size: 16px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
      animation: fadeIn 0.5s ease-in-out;
    }
    
    .notificacion.error {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
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
      border-right: 1px solid #e0e0e0;
      border-bottom: 1px solid #e0e0e0;
    }
    
    #tablaProductos th:last-child,
    #tablaProductos td:last-child {
      border-right: none;
    }

    #tablaProductos td {
      text-align: center;
    }
    #tablaProductos th {
      text-align: center;
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
    
    /* Estilos para inputs y selects dentro de la tabla */
    #tablaProductos input[type="number"] {
      width: 80px;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      text-align: center;
    }
    
    #tablaProductos select {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
      min-width: 100px;
    }
    
    /* Botón de guardar cambios */
    .submit-btn-container {
      text-align: right;
      margin-top: 20px;
    }
    
    button[type="submit"] {
      padding: 10px 25px;
      background-color: #4158d0;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      font-size: 16px;
      transition: all 0.3s;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    button[type="submit"]:hover {
      background-color: #3148b0;
      transform: translateY(-2px);
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
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
    
    @keyframes fadeIn {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
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
  <h1>Ajustar Inventario</h1>

  <div class="card">
    <?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
      <div class="notificacion">
        ✅ Cambios guardados correctamente
      </div>
    <?php elseif (isset($_GET['error']) && $_GET['error'] == 1): ?>
      <div class="notificacion error">
        ⚠️ No se permite reducir la cantidad de productos. Solo puedes aumentarla.
      </div>
    <?php endif; ?>

    <form method="POST">
      <div class="table-controls">
        <input type="text" id="buscador" placeholder="Buscar producto...">
        <div class="filas-selector">
          <label>Mostrar</label>
          <select id="filasPorPagina">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
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
                <td>
                  <input type="number" name="productos[<?php echo $producto['idProducto']; ?>][cantidad]" 
                         value="<?php echo $producto['cantidad']; ?>" min="0">
                </td>
                <td>
                  <select name="productos[<?php echo $producto['idProducto']; ?>][estatus]">
                    <option value="1" <?= $producto['estatus'] == 1 ? 'selected' : '' ?>>Activo</option>
                    <option value="0" <?= $producto['estatus'] == 0 ? 'selected' : '' ?>>Inactivo</option>
                  </select>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>

      <div class="submit-btn-container">
        <button type="submit">Guardar Cambios</button>
      </div>
    </form>

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

    const mensaje = document.querySelector(".notificacion");
    if (mensaje) {
      setTimeout(() => {
        mensaje.style.display = "none";
      }, 3000);
    }

    window.addEventListener("load", aplicarFiltroYPaginacion);
  </script>
</body>
</html>