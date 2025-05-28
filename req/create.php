<?php
include "../conn_db.php";
if (
    isset($_POST['first_name'], $_POST['last_name'], $_POST['email'], $_POST['contact'], $_POST['age'], $_POST['unit_id'], $_POST['payment_status'])
) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $contact = $_POST['contact'];
    $age = $_POST['age'];
    $unit_id = $_POST['unit_id'];
    $payment_status = $_POST['payment_status'];
    if (
        empty($first_name) || empty($last_name) || empty($email) || empty($contact) ||
        empty($age) || empty($unit_id) || empty($payment_status)
    ) {
        $em = "All fields are required";
        header("Location: ../ownerpagetenants.php?error=$em");
        exit;
    } else {
        $sql = "INSERT INTO tenants (first_name, last_name, email, contact, age, unit_id, payment_status)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssiss", $first_name, $last_name, $email, $contact, $age, $unit_id, $payment_status);
        if ($stmt->execute()) {
            $sm = "Tenant added successfully";
            header("Location: ../ownerpagetenants.php?success=$sm");
            exit;
        } else {
            $em = "Database error: " . $stmt->error;
            header("Location: ../ownerpagetenants.php?error=$em");
            exit;
        }
    }
} else {
    $em = "Invalid form submission";
    header("Location: ../ownerpagetenants.php?error=$em");
    exit;
}