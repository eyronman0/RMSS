<?php
// filepath: c:\xampp2\htdocs\RMS\track_maintenance_requests.php

// Read the JSON file
$requests = [];
$json_file = 'maintenance_requests.json';
if (file_exists($json_file)) {
    $json = file_get_contents($json_file);
    $requests = json_decode($json, true);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Track Maintenance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f4f4f4;
        }
        .sidebar {
            min-height: 100vh;
            background-color: #0b1e7f;
            color: white;
        }
        .sidebar .nav-link {
            color: #fff;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: #2336a3;
            color: #fff;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <nav class="col-md-3 col-lg-2 d-md-block sidebar py-4">
            <div class="position-sticky">
                <h2 class="fs-5 text-center mb-4">Apartment Management</h2>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="main.php">Dashboard</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="personal_info.php">View Personal Info</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="submit_maintenance_request.php">Submit Maintenance Request</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link active" href="track_maintenance_requests.php">Track Maintenance Requests</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="pay_rent.php">Pay Rent</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </nav>
        <!-- Main Content -->
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="container mt-5">
                <h2>Maintenance Requests</h2>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Tenant</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($requests)): ?>
                            <?php foreach ($requests as $req): ?>
                                <tr>
                                    <td><?= htmlspecialchars($req['tenant']) ?></td>
                                    <td><?= htmlspecialchars($req['type']) ?></td>
                                    <td><?= htmlspecialchars($req['description']) ?></td>
                                    <td><?= htmlspecialchars($req['date']) ?></td>
                                    <td><?= htmlspecialchars($req['status']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr><td colspan="5" class="text-center">No maintenance requests found.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</div>
</body>
</html>