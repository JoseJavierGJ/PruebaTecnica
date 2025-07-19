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
  <div style="overflow-x:auto;">
    <table style="width:100%;border-collapse:collapse;">
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
