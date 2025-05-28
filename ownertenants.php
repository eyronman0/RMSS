<?php
include "conn_db.php";

// Handle success/error messages
$success = isset($_GET['success']) ? $_GET['success'] : '';
$error = isset($_GET['error']) ? $_GET['error'] : '';

// Fetch all tenants
$stmt = $conn->prepare("SELECT * FROM tenants");
$stmt->execute();
$tenants = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// Fetch all units
$units = [];
$unit_stmt = $conn->prepare("SELECT id, unit_number FROM units");
$unit_stmt->execute();
$unit_result = $unit_stmt->get_result();
while ($row = $unit_result->fetch_assoc()) {
    $units[] = $row;

$edit_mode = false;
$edit_tenant = null;
if (isset($_GET['edit'])) {
    $edit_id = $_GET['edit'];
    $stmt = $conn->prepare("SELECT * FROM tenants WHERE id = ?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $edit_mode = true;
        $edit_tenant = $result->fetch_assoc();
    }
}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body { background-color: #f4f4f4; }
    .sidebar { min-height: 100vh; background-color: #0b1e7f; color: white; }
    .sidebar .nav-link { color: #fff; }
    .sidebar .nav-link.active, .sidebar .nav-link:hover { background: #2336a3; color: #fff; }
    .dashboard-header { font-size: 2rem; font-weight: bold; margin-bottom: 10px; }
    @media (max-width: 767px) { .sidebar { min-height: auto; } }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar py-4">
                <div class="position-sticky">
                    <h2 class="fs-5 text-center mb-4">Dashboard</h2>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2"><a class="nav-link" href="ownerpage.php">Dashboard</a></li>
                        <li class="nav-item mb-2"><a class="nav-link" href="ownerunits.php">Manage Units</a></li>
                        <li class="nav-item mb-2"><a class="nav-link" href="ownertenants.php">Manage Tenants</a></li>
                        <li class="nav-item mb-2"><a class="nav-link" href="ownermaintenance.php">Maintenance</a></li>
                        <li class="nav-item mb-2"><a class="nav-link" href="ownerfinancials.php">Financials</a></li>
                        <li class="nav-item mb-2"><a class="nav-link" href="ownerdocs.php">Documents</a></li>
                        <li class="nav-item mb-2"><a class="nav-link" href="logout.php">Logout</a></li>
                    </ul>
                </div>
            </nav>
            <!-- Tenant Information -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="card shadow-sm mt-4 mt-md-0">
                    <div class="card-body">
                        <h2 class="card-title mb-3">Tenant Information</h2>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
                        <?php endif; ?>
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                        <?php endif; ?>
                        <form id="tenantForm" class="mb-4" method="POST" action="<?= $edit_mode ? 'req/update.php' : 'req/create.php' ?>">
                            <div class="row g-2">
                                <div class="col-md-2">
                                    <input type="text" name="first_name" class="form-control" placeholder="First Name" required
                                        value="<?= $edit_mode ? htmlspecialchars($edit_tenant['first_name']) : '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="last_name" class="form-control" placeholder="Last Name" required
                                        value="<?= $edit_mode ? htmlspecialchars($edit_tenant['last_name']) : '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="email" name="email" class="form-control" placeholder="Email" required
                                        value="<?= $edit_mode ? htmlspecialchars($edit_tenant['email']) : '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <input type="text" name="contact" class="form-control" placeholder="Contact Number" required
                                        value="<?= $edit_mode ? htmlspecialchars($edit_tenant['contact']) : '' ?>">
                                </div>
                                <div class="col-md-1">
                                    <input type="number" name="age" class="form-control" placeholder="Age" required
                                        value="<?= $edit_mode ? htmlspecialchars($edit_tenant['age']) : '' ?>">
                                </div>
                                <div class="col-md-2">
                                    <select name="unit_id" class="form-control" required>
                                        <option value="">Select Unit</option>
                                        <?php foreach ($units as $unit): ?>
                                            <option value="<?= $unit['id'] ?>"
                                                <?= $edit_mode && $edit_tenant['unit_id'] == $unit['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($unit['unit_number']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="payment_status" class="form-control" required>
                                        <option value="">Status</option>
                                        <option value="Paid" <?= $edit_mode && $edit_tenant['payment_status'] == 'Paid' ? 'selected' : '' ?>>Paid</option>
                                        <option value="Unpaid" <?= $edit_mode && $edit_tenant['payment_status'] == 'Unpaid' ? 'selected' : '' ?>>Unpaid</option>
                                    </select>
                                </div>
                                <?php if ($edit_mode): ?>
                                    <input type="hidden" name="ID" value="<?= $edit_tenant['id'] ?>">
                                <?php endif; ?>
                                <div class="col-md-1">
                                    <button type="submit" class="btn btn-<?= $edit_mode ? 'warning' : 'success' ?> w-100">
                                        <?= $edit_mode ? 'Update' : 'Add' ?> Tenant
                                    </button>
                                </div>
                            </div>
                        </form>
                        <ul class="list-group" id="tenantList">
                            <?php foreach ($tenants as $tenant): ?>
                                <?php
                                // Find unit number for this tenant
                                $unit_number = '';
                                foreach ($units as $unit) {
                                    if ($unit['id'] == $tenant['unit_id']) {
                                        $unit_number = $unit['unit_number'];
                                        break;
                                    }
                                }
                                ?>
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span>
                                        <?= htmlspecialchars($tenant['first_name']) . ' ' . htmlspecialchars($tenant['last_name']) ?>
                                        , Email: <?= htmlspecialchars($tenant['email']) ?>
                                        , Contact: <?= htmlspecialchars($tenant['contact']) ?>
                                        , Age: <?= htmlspecialchars($tenant['age']) ?>
                                        , Room: <?= htmlspecialchars($unit_number) ?>
                                        , Status: <?= htmlspecialchars($tenant['payment_status']) ?>
                                        , Room ID: <?= htmlspecialchars($tenant['unit_id']) ?>
                                    </span>
                                    <div>
                                        <a href="ownertenants.php?edit=<?= $tenant['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="req/delete.php?ID=<?= $tenant['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this tenant?')">Delete</a>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php if ($success): ?>
    <script>
        alert("<?= htmlspecialchars($success) ?>");
    </script>
    <?php endif; ?>
</body>
</html>