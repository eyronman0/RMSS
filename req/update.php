<?php
include "../conn_db.php";
if (
    isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['contact'], $_POST['age'], $_POST['unit_id'], $_POST['payment_status'], $_POST['ID'])
) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $age = $_POST['age'];
    $unit_id = $_POST['unit_id'];
    $payment_status = $_POST['payment_status'];
    $ID = $_POST['ID'];
    if (
        empty($first_name) || empty($last_name) || empty($email) || empty($contact) ||
        empty($age) || empty($unit_id) || empty($payment_status)
    ) {
        $em = "All fields are required";
        header("Location: ../ownertenants.php?error=$em");
        exit;
    } else {
        $sql = "UPDATE tenants SET first_name=?, last_name=?, email=?, contact=?, age=?, unit_id=?, payment_status=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssissi", $first_name, $last_name, $email, $contact, $age, $unit_id, $payment_status, $ID);
        $stmt->execute();
        $sm = "Tenant updated successfully";
        header("Location: ../ownertenants.php?success=$sm");
        exit;
    }
}