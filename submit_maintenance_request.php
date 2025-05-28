<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestType = $_POST['requestType'] ?? '';
    $description = $_POST['description'] ?? '';
    $tenantName = $_POST['tenantName'] ?? '';

    if ($requestType && $description && $tenantName) {
        $file = __DIR__ . '/maintenance_requests.json';
        $requests = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        $requests[] = [
            'tenant' => $tenantName,
            'type' => $requestType,
            'description' => $description,
            'date' => date('Y-m-d H:i'),
            'status' => 'Pending' // <-- Add this line
        ];
        file_put_contents($file, json_encode($requests, JSON_PRETTY_PRINT));
        $success = "Request submitted! The owner has been notified.";
    } else {
        $error = "All fields are required.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Submit Maintenance Request</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        @media (max-width: 767px) {
            .sidebar {
                min-height: auto;
            }
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
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-4">
    <div class="mb-4 text-center">
        <h1>Submit Maintenance Requests</h1>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <?php if (!empty($success)): ?>
                        <div class="alert alert-success"><?= $success ?></div>
                    <?php elseif (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="tenantName" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="tenantName" name="tenantName" required>
                        </div>
                        <div class="mb-3">
                            <label for="requestType" class="form-label">Request Type</label>
                            <select class="form-control" id="requestType" name="requestType" required>
                                <option value="">Select type</option>
                                <option value="Plumbing">Plumbing</option>
                                <option value="Electrical">Electrical</option>
                                <option value="Heating/Cooling">Heating/Cooling</option>
                                <option value="Other">Other</option>
                            </select>
                            </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>
    </div>
</div>
<!-- Bootstrap JS Bundle CDN -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>