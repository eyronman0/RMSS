<?php
include "../conn_db.php";

if (isset($_GET['ID'])) {
    $ID = $_GET['ID'];
    $sql = "DELETE FROM tenants WHERE ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$ID]);
    $sm = "Tenant deleted successfully";
    header("Location: ../ownertenants.php?success=$sm");
    exit;
}