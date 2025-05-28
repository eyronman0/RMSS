<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'], $_POST['req_index'])) {
    $file = __DIR__ . '/maintenance_requests.json';
    $requests = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
    $index = intval($_POST['req_index']);
    $new_status = $_POST['update_status'];
    if (isset($requests[$index])) {
        $requests[$index]['status'] = $new_status;
        file_put_contents($file, json_encode($requests, JSON_PRETTY_PRINT));
    }
    header("Location: ownermaintenance.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner Dashboard</title>
    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    .dashboard-header {
      font-size: 2rem;
      font-weight: bold;
      margin-bottom: 10px;
    }
    .card h3, .card h2 {
      margin-top: 0;
      margin-bottom: 10px;
      color: #0b1e7f;
      font-size: 1.25rem;
    }
    .card p {
      margin: 0;
      color: #555;
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
                    <h2 class="fs-5 text-center mb-4">Owner Panel</h2>
                    <ul class="nav flex-column">
                        <li class="nav-item mb-2">
                            <a class="nav-link active" href="ownerpage.php">Dashboard</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownerunits.php">Manage Units</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownertenants.php">Manage Tenants</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownermaintenance.php">Maintenance</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownerfinancials.php">Financials</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="ownerdocs.php">Documents</a>
                        </li>
                        <li class="nav-item mb-2">
                            <a class="nav-link" href="logout.php">Logout</a>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-5 py-4">
                <h1 class="dashboard-header">Owner Dashboard</h1>
                <div class="row">
                    <!-- Maintenance Requests -->
                    <div class="col-md-6">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h2 class="card-title mb-3">Maintenance Requests</h2>
                                <ul class="list-group">
                                    <?php
                                    $file = __DIR__ . '/maintenance_requests.json';
                                    if (file_exists($file)) {
                                        $requests = json_decode(file_get_contents($file), true);
                                        if ($requests && count($requests) > 0) {
                                            foreach (array_reverse($requests, true) as $i => $req) {
                                                $status = isset($req['status']) ? $req['status'] : 'Pending';
                                                echo '<li class="list-group-item">';
                                                echo '<strong>' . htmlspecialchars($req['tenant']) . '</strong> (' . htmlspecialchars($req['type']) . ')<br>';
                                                echo htmlspecialchars($req['description']) . '<br>';
                                                echo '<small>' . htmlspecialchars($req['date']) . '</small><br>';
                                                echo '<form method="POST" class="d-inline">';
                                                echo '<input type="hidden" name="req_index" value="' . $i . '">';
                                                echo '<select name="update_status" class="form-select d-inline w-auto">';
                                                foreach (['Pending', 'In Progress', 'Completed'] as $option) {
                                                    $selected = ($status === $option) ? 'selected' : '';
                                                    echo "<option value=\"$option\" $selected>$option</option>";
                                                }
                                                echo '</select> ';
                                                echo '<button type="submit" class="btn btn-sm btn-primary">Update</button>';
                                                echo '</form>';
                                                echo '<span class="badge bg-secondary ms-2">' . htmlspecialchars($status) . '</span>';
                                                echo '</li>';
                                            }
                                        } else {
                                            echo '<li class="list-group-item">No requests yet.</li>';
                                        }
                                    } else {
                                        echo '<li class="list-group-item">No requests yet.</li>';
                                    }
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- Bootstrap JS Bundle CDN -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
