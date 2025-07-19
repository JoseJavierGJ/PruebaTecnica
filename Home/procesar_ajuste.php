<?php
require_once '../config/Connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ids = $_POST['idProducto'];
    $cantidades = $_POST['cantidad'];
    $estatuses = $_POST['estatus'];

    $connection = new Connection();
    $pdo = $connection->connect();

    for ($i = 0; $i < count($ids); $i++) {
        $stmt = $pdo->prepare("UPDATE productos SET cantidad = ?, estatus = ? WHERE idProducto = ?");
        $stmt->execute([
            $cantidades[$i],
            $estatuses[$i],
            $ids[$i]
        ]);
    }

    // Redirigir con mensaje de éxito
    header("Location: dashboardAdministrador.php?vista=ajustar_inventario&success=1");
    exit;
}
?>
