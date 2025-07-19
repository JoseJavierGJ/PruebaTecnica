<?php
require_once '../config/Connection.php';
$pdo = (new Connection())->connect();

$sql = "SELECT m.idMovimiento, p.nombre AS producto,
        u.nombre AS usuario, m.cantidad, m.cantidadAnterior,
        m.cantidadNueva, m.fechaMovimiento
        FROM movimientos m
        JOIN productos p ON m.idProducto = p.idProducto
        JOIN usuarios u ON m.idUsuario = u.idUsuario
        WHERE m.idTipoMovimiento = 2
        ORDER BY m.fechaMovimiento DESC";

$movs = $pdo->query($sql)->fetchAll();
?>

<h1>Historial de Salidas</h1>

<div class="card">
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;">
      <thead><tr style="background:#4158d0;color:#fff;">
        <th>ID</th><th>Producto</th><th>Usuario</th><th>Cantidad</th>
        <th>Antes</th><th>Después</th><th>Fecha y hora</th>
      </tr></thead>
      <tbody>
        <?php foreach ($movs as $m): ?>
          <tr>
            <td><?= htmlspecialchars($m['idMovimiento']) ?></td>
            <td><?= htmlspecialchars($m['producto']) ?></td>
            <td><?= htmlspecialchars($m['usuario']) ?></td>
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
