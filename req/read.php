<?php

function readTenants($conn) {
    $sql = "SELECT * FROM tenants ORDER BY created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        return [];
    }
}